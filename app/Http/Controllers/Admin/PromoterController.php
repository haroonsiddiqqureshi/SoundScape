<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Promoter;
use App\Models\Concert;
use App\Models\Province;

class PromoterController extends Controller
{
    public function index(Request $request)
    {
        $query = Promoter::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            
            $query->where(function ($q) use ($search) {
                $q->where('fullname', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($search) {
                      $userQuery->where('email', 'like', '%' . $search . '%');
                  });
            });
        }

        $promoters = $query->get();

        return Inertia::render('Admin/Promoter/Index', [
            'promoters' => $promoters,
            'filters' => $request->only(['search']),
        ]);
    }

    public function detail(Promoter $promoter)
    {
        $promoter->load(['user:id,name,email,phone']);
        
        $concerts = Concert::where('promoter_id', $promoter->id)
            ->latest()
            ->paginate(12);
            
        $provinces = Province::all()->keyBy('id');

        return Inertia::render('Admin/Promoter/Detail', [
            'promoter' => $promoter,
            'concerts' => $concerts,
            'provinces' => $provinces,
        ]);
    }

    public function updateVerificationStatus(Request $request, $id)
    {
        $request->validate([
            'is_verified' => 'required|boolean',
        ]);

        $promoter = Promoter::findOrFail($id);
        $promoter->is_verified = $request->input('is_verified');
        $promoter->save();
    }
}