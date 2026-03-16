<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Concert $concert)
    {
        $request->validate(['content' => 'required|string|max:1000']);
        $concert->comments()->create(['admin_id' => Auth::guard('admin')->id(), 'content' => $request->content]);
        return back();
    }

    public function update(Request $request, Comment $comment)
    {
        abort_if($comment->admin_id !== Auth::guard('admin')->id(), 403);
        $request->validate(['content' => 'required|string|max:1000']);
        $comment->update(['content' => $request->content]);
        return back();
    }

    public function destroy(Comment $comment)
    {
        abort_if($comment->admin_id !== Auth::guard('admin')->id(), 403);
        $comment->delete();
        return back();
    }
}
