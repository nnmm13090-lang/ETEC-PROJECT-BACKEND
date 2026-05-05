<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // ── Admin: list all posts ──────────────────────────────────────────
    public function index()
    {
        $posts = Post::with(['author', 'category'])
            ->latest()
            ->paginate(15);

        return view('admin.posts.index', compact('posts'));
    }

    // ── Admin: create form ────────────────────────────────────────────
    public function create()
    {
        $categories = Categories::orderBy('name')->get();
        return view('admin.posts.create', compact('categories'));
    }

    // ── Admin: store new post ─────────────────────────────────────────
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|max:255',
            'content'     => 'required',
            'excerpt'     => 'nullable|max:500',
            'category_id' => 'nullable|integer|exists:categories,id',
            'status'      => 'required|in:draft,published',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        // Convert empty string to null
        $data['category_id'] = $data['category_id'] ?: null;

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        $data['user_id']      = Auth::id();
        $data['published_at'] = $data['status'] === 'published' ? now() : null;

        // Generate unique slug
        $base = Str::slug($data['title']);
        $slug = $base;
        $i = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }
        $data['slug'] = $slug;

        Post::create($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    // ── Admin: edit form ──────────────────────────────────────────────
    public function edit(Post $post)
    {
        $categories = Categories::orderBy('name')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    // ── Admin: update post ────────────────────────────────────────────
    public function update(Request $request, Post $post)
    {
        $data = $request->validate([
            'title'       => 'required|max:255',
            'content'     => 'required',
            'excerpt'     => 'nullable|max:500',
            'category_id' => 'nullable|integer|exists:categories,id',
            'status'      => 'required|in:draft,published',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        // Convert empty string to null
        $data['category_id'] = $data['category_id'] ?: null;

        if ($request->hasFile('cover_image')) {
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')
                ->store('covers', 'public');
        }

        // Only set published_at the first time it's published
        if ($data['status'] === 'published' && ! $post->published_at) {
            $data['published_at'] = now();
        }

        // Reset published_at if changed back to draft
        if ($data['status'] === 'draft') {
            $data['published_at'] = null;
        }

        $post->update($data);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    // ── Admin: delete post ────────────────────────────────────────────
    public function destroy(Post $post)
    {
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted.');
    }

    // ── Public: single post ───────────────────────────────────────────
    public function show(string $slug)
    {
        $post = Post::with(['author', 'category', 'comments.user'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $post->increment('views');

        $relatedPosts = Post::published()
            ->where('id', '!=', $post->id)
            ->where('category_id', $post->category_id)
            ->latest()
            ->take(3)
            ->get();

        return view('pages.post', compact('post', 'relatedPosts'));
    }
    
}