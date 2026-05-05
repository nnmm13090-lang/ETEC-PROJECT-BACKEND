<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|min:3|max:1000',
        ]);

        Comment::create([
            'post_id'   => $post->id,
            'user_id'   => Auth::id(),
            'content'   => $request->content,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Comment posted.');
    }
}