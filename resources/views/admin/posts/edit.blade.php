@extends('layouts.app')
@section('title', 'Edit Post — Admin')

@section('content')

<section class="bg-ink text-ivory px-6 lg:px-10 py-10 border-b border-white/10">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <div>
            <p class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent mb-1">Admin Panel</p>
            <h1 class="font-display text-3xl font-bold tracking-tight">Edit Post</h1>
        </div>
        {{-- DELETE FORM — completely separate from the edit form --}}
        <form action="{{ route('admin.posts.destroy', $post) }}" method="POST"
              onsubmit="return confirm('Permanently delete this post?')">
            @csrf @method('DELETE')
            <button type="submit"
                    class="px-5 py-3 text-xs font-bold uppercase tracking-widest text-red-400 border border-red-400/30 hover:bg-red-600 hover:text-white hover:border-red-600 transition">
                Delete Post
            </button>
        </form>
    </div>
</section>

<section class="bg-ivory px-6 lg:px-10 py-10">
    <div class="max-w-3xl mx-auto bg-white border border-rule p-8 shadow-sm">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm rounded">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm rounded">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        {{-- EDIT FORM — no delete button inside --}}
        <form action="{{ route('admin.posts.update', $post) }}" method="POST"
              enctype="multipart/form-data" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Post Title *</label>
                <input type="text" name="title" value="{{ old('title', $post->title) }}" required
                       class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent">
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Excerpt</label>
                <textarea name="excerpt" rows="2"
                          class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent">{{ old('excerpt', $post->excerpt) }}</textarea>
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Content *</label>
                <textarea name="content" rows="12"
                          class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent font-mono">{{ old('content', $post->content) }}</textarea>
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Category</label>
                <select name="category_id"
                        class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent">
                    <option value="">— No category —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $post->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Cover Image</label>
                @if($post->cover_image)
                    <div class="mt-2 mb-3">
                        <img src="{{ asset('storage/'.$post->cover_image) }}"
                             class="h-32 object-cover border border-rule" alt="Current cover">
                        <p class="text-xs text-muted mt-1">Upload a new image to replace.</p>
                    </div>
                @endif
                <input type="file" name="cover_image" accept="image/*"
                       class="w-full mt-2 border border-rule px-4 py-3 text-sm">
            </div>

            <div>
                <label class="text-xs font-bold uppercase tracking-widest text-muted">Status</label>
                <select name="status"
                        class="w-full mt-2 border border-rule px-4 py-3 text-sm focus:outline-none focus:border-accent">
                    <option value="draft"     {{ old('status', $post->status) == 'draft'     ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.posts.index') }}"
                   class="px-5 py-3 text-xs font-bold uppercase tracking-widest border border-rule hover:bg-ink hover:text-ivory transition">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-accent text-ivory text-xs font-bold uppercase tracking-widest hover:bg-orange-800 transition">
                    Update Post
                </button>
            </div>

        </form>
    </div>
</section>

@endsection