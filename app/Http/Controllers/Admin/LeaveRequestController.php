<?php

namespace App\Http\Controllers\Admin;

use App\Models\LeaveRequest;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Model;

class LeaveRequestController extends ResourceController
{
    protected const STATUS = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'];
    protected const TYPES = ['annual' => 'Annual', 'sick' => 'Sick', 'casual' => 'Casual', 'unpaid' => 'Unpaid'];

    protected function model(): string
    {
        return LeaveRequest::class;
    }

    protected function meta(): array
    {
        return ['title' => 'Leave requests', 'singular' => 'Leave request', 'route' => 'admin.leaves', 'icon' => 'calendar',
            'subtitle' => 'Time-off requests from the team.', 'can_create' => true];
    }

    protected function with(): array
    {
        return ['member'];
    }

    protected function searchable(): array
    {
        return ['member.name', 'reason'];
    }

    protected function filters(): array
    {
        return [['name' => 'status', 'label' => 'All statuses', 'options' => self::STATUS]];
    }

    protected function columns(): array
    {
        return [
            ['key' => 'member.name', 'label' => 'Member', 'type' => 'text'],
            ['key' => 'type', 'label' => 'Type', 'type' => 'badge', 'map' => self::TYPES],
            ['key' => 'period', 'label' => 'Period', 'type' => 'render', 'render' => fn (LeaveRequest $l) => e($l->start_date->format('d M') . ' → ' . $l->end_date->format('d M Y')) . ' <small class="muted">· ' . $l->days . 'd</small>'],
            ['key' => 'reason', 'label' => 'Reason', 'type' => 'text'],
            ['key' => 'status', 'label' => 'Status', 'type' => 'badge', 'map' => self::STATUS],
        ];
    }

    protected function rowActions(Model $record): array
    {
        if ($record->status !== 'pending') {
            return [];
        }
        $url = route('admin.leaves.status', $record->id);

        return [
            ['label' => 'Approve', 'tone' => 'ok', 'url' => $url, 'data' => ['status' => 'approved']],
            ['label' => 'Reject', 'tone' => 'danger', 'url' => $url, 'data' => ['status' => 'rejected']],
        ];
    }

    protected function fields(?Model $record = null): array
    {
        return [
            ['name' => 'team_member_id', 'label' => 'Member', 'type' => 'select', 'required' => true, 'col' => 6,
                'options' => TeamMember::orderBy('name')->pluck('name', 'id')->all(), 'rules' => 'required|exists:team_members,id'],
            ['name' => 'type', 'label' => 'Leave type', 'type' => 'select', 'required' => true, 'options' => self::TYPES, 'col' => 3],
            ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'required' => true, 'options' => self::STATUS, 'default' => 'pending', 'col' => 3],
            ['name' => 'start_date', 'label' => 'From', 'type' => 'date', 'rules' => 'required|date', 'col' => 3],
            ['name' => 'end_date', 'label' => 'To', 'type' => 'date', 'rules' => 'required|date|after_or_equal:start_date', 'col' => 3],
            ['name' => 'reason', 'label' => 'Reason', 'type' => 'text', 'rules' => 'nullable|string|max:255', 'col' => 6],
        ];
    }
}
