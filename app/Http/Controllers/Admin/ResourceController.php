<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Metadata-driven CRUD base. A concrete controller describes its model, list columns
 * and form fields; the shared admin.crud.* views render everything.
 */
abstract class ResourceController extends Controller
{
    /** @return class-string<Model> */
    abstract protected function model(): string;

    /** title, singular, route (name prefix, e.g. admin.clients), icon, subtitle, can_create */
    abstract protected function meta(): array;

    abstract protected function columns(): array;

    abstract protected function fields(?Model $record = null): array;

    protected function searchable(): array
    {
        return [];
    }

    protected function filters(): array
    {
        return [];
    }

    protected function with(): array
    {
        return [];
    }

    protected function perPage(): int
    {
        return 12;
    }

    protected function order($q)
    {
        return $q->latest('id');
    }

    /** Return an error message to block deletion. */
    protected function guardDelete(Model $record): ?string
    {
        return null;
    }

    /** Extra per-row buttons: [['label','url','method','data'=>[],'tone'=>'ok|danger|ghost']] */
    protected function rowActions(Model $record): array
    {
        return [];
    }

    /** Adjust validated data before saving. */
    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        return $data;
    }

    protected function saved(Model $record, Request $request, bool $created): void
    {
    }

    protected function viewData(): array
    {
        return [];
    }

    // ───────────────────────── actions ─────────────────────────

    public function index(Request $request)
    {
        $q = ($this->model())::query()->with($this->with());

        if (($term = trim((string) $request->query('q'))) !== '' && $this->searchable()) {
            $q->where(function (Builder $w) use ($term) {
                foreach ($this->searchable() as $col) {
                    if (str_contains($col, '.')) {
                        [$rel, $c] = explode('.', $col, 2);
                        $w->orWhereHas($rel, fn ($r) => $r->where($c, 'like', "%{$term}%"));
                    } else {
                        $w->orWhere($col, 'like', "%{$term}%");
                    }
                }
            });
        }

        foreach ($this->filters() as $f) {
            $val = $request->query($f['name']);
            if ($val !== null && $val !== '') {
                $q->where($f['name'], $val);
            }
        }

        $rows = $this->order($q)->paginate($this->perPage())->withQueryString();

        return view('admin.crud.index', [
            'meta' => $this->meta(),
            'columns' => $this->columns(),
            'filters' => $this->filters(),
            'rows' => $rows,
            'searchable' => (bool) $this->searchable(),
            'ctrl' => $this,
        ] + $this->viewData());
    }

    public function create()
    {
        return view('admin.crud.form', [
            'meta' => $this->meta(),
            'fields' => $this->fields(),
            'record' => null,
        ] + $this->viewData());
    }

    public function store(Request $request): RedirectResponse
    {
        $record = $this->persist($request, null);

        return redirect()->route($this->meta()['route'] . '.index')
            ->with('success', $this->meta()['singular'] . ' created successfully.');
    }

    public function edit($id)
    {
        $record = ($this->model())::findOrFail($id);

        return view('admin.crud.form', [
            'meta' => $this->meta(),
            'fields' => $this->fields($record),
            'record' => $record,
        ] + $this->viewData());
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $record = ($this->model())::findOrFail($id);
        $this->persist($request, $record);

        return redirect()->route($this->meta()['route'] . '.index')
            ->with('success', $this->meta()['singular'] . ' updated.');
    }

    public function destroy($id): RedirectResponse
    {
        $record = ($this->model())::findOrFail($id);

        if ($msg = $this->guardDelete($record)) {
            return back()->with('error', $msg);
        }

        $record->delete();

        return back()->with('success', $this->meta()['singular'] . ' deleted.');
    }

    /** Quick status change (approve / reject / mark read…). */
    public function setStatus(Request $request, $id): RedirectResponse
    {
        $record = ($this->model())::findOrFail($id);
        $allowed = [];
        foreach ($this->fields($record) as $f) {
            if ($f['name'] === 'status') {
                $allowed = array_keys($f['options'] ?? []);
            }
        }
        $data = $request->validate(['status' => ['required', Rule::in($allowed)]]);
        $record->update($data);

        return back()->with('success', 'Status updated to ' . str_replace('_', ' ', $data['status']) . '.');
    }

    // ───────────────────────── internals ─────────────────────────

    protected function persist(Request $request, ?Model $record): Model
    {
        $fields = $this->fields($record);

        $rules = [];
        foreach ($fields as $f) {
            if (! empty($f['readonly']) || ! empty($f['virtual'])) {
                continue;
            }
            $type = $f['type'] ?? 'text';
            if (isset($f['rules'])) {
                $rules[$f['name']] = $f['rules'];
            } elseif ($type === 'select') {
                $rules[$f['name']] = [
                    ! empty($f['required']) ? 'required' : 'nullable',
                    Rule::in(array_map('strval', array_keys($f['options'] ?? []))),
                ];
            }
        }
        $validated = $request->validate($rules);

        $data = [];
        foreach ($fields as $f) {
            if (! empty($f['readonly']) || ! empty($f['virtual'])) {
                continue;
            }
            $n = $f['name'];
            switch ($f['type'] ?? 'text') {
                case 'checkbox':
                    $data[$n] = $request->boolean($n);
                    break;
                case 'tags':
                    $data[$n] = collect(preg_split('/[,\n]+/', (string) $request->input($n, '')))
                        ->map(fn ($t) => trim($t))->filter()->values()->all();
                    break;
                case 'permissions':
                    $allowed = array_merge(array_keys(Role::MODULES), ['*']);
                    $data[$n] = array_values(array_intersect((array) $request->input($n, []), $allowed));
                    break;
                case 'image':
                    if ($request->hasFile($n)) {
                        $this->deleteUpload($record?->{$n});
                        $data[$n] = $this->upload($request->file($n), $f['folder'] ?? 'misc');
                    } elseif ($request->boolean($n . '_remove')) {
                        $this->deleteUpload($record?->{$n});
                        $data[$n] = null;
                    }
                    break;
                case 'password':
                    if ($request->filled($n)) {
                        $data[$n] = $request->input($n);
                    }
                    break;
                default:
                    $data[$n] = array_key_exists($n, $validated) ? $validated[$n] : (isset($rules[$n]) ? null : $request->input($n));
            }
        }

        $data = $this->prepare($data, $record, $request);

        if ($record) {
            $record->update($data);
        } else {
            $record = ($this->model())::create($data);
        }
        $this->saved($record, $request, $record->wasRecentlyCreated);

        return $record;
    }

    protected function uniqueSlug(string $text, ?Model $ignore = null, string $column = 'slug'): string
    {
        $base = Str::slug($text) ?: 'item';
        $slug = $base;
        $i = 2;
        $model = $this->model();
        while ($model::where($column, $slug)->when($ignore, fn ($q) => $q->where('id', '!=', $ignore->id))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    protected function upload(UploadedFile $file, string $folder): string
    {
        $dir = public_path('uploads/' . $folder);
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        $name = Str::random(20) . '.' . strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $file->move($dir, $name);

        return 'uploads/' . $folder . '/' . $name;
    }

    protected function deleteUpload(?string $path): void
    {
        if ($path && str_starts_with($path, 'uploads/') && is_file(public_path($path))) {
            @unlink(public_path($path));
        }
    }

    /** Used by the index view to resolve row action definitions. */
    public function actionsFor(Model $record): array
    {
        return $this->rowActions($record);
    }
}
