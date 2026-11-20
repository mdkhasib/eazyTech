<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ContactMessage;
use App\Models\LeaveRequest;
use App\Models\PortfolioItem;
use App\Models\Project;
use App\Models\Task;
use App\Models\TeamMember;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $member = $user->member;

        // Leads per month (last 6 months)
        $months = collect(range(5, 0))->map(fn ($i) => Carbon::now()->subMonthsNoOverflow($i)->startOfMonth());
        $leads = $months->map(fn (Carbon $m) => [
            'label' => $m->format('M'),
            'value' => ContactMessage::whereBetween('created_at', [$m, $m->copy()->endOfMonth()])->count(),
        ])->values();

        $taskCounts = Task::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');
        $taskStatus = collect(Task::STATUSES)->map(fn ($label, $key) => ['label' => $label, 'value' => (int) ($taskCounts[$key] ?? 0)])->values();

        $teamMix = TeamMember::where('status', '!=', 'inactive')->selectRaw('employment_type, count(*) as c')
            ->groupBy('employment_type')->pluck('c', 'employment_type');

        return view('admin.dashboard', [
            'stats' => [
                ['label' => 'Active clients', 'value' => Client::where('status', 'active')->count(), 'icon' => 'briefcase', 'tone' => 'teal', 'hint' => Client::where('status', 'lead')->count() . ' leads in pipeline'],
                ['label' => 'Running projects', 'value' => Project::whereIn('status', ['in_progress', 'review'])->count(), 'icon' => 'folder', 'tone' => 'orange', 'hint' => Project::where('status', 'completed')->count() . ' completed'],
                ['label' => 'Open tasks', 'value' => Task::where('status', '!=', 'done')->count(), 'icon' => 'check', 'tone' => 'yellow', 'hint' => Task::where('status', '!=', 'done')->whereDate('due_date', '<', today())->count() . ' overdue'],
                ['label' => 'Team members', 'value' => TeamMember::where('status', '!=', 'inactive')->count(), 'icon' => 'users', 'tone' => 'coral', 'hint' => ($teamMix['remote_part_time'] ?? 0) . ' remote part-time'],
                ['label' => 'New leads', 'value' => ContactMessage::where('status', 'new')->count(), 'icon' => 'mail', 'tone' => 'violet', 'hint' => ContactMessage::count() . ' total enquiries'],
                ['label' => 'Portfolio items', 'value' => PortfolioItem::where('is_published', true)->count(), 'icon' => 'layers', 'tone' => 'teal', 'hint' => PortfolioItem::where('is_featured', true)->count() . ' featured'],
            ],
            'leads' => $leads,
            'taskStatus' => $taskStatus,
            'teamMix' => collect(TeamMember::TYPES)->map(fn ($label, $k) => ['label' => $label, 'value' => (int) ($teamMix[$k] ?? 0)])->filter(fn ($r) => $r['value'] > 0)->values(),
            'recentMessages' => ContactMessage::latest()->take(5)->get(),
            'projects' => Project::with('client')->whereIn('status', ['in_progress', 'review', 'planning'])->orderBy('due_date')->take(5)->get(),
            'myTasks' => $member ? Task::with('project')->where('assigned_to', $member->id)->where('status', '!=', 'done')->orderBy('due_date')->take(6)->get() : collect(),
            'upcoming' => Task::with(['assignee', 'project'])->where('status', '!=', 'done')->whereNotNull('due_date')->orderBy('due_date')->take(6)->get(),
            'pendingLeaves' => LeaveRequest::with('member')->where('status', 'pending')->orderBy('start_date')->take(4)->get(),
            'member' => $member,
        ]);
    }
}
