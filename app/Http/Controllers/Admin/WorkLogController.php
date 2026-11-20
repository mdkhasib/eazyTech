<?php

namespace App\Http\Controllers\Admin;

use App\Models\TeamMember;
use App\Models\WorkLog;
use Illuminate\Database\Eloquent\Model;

class WorkLogController extends ResourceController
{
    protected const STATUS = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];

    protected function model(): string
    {
        return WorkLog::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Work logs', 'singular' => 'Work log', 'route' => 'admin.worklogs', 'icon' => 'clock',
            'subtitle' => 'Hours worked by remote part-time members. Approved hours drive payroll.', 'can_create' => true];
    }

    protected function with(): array
    {
        return ['member'];
    }

    protected function perPage(): int
    {
        return 15;
    }

    protected function searchable(): array
    {
        return ['member.name', 'description'];
    }

    protected function filters(): array
    {
        return [
            ['name' => 'status', 'label' => 'All statuses', 'options' => self::STATUS],
            ['name' => 'team_member_id', 'label' => 'All members', 'options' => $this->partTimers()],
        ];
    }

    protected function order($q)
    {
        return $q->orderByDesc('work_date')->orderByDesc('id');
    }

    protected function partTimers(): array
    {
        return TeamMember::where('employment_type', 'remote_part_time')->orderBy('name')->pluck('name', 'id')->all();
    }

    protected function columns(): array
    {
        return [
            ['key' => 'work_date', 'label' => 'Date', 'type' => 'date'],
            ['key' => 'member.name', 'label' => 'Member', 'type' => 'text'],
            ['key' => 'hours', 'label' => 'Hours', 'type' => 'render', 'render' => fn (WorkLog $w) => '<strong>' . rtrim(rtrim((string) $w->hours, '0'), '.') . 'h</strong>'],
            ['key' => 'description', 'label' => 'Work done', 'type' => 'text'],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge', 'map' => self::STATUS],
        ];
    }

    protected function rowActions(Model $record): array
    {
        if ($record->status === 'approved') {
            return [];
        }

        return [['label' => 'Approve', 'tone' => 'ok', 'url' => route('admin.worklogs.status', $record->id), 'data' => ['status' => 'approved']]];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'team_member_id', 'label' => 'Member', 'type' => 'select', 'required' => true, 'col' => 6,
                'options' => TeamMember::orderBy('name')->pluck('name', 'id')->all(), 'rules' => 'required|exists:team_members,id'],
            ['name' => 'work_date', 'label' => 'Date', 'type' => 'date', 'rules' => 'required|date|before_or_equal:today', 'default' => now()->toDateString(), 'col' => 3],
            ['name' => 'hours', 'label' => 'Hours', 'type' => 'number', 'step' => '0.25', 'rules' => 'required|numeric|min:0.25|max:16', 'col' => 3],
            ['name' => 'description', 'label' => 'What was done', 'type' => 'text', 'rules' => 'nullable|string|max:255', 'col' => 8],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => self::STATUS, 'default' => 'pending', 'col' => 4],
        ];
    }
}
