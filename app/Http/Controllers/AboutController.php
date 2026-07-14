<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\TeamMember;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        return view('about', [
            'services' => Service::active()->ordered()->get(),
            'teamMembers' => TeamMember::active()->ordered()->get(),
        ]);
    }
}
