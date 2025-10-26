<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->input('search'), function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('id', 'like', "%{$search}%")
                             ->orWhere('name', 'like', "%{$search}%")
                             ->orWhere('email', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%")
                             ->orWhere('line_id', 'like', "%{$search}%");
                });
            })
            ->get();
            
        return Inertia::render('Admin/User/Index', [
            'users' => $users,
            'filters' => $request->only('search'),
        ]);
    }
}