<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('home', [
            'services' => Service::active()->ordered()->take(6)->get(),
            'featuredProjects' => Project::published()->featured()->ordered()->take(6)->get(),
            'teamMembers' => TeamMember::active()->ordered()->take(4)->get(),
            'testimonials' => Testimonial::active()->ordered()->take(6)->get(),
            'faqs' => Faq::active()->ordered()->take(6)->get(),
            'stats' => [
                'projects' => (int) setting('stat_projects', max(Project::published()->count(), 50)),
                'clients' => (int) setting('stat_clients', 30),
                'years' => (int) setting('stat_years', 5),
            ],
        ]);
    }
}
