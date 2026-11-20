<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\LeaveRequest;
use App\Models\TeamMember;
use App\Models\WorkLog;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

/** Lightweight HR overview: headcount by type + monthly payroll estimate. */
class HrController extends Controller
{
    public function index(Request $request)
    {
        try {
            $month = Carbon::createFromFormat('Y-m', $request->query('month', now()->format('Y-m')))->startOfMonth();
        } catch (\Throwable) {
            $month = now()->startOfMonth();
        }
        $end = $month->copy()->endOfMonth();

        $members = TeamMember::with('department')->where('status', '!=', 'inactive')->orderBy('employment_type')->orderBy('name')->get();

        $hours = WorkLog::selectRaw('team_member_id, status, sum(hours) as h')
            ->whereBetween('work_date', [$month->toDateString(), $end->toDateString()])
            ->groupBy('team_member_id', 'status')->get()
            ->groupBy('team_member_id');

        $rows = $members->map(function (TeamMember $m) use ($hours) {
            $isHourly = $m->employment_type === 'remote_part_time';
            $h = $hours->get($m->id, collect());
            $approved = (float) ($h->firstWhere('status', 'approved')?->h ?? 0);
            $pending = (float) ($h->firstWhere('status', 'pending')?->h ?? 0);
            $pay = $isHourly ? $approved * (float) $m->hourly_rate : (float) $m->monthly_salary;

            return [
                'member' => $m, 'hourly' => $isHourly, 'approved' => $approved, 'pending' => $pending,
                'pay' => $pay,
                'target' => $isHourly ? $m->weekly_hours * 4.33 : null,
            ];
        });

        return view('admin.hr.index', [
            'month' => $month,
            'prev' => $month->copy()->subMonth()->format('Y-m'),
            'next' => $month->copy()->addMonth()->format('Y-m'),
            'rows' => $rows,
            'summary' => [
                'permanent' => $members->where('employment_type', 'permanent')->count(),
                'part_time' => $members->where('employment_type', 'remote_part_time')->count(),
                'others' => $members->whereNotIn('employment_type', ['permanent', 'remote_part_time'])->count(),
                'payroll' => $rows->sum('pay'),
                'permanent_pay' => $rows->where('hourly', false)->sum('pay'),
                'part_time_pay' => $rows->where('hourly', true)->sum('pay'),
                'hours' => $rows->sum('approved'),
                'pending_hours' => $rows->sum('pending'),
                'pending_leaves' => LeaveRequest::where('status', 'pending')->count(),
                'new_applications' => JobApplication::where('status', 'new')->count(),
            ],
        ]);
    }
}
