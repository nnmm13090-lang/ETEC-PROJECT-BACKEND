@extends('layouts.app')
@section('title', 'All Posts — Admin')

@section('content')

<section class="bg-ink text-ivory px-6 lg:px-10 py-10 border-b border-white/10">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <div>
            <p class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent mb-1">Admin Panel</p>
            <h1 class="font-display text-3xl font-bold tracking-tight">All Posts</h1>
        </div>
        <a href="{{ route('admin.posts.create') }}"
           class="bg-accent text-ivory text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-sm hover:bg-orange-800 transition-colors">
            + New Post
        </a>
    </div>
</section>

<section class="bg-ivory px-6 lg:px-10 py-10">
    <div class="max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-rule overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-cream border-b border-rule">
                        <th class="text-left px-5 py-3 text-[0.6rem] uppercase text-muted">Title</th>
                        <th class="hidden md:table-cell text-left px-4 py-3 text-[0.6rem] uppercase text-muted">Category</th>
                        <th class="hidden lg:table-cell text-left px-4 py-3 text-[0.6rem] uppercase text-muted">Views</th>
                        <th class="text-left px-4 py-3 text-[0.6rem] uppercase text-muted">Status</th>
                        <th class="text-right px-5 py-3 text-[0.6rem] uppercase text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr class="border-b last:border-0 hover:bg-cream/50">
                        <td class="px-5 py-4">
                            <p class="font-semibold text-sm">{{ $post->title }}</p>
                            <p class="text-xs text-muted">
                                {{ $post->published_at?->format('M j, Y') ?? 'Draft' }}
                            </p>
                        </td>
                        <td class="px-4 py-4 hidden md:table-cell">
                            <span class="text-xs text-accent2">{{ $post->category?->name ?? '—' }}</span>
                        </td>
                        <td class="px-4 py-4 hidden lg:table-cell text-xs text-muted">
                            {{ number_format($post->views ?? 0) }}
                        </td>
                        <td class="px-4 py-4">
                            @if($post->status === 'published')
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Published</span>
                            @else
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Draft</span>
                            @endif
                        </td>
                        <td class="px-5 py-4 text-right space-x-2">
                            @if($post->status === 'published')
                                <a href="{{ route('post', $post->slug) }}" target="_blank"
                                   class="text-xs text-muted hover:text-ink">View</a>
                            @endif
                            <a href="{{ route('admin.posts.edit', $post) }}"
                               class="text-xs text-accent2 hover:text-ink">Edit</a>
                            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-500 hover:text-red-700"
                                        onclick="return confirm('Delete this post?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-10 text-muted">
                            No posts yet.
                            <a href="{{ route('admin.posts.create') }}" class="text-accent2 hover:underline ml-1">
                                Create your first post →
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
            <div class="mt-6">{{ $posts->links() }}</div>
        @endif

    </div>
</section>

@endsection