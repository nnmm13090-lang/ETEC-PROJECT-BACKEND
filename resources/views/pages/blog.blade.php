@extends('layouts.app')

@section('title', 'Blog - ' . config('app.name', 'The Desk'))

@section('content')

<section class="bg-[#fbf7f0] px-6 py-8 lg:px-10">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-[0.62rem] font-bold uppercase tracking-[0.24em] text-muted">Category</span>

            <a href="{{ route('blog') }}"
               class="rounded-full border px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] transition-colors
               {{ !request('category') ? 'border-accent bg-accent text-white' : 'border-ink/10 bg-white text-charcoal hover:border-accent hover:text-accent' }}">
                All
            </a>

            @foreach(collect($categories ?? []) as $cat)
                <a href="{{ route('blog', ['category' => $cat->slug]) }}"
                   class="rounded-full border px-4 py-2 text-xs font-bold uppercase tracking-[0.2em] transition-colors
                   {{ request('category') === $cat->slug ? 'border-accent bg-accent text-white' : 'border-ink/10 bg-white text-charcoal hover:border-accent hover:text-accent' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="bg-[#fbf7f0] px-6 pb-16 pt-4 lg:px-10">
    <div class="mx-auto max-w-7xl">

        @php
            $thumbs = [
                'from-[#1e3248] to-[#52789b]',
                'from-[#3c231a] to-[#b16c44]',
                'from-[#2f3d28] to-[#68845e]',
                'from-[#2b2d55] to-[#7080cb]',
                'from-[#4a2d1c] to-[#d0975d]',
                'from-[#254049] to-[#61a1ad]',
            ];
            $leadPost = $posts->first();
        @endphp

        {{-- LEAD POST --}}
        @if($leadPost)
            <div class="mb-8 overflow-hidden rounded-[2rem] border border-white/70 bg-white shadow-[0_20px_60px_rgba(89,64,42,0.08)]">
                <div class="grid lg:grid-cols-[1.1fr_0.9fr]">
                    <div class="relative min-h-[360px] bg-gradient-to-br {{ $thumbs[0] }}">
                        @if($leadPost->cover_image)
                            <img src="{{ asset('storage/'.$leadPost->cover_image) }}"
                                 alt="{{ $leadPost->title }}"
                                 class="absolute inset-0 h-full w-full object-cover">
                        @endif
                        <div class="absolute left-6 top-6 rounded-full bg-white/80 px-3 py-1 text-[0.58rem] font-bold uppercase tracking-[0.22em] text-ink">
                            Lead Story
                        </div>
                        @if($leadPost->category)
                            <div class="absolute right-6 top-6 rounded-full bg-accent px-3 py-1 text-[0.58rem] font-bold uppercase tracking-[0.22em] text-white">
                                {{ $leadPost->category->name }}
                            </div>
                        @endif
                    </div>
                    <div class="flex flex-col justify-center p-8 lg:p-10">
                        <div class="flex items-center gap-2 mb-2">
                            @if($leadPost->category)
                                <span class="text-[0.6rem] font-bold uppercase tracking-wide text-accent2 border border-accent2/30 px-2 py-0.5 rounded-full">
                                    {{ $leadPost->category->name }}
                                </span>
                            @endif
                            <p class="text-[0.62rem] font-bold uppercase tracking-[0.24em] text-muted">
                                {{ optional($leadPost->published_at)->format('F j, Y') }}
                            </p>
                        </div>
                        <h2 class="mt-2 font-display text-4xl font-bold leading-tight text-ink">
                            {{ $leadPost->title }}
                        </h2>
                        <p class="mt-4 text-base leading-8 text-muted">
                            {{ $leadPost->excerpt }}
                        </p>
                        <div class="mt-6 flex items-center justify-between border-t border-rule pt-5 text-xs uppercase tracking-[0.18em] text-muted">
                            <span>{{ $leadPost->author->display_name ?? $leadPost->author->name ?? 'Author' }}</span>
                            <span>{{ $leadPost->read_time ?? 5 }} min read</span>
                        </div>
                        <a href="{{ route('post', $leadPost->slug) }}"
                           class="mt-6 inline-flex rounded-full bg-ink px-6 py-3 text-xs font-bold uppercase tracking-[0.22em] text-ivory transition-colors hover:bg-accent">
                            Read Essay
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- POSTS GRID --}}
        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
            @forelse($posts as $i => $post)
                @continue($loop->first)
                <a href="{{ route('post', $post->slug) }}"
                   class="group overflow-hidden rounded-[1.75rem] border border-white/60 bg-white shadow-[0_16px_45px_rgba(89,64,42,0.08)] transition-transform hover:-translate-y-1">
                    <div class="relative h-52 bg-gradient-to-br {{ $thumbs[$i % 6] }}">
                        @if($post->cover_image)
                            <img src="{{ asset('storage/'.$post->cover_image) }}"
                                 alt="{{ $post->title }}"
                                 class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @endif
                        @if($post->category)
                            <span class="absolute top-4 left-4 rounded-full bg-accent px-3 py-1 text-[0.58rem] font-bold uppercase tracking-[0.2em] text-white">
                                {{ $post->category->name }}
                            </span>
                        @endif
                    </div>
                    <div class="p-6">
                        <p class="text-[0.62rem] font-bold uppercase tracking-[0.24em] text-muted">
                            {{ optional($post->published_at)->format('F j, Y') }}
                        </p>
                        <h2 class="mt-3 font-display text-2xl font-bold leading-tight text-ink transition-colors group-hover:text-accent">
                            {{ $post->title }}
                        </h2>
                        <p class="mt-3 text-sm leading-7 text-muted">{{ $post->excerpt }}</p>
                        <div class="mt-5 flex items-center justify-between border-t border-rule pt-4 text-xs uppercase tracking-[0.18em] text-muted">
                            <span>{{ $post->author->display_name ?? $post->author->name ?? 'Author' }}</span>
                            <span>{{ $post->read_time ?? 5 }} min read</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full rounded-[2rem] border border-rule bg-white px-8 py-20 text-center shadow-sm">
                    <p class="font-display text-3xl font-bold text-ink">No posts found.</p>
                    <a href="{{ route('blog') }}" class="mt-4 inline-block text-sm font-semibold text-accent2 hover:text-accent">
                        Clear filters
                    </a>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        @if(isset($posts) && $posts->hasPages())
            <div class="mt-12 flex flex-wrap justify-center gap-2">
                @if($posts->onFirstPage())
                    <span class="rounded-full border border-rule px-4 py-2 text-xs text-muted">Prev</span>
                @else
                    <a href="{{ $posts->previousPageUrl() }}"
                       class="rounded-full border border-rule bg-white px-4 py-2 text-xs font-semibold text-charcoal hover:border-accent hover:text-accent">
                       Prev
                    </a>
                @endif

                @foreach($posts->getUrlRange(1, $posts->lastPage()) as $page => $url)
                    <a href="{{ $url }}"
                       class="rounded-full border px-4 py-2 text-xs font-semibold transition-colors
                       {{ $page == $posts->currentPage() ? 'border-accent bg-accent text-white' : 'border-rule bg-white text-charcoal hover:border-accent hover:text-accent' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($posts->hasMorePages())
                    <a href="{{ $posts->nextPageUrl() }}"
                       class="rounded-full border border-rule bg-white px-4 py-2 text-xs font-semibold text-charcoal hover:border-accent hover:text-accent">
                       Next
                    </a>
                @else
                    <span class="rounded-full border border-rule px-4 py-2 text-xs text-muted">Next</span>
                @endif
            </div>
        @endif

    </div>
</section>

@endsection