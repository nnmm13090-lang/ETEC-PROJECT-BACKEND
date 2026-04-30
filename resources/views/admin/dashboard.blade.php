@extends('layouts.app')

@section('title', 'Dashboard — ' . config('app.name', 'The Desk'))

@section('content')

{{-- ── DASHBOARD HEADER ── --}}
<section class="bg-ink text-ivory px-6 lg:px-10 py-10 border-b border-white/10">
    <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-4">
        <div>
            <p class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent mb-1">Admin Panel</p>
            <h1 class="font-display text-3xl font-bold tracking-tight">
                Welcome back, {{ auth()->user()->display_name ?? auth()->user()->name }} 👋
            </h1>
        </div>

        {{-- FIXED ROUTE --}}
        <a href="{{ route('admin.posts.create') }}"
           class="bg-accent text-ivory text-xs font-bold uppercase tracking-widest px-6 py-3 rounded-sm hover:bg-orange-800 transition-colors">
            + New Post
        </a>
    </div>
</section>

{{-- ── STATS ── --}}
<section class="bg-cream border-b border-rule px-6 lg:px-10 py-8">
    <div class="max-w-7xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-5">
        @php
            $stats = [
                ['label' => 'Total Posts',  'value' => $stats['posts'] ?? 0, 'color' => 'text-accent'],
                ['label' => 'Published',    'value' => $stats['published'] ?? 0, 'color' => 'text-green-600'],
                ['label' => 'Total Views',  'value' => number_format($stats['views'] ?? 0), 'color' => 'text-accent2'],
                ['label' => 'Subscribers',  'value' => $stats['subscribers'] ?? 0, 'color' => 'text-gold'],
            ];
        @endphp

        @foreach($stats as $stat)
        <div class="bg-white border border-rule p-6">
            <p class="font-display text-3xl font-bold {{ $stat['color'] }} mb-1">
                {{ $stat['value'] }}
            </p>
            <p class="text-[0.65rem] font-bold uppercase tracking-[0.18em] text-muted">
                {{ $stat['label'] }}
            </p>
        </div>
        @endforeach
    </div>
</section>

{{-- ── MAIN CONTENT ── --}}
<section class="bg-ivory px-6 lg:px-10 py-10">
    <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-8">

        {{-- POSTS TABLE --}}
        <div>

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <span class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent">Recent Posts</span>
                    <span class="w-8 h-px bg-accent"></span>
                </div>

                <a href="{{ route('admin.posts.index') }}"
                   class="text-[0.68rem] font-bold uppercase tracking-wide text-accent2 border-b border-accent2 pb-0.5 hover:text-accent hover:border-accent transition-colors">
                    All Posts
                </a>
            </div>

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
                        @forelse($recentPosts ?? [] as $post)
                        <tr class="border-b last:border-0 hover:bg-cream/50">

                            <td class="px-5 py-4">
                                <p class="font-semibold text-sm">{{ $post->title }}</p>
                                <p class="text-xs text-muted">
                                    {{ $post->published_at?->format('M j, Y') ?? 'Draft' }}
                                </p>
                            </td>

                            <td class="px-4 py-4 hidden md:table-cell">
                                <span class="text-xs text-accent2">
                                    {{ $post->categories->first()?->name ?? '—' }}
                                </span>
                            </td>

                            <td class="px-4 py-4 hidden lg:table-cell text-xs text-muted">
                                {{ number_format($post->views_count ?? 0) }}
                            </td>

                            <td class="px-4 py-4">
                                @if($post->status === 'published')
                                    <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded">Published</span>
                                @elseif($post->status === 'draft')
                                    <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded">Draft</span>
                                @else
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded">Scheduled</span>
                                @endif
                            </td>

                            <td class="px-5 py-4 text-right space-x-2">

                                <a href="{{ route('post', $post->slug) }}"
                                   class="text-xs text-muted hover:text-ink">View</a>

                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                   class="text-xs text-accent2 hover:text-ink">Edit</a>

                                <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
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

                                {{-- FIXED ROUTE HERE --}}
                                <a href="{{ route('admin.posts.create') }}"
                                   class="text-accent2 hover:underline ml-1">
                                    Create your first post →
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="space-y-6">

            {{-- QUICK ACTIONS --}}
            <div class="bg-white border border-rule p-6">
                <div class="text-xs font-bold uppercase text-accent mb-4">Quick Actions</div>

                <div class="space-y-2">

                    <a href="{{ route('admin.posts.create') }}"
                       class="block bg-ink text-white px-4 py-3 text-xs font-bold uppercase hover:bg-accent">
                        Write New Post →
                    </a>

                    <a href="{{ route('admin.categories.create') }}"
                       class="block border px-4 py-3 text-xs font-bold uppercase hover:bg-ink hover:text-white">
                        Add Category →
                    </a>

                    <a href="{{ route('admin.media') }}"
                       class="block border px-4 py-3 text-xs font-bold uppercase hover:bg-ink hover:text-white">
                        Media Library →
                    </a>

                    <a href="{{ route('admin.comments') }}"
                       class="block border px-4 py-3 text-xs font-bold uppercase hover:bg-ink hover:text-white">
                        Comments
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection