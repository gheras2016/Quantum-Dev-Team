<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        return view('services', [
            'services' => Service::active()->ordered()->get(),
        ]);
    }
}
