<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Concert;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Concert $concert)
    {
        $request->validate(['content' => 'required|string|max:1000']);
        $concert->comments()->create(['user_id' => $request->user()->id, 'content' => $request->content]);
        return back();
    }

    public function update(Request $request, Comment $comment)
    {
        abort_if($comment->user_id !== $request->user()->id, 403);
        $request->validate(['content' => 'required|string|max:1000']);
        $comment->update(['content' => $request->content]);
        return back();
    }

    public function destroy(Request $request, Comment $comment)
    {
        abort_if($comment->user_id !== $request->user()->id, 403);
        $comment->delete();
        return back();
    }
}
