<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Promoter;

class PromoterController extends Controller
{
    public function index()
    {
        $promoters = Promoter::with('user')->get();
        return Inertia::render('Admin/Promoter/Index', [
            'promoters' => $promoters,
        ]);
    }

    public function detail(Promoter $promoter)
    {
        $promoter->load('user');
        return Inertia::render('Admin/Promoter/Detail', [
            'promoter' => $promoter,
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

        return redirect()->route('admin.promoter.index')->with('success', 'Promoter verification status updated successfully.');
    }
}
