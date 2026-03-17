<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Promoter;
use App\Models\Concert;
use App\Models\Highlight;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_promoters' => Promoter::count(),
            'total_concerts' => Concert::count(),
            'active_highlights' => Highlight::where('is_active', true)->count(), 
        ];

        $recentConcerts = Concert::with('promoter')
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentConcerts' => $recentConcerts,
        ]);
    }
}