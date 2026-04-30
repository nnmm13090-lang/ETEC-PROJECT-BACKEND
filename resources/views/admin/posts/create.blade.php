@extends('layouts.app')

@section('title', 'Add Post — Admin')

@section('content')

<section class="bg-ink text-ivory px-6 lg:px-10 py-10 border-b border-white/10">
    <div class="max-w-7xl mx-auto">
        <p class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent mb-1">
            Admin Panel
        </p>
        <h1 class="font-display text-3xl font-bold tracking-tight">
            Create New Post
        </h1>
    </div>
</section>

<section class="bg-ivory px-6 lg:px-10 py-10">
    <div class="max-w-3xl mx-auto bg-white border border-rule p-8 shadow-sm">

        <form action="#" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- TITLE --}}
            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Post Title</label>
                <input type="text" name="title"
                       class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent"
                       placeholder="Enter post title">
            </div>

            {{-- CONTENT --}}
            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Content</label>
                <textarea name="content" rows="6"
                          class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent"
                          placeholder="Write your post..."></textarea>
            </div>

            {{-- CATEGORY --}}
            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Category</label>
                <select name="category_id"
                        class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent">
                    <option value="">Select category</option>
                    <option value="1">Technology</option>
                    <option value="2">Fashion</option>
                </select>
            </div>

            {{-- IMAGE --}}
            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Featured Image</label>
                <input type="file" name="image"
                       class="w-full mt-2 border border-rule px-4 py-3 text-sm">
            </div>

            {{-- STATUS --}}
            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Status</label>
                <select name="status"
                        class="w-full mt-2 border border-rule px-4 py-3 text-sm">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>

            {{-- BUTTONS --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.posts.index') }}"
                   class="px-5 py-3 text-xs font-bold uppercase tracking-widest border border-rule hover:bg-ink hover:text-ivory transition">
                    Cancel
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-accent text-ivory text-xs font-bold uppercase tracking-widest hover:bg-orange-800 transition">
                    Save Post
                </button>
            </div>

        </form>

    </div>
</section>

@endsection