<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Contact;
use App\Models\Project;
use App\Models\ProjectRequest;
use App\Models\Post;
use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'projects' => Project::count(),
            'published_projects' => Project::published()->count(),
            'featured_projects' => Project::featured()->count(),
            'services' => Service::count(),
            'team' => TeamMember::count(),
            'unread_contacts' => Contact::unread()->count(),
            'pending_requests' => ProjectRequest::pending()->count(),
        ];

        $recentActivity = ActivityLog::latest()->take(8)->get();
        $recentContacts = Contact::latest()->take(5)->get();
        $contentChart = $this->contentChart();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'recentContacts', 'contentChart'));
    }

    /**
     * New content (projects + articles) created per month over the last 6 months.
     * A single-series magnitude-over-time bar chart.
     *
     * @return array<int, array{label: string, count: int, pct: int}>
     */
    private function contentChart(): array
    {
        $months = collect(range(5, 0))->map(fn (int $i) => Carbon::now()->startOfMonth()->subMonths($i));

        $data = $months->map(function (Carbon $month) {
            $end = (clone $month)->endOfMonth();
            $count = Project::whereBetween('created_at', [$month, $end])->count()
                + Post::whereBetween('created_at', [$month, $end])->count();

            return ['label' => $month->translatedFormat('M'), 'count' => $count];
        });

        $max = max($data->max('count'), 1);

        return $data->map(fn (array $row) => [
            ...$row,
            // Cap at 88% so the value label above each bar always has headroom.
            'pct' => $row['count'] > 0 ? max(4, (int) round($row['count'] / $max * 88)) : 0,
        ])->all();
    }
}
