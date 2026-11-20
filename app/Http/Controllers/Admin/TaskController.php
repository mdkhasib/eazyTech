<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Task;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class TaskController extends ResourceController
{
    protected function model(): string
    {
        return Task::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Tasks', 'singular' => 'Task', 'route' => 'admin.tasks', 'icon' => 'check',
            'subtitle' => 'Drag cards between columns to update progress.', 'can_create' => true];
    }

    protected function columns(): array
    {
        return [];
    }

    /** Kanban board instead of a table. */
    public function index(Request $request)
    {
        $q = Task::with(['assignee', 'project.client'])->orderBy('sort_order')->orderBy('id');

        $mine = null;
        if ($request->boolean('mine') && ($m = auth()->user()->member)) {
            $q->where('assigned_to', $m->id);
            $mine = $m;
        }
        if ($request->filled('assignee')) {
            $q->where('assigned_to', $request->integer('assignee'));
        }
        if ($request->filled('project')) {
            $q->where('project_id', $request->integer('project'));
        }
        if ($request->filled('priority')) {
            $q->where('priority', $request->query('priority'));
        }

        $tasks = $q->get()->groupBy('status');

        return view('admin.tasks.board', [
            'meta' => $this->meta(),
            'columns' => Task::STATUSES,
            'tasks' => $tasks,
            'members' => TeamMember::where('status', '!=', 'inactive')->orderBy('name')->get(),
            'projects' => Project::orderBy('title')->get(['id', 'title']),
            'priorities' => Task::PRIORITIES,
            'filters' => $request->only(['assignee', 'project', 'priority', 'mine']),
            'hasMember' => (bool) auth()->user()->member,
        ]);
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'title', 'label' => 'Task title', 'type' => 'text', 'rules' => 'required|string|max:200', 'col' => 12],
            ['name' => 'project_id', 'label' => 'Project', 'type' => 'select', 'col' => 6,
                'options' => Project::orderBy('title')->pluck('title', 'id')->all(), 'rules' => 'nullable|exists:projects,id'],
            ['name' => 'assigned_to', 'label' => 'Assigned to', 'type' => 'select', 'col' => 6,
                'options' => TeamMember::orderBy('name')->pluck('name', 'id')->all(), 'rules' => 'nullable|exists:team_members,id'],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => Task::STATUSES, 'default' => 'todo', 'col' => 4],
            ['name' => 'priority', 'label' => 'Priority', 'type' => 'select', 'required' => true, 'options' => Task::PRIORITIES, 'default' => 'medium', 'col' => 4],
            ['name' => 'due_date', 'label' => 'Due date', 'type' => 'date', 'rules' => 'nullable|date', 'col' => 4],
            ['name' => 'description', 'label' => 'Details', 'type' => 'textarea', 'rules' => 'nullable|string|max:5000', 'col' => 12],
        ];
    }

    protected function prepare(array $data, ?Model $record, Request $request): array
    {
        if (! $record) {
            $data['created_by'] = auth()->id();
        }

        return $data;
    }

    /** AJAX endpoint used by the drag & drop board. */
    public function move(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', array_keys(Task::STATUSES))],
            'order' => ['nullable', 'array'],
            'order.*' => ['integer'],
        ]);

        $task->update(['status' => $data['status']]);
        foreach (($data['order'] ?? []) as $pos => $taskId) {
            Task::whereKey($taskId)->update(['sort_order' => $pos]);
        }

        return response()->json(['ok' => true, 'status' => $task->status]);
    }
}
