@extends('layouts.app')
@section('title', 'New Post — Admin')

@section('content')

<section class="bg-ink text-ivory px-6 lg:px-10 py-10 border-b border-white/10">
    <div class="max-w-7xl mx-auto">
        <p class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent mb-1">Admin Panel</p>
        <h1 class="font-display text-3xl font-bold tracking-tight">Create New Post</h1>
    </div>
</section>

<section class="bg-ivory px-6 lg:px-10 py-10">
    <div class="max-w-3xl mx-auto bg-white border border-rule p-8 shadow-sm">

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm rounded">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Post Title *</label>
                <input type="text" name="title" value="{{ old('title') }}" required
                       class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent"
                       placeholder="Enter post title">
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Excerpt</label>
                <textarea name="excerpt" rows="2"
                          class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent"
                          placeholder="Short summary (optional)">{{ old('excerpt') }}</textarea>
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Content *</label>
                <textarea name="content" rows="12"
                          class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent font-mono"
                          placeholder="Write your post...">{{ old('content') }}</textarea>
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Category</label>
                <select name="category_id"
                        class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent">
                    <option value="">— No category —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Cover Image</label>
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full mt-2 border border-rule px-4 py-3 text-sm">
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Status</label>
                <select name="status"
                        class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent">
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2">
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