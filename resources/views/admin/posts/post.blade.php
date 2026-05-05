@extends('layouts.app')

@section('title', $post->title . ' — ' . config('app.name', 'The Desk'))

@section('content')

{{-- ── POST HEADER ── --}}
<section class="bg-ink text-ivory px-6 lg:px-10 pt-16 pb-0 relative overflow-hidden">
    <div class="absolute inset-0 pointer-events-none"
         style="background:repeating-linear-gradient(0deg,transparent,transparent 39px,rgba(255,255,255,.025) 39px,rgba(255,255,255,.025) 40px)"></div>

    <div class="max-w-3xl mx-auto relative pb-12">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-[0.65rem] text-muted uppercase tracking-wide mb-8">
            <a href="{{ route('home') }}" class="hover:text-gold transition-colors">Home</a>
            <span>/</span>
            <a href="{{ route('blog') }}" class="hover:text-gold transition-colors">Writing</a>
            @if($post->category)
                <span>/</span>
                <a href="{{ route('blog', ['category' => $post->category->slug]) }}"
                   class="hover:text-gold transition-colors text-accent2">
                    {{ $post->category->name }}
                </a>
            @endif
        </div>

        {{-- Meta --}}
        <div class="flex flex-wrap items-center gap-3 text-[0.68rem] text-muted uppercase tracking-wide mb-5">
            @if($post->category)
                <span class="text-white font-bold bg-accent px-3 py-1 rounded-full text-[0.6rem]">
                    {{ $post->category->name }}
                </span>
                <span>·</span>
            @endif
            <span>{{ $post->published_at->format('F j, Y') }}</span>
            <span>·</span>
            <span>{{ $post->read_time ?? 5 }} min read</span>
            <span>·</span>
            <span>{{ number_format($post->views_count ?? 0) }} views</span>
        </div>

        <h1 class="font-display text-4xl lg:text-5xl font-bold leading-tight tracking-tight mb-6">
            {{ $post->title }}
        </h1>

        @if($post->excerpt)
            <p class="text-lg text-[#b8b0a4] leading-relaxed max-w-2xl mb-8">{{ $post->excerpt }}</p>
        @endif

        {{-- Author row --}}
        <div class="flex items-center gap-4 pt-8 border-t border-white/10">
            <div class="w-10 h-10 rounded-full bg-charcoal text-ivory flex items-center justify-center text-xs font-bold overflow-hidden flex-shrink-0">
                @if($post->author->avatar)
                    <img src="{{ asset('storage/'.$post->author->avatar) }}" alt="" class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr($post->author->display_name ?? $post->author->name, 0, 2)) }}
                @endif
            </div>
            <div>
                <p class="text-sm font-semibold text-ivory">{{ $post->author->display_name ?? $post->author->name }}</p>
                <p class="text-[0.68rem] text-muted">{{ $post->author->bio ? Str::limit($post->author->bio, 60) : 'Author' }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ── COVER IMAGE ── --}}
@if($post->cover_image)
<div class="bg-ink">
    <div class="max-w-4xl mx-auto">
        <img src="{{ asset('storage/'.$post->cover_image) }}"
             alt="{{ $post->title }}"
             class="w-full max-h-[520px] object-cover">
    </div>
</div>
@endif

{{-- ── ARTICLE BODY ── --}}
<section class="bg-ivory px-6 lg:px-10 py-16">
    <div class="max-w-3xl mx-auto">

        {{-- Content --}}
        <div class="prose prose-lg max-w-none
                    prose-headings:font-display prose-headings:font-bold prose-headings:tracking-tight prose-headings:text-ink
                    prose-h2:text-3xl prose-h3:text-2xl
                    prose-p:text-charcoal prose-p:leading-[1.9] prose-p:text-[1.05rem]
                    prose-a:text-accent2 prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-ink prose-strong:font-semibold
                    prose-blockquote:border-l-4 prose-blockquote:border-accent prose-blockquote:bg-cream prose-blockquote:py-4 prose-blockquote:px-6 prose-blockquote:not-italic
                    prose-blockquote:text-charcoal
                    prose-code:bg-warm prose-code:px-1.5 prose-code:py-0.5 prose-code:rounded prose-code:text-sm
                    prose-pre:bg-ink prose-pre:text-ivory">
            {!! $post->content !!}
        </div>

        {{-- Category & Share --}}
        <div class="mt-14 pt-10 border-t border-rule flex flex-wrap items-center justify-between gap-4">
            <div>
                @if($post->category)
                    <p class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-muted mb-3">Filed under</p>
                    <a href="{{ route('blog', ['category' => $post->category->slug]) }}"
                       class="inline-block text-xs font-bold uppercase tracking-widest text-accent2 border border-accent2/30 px-4 py-2 rounded-full hover:bg-accent2 hover:text-white transition-colors">
                        {{ $post->category->name }}
                    </a>
                @endif
            </div>
            <div>
                <p class="text-[0.65rem] font-bold uppercase tracking-[0.2em] text-muted mb-3">Share this article</p>
                <div class="flex gap-2">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}"
                       target="_blank"
                       class="px-4 py-2 bg-ink text-ivory text-[0.65rem] font-bold uppercase tracking-wide rounded-sm hover:bg-accent transition-colors">
                        Twitter
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                       target="_blank"
                       class="px-4 py-2 bg-ink text-ivory text-[0.65rem] font-bold uppercase tracking-wide rounded-sm hover:bg-accent2 transition-colors">
                        LinkedIn
                    </a>
                </div>
            </div>
            <a href="{{ route('blog') }}"
               class="text-[0.68rem] font-bold uppercase tracking-widest text-muted border-b border-muted pb-0.5 hover:text-ink hover:border-ink transition-colors">
                ← Back to all posts
            </a>
        </div>
    </div>
</section>

{{-- ── AUTHOR BIO ── --}}
<section class="bg-cream border-y border-rule px-6 lg:px-10 py-12">
    <div class="max-w-3xl mx-auto flex gap-6 items-start">
        <div class="w-16 h-16 rounded-full bg-ink text-ivory flex items-center justify-center text-sm font-bold overflow-hidden flex-shrink-0">
            @if($post->author->avatar)
                <img src="{{ asset('storage/'.$post->author->avatar) }}" alt="" class="w-full h-full object-cover">
            @else
                {{ strtoupper(substr($post->author->display_name ?? $post->author->name, 0, 2)) }}
            @endif
        </div>
        <div>
            <p class="text-[0.62rem] font-bold uppercase tracking-[0.2em] text-muted mb-1">Written by</p>
            <p class="font-display text-xl font-bold text-ink mb-2">{{ $post->author->display_name ?? $post->author->name }}</p>
            <p class="text-sm text-charcoal leading-relaxed">{{ $post->author->bio ?? 'Author at The Desk.' }}</p>
        </div>
    </div>
</section>

{{-- ── RELATED POSTS ── --}}
@if(isset($relatedPosts) && $relatedPosts->count())
<section class="bg-ivory px-6 lg:px-10 py-16">
    <div class="max-w-7xl mx-auto">
        <div class="flex items-center gap-3 mb-8">
            <span class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent">More to Read</span>
            <span class="w-10 h-px bg-accent"></span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php $thumbs = ['from-[#1a3a5c] to-[#2c5f8a]','from-[#3a1a1a] to-[#b5451b]','from-[#1a3a2c] to-[#2c7a4e]']; @endphp
            @foreach($relatedPosts->take(3) as $i => $related)
            <a href="{{ route('post', $related->slug) }}"
               class="bg-white border border-rule hover:-translate-y-1 hover:shadow-xl transition-all duration-200 group block overflow-hidden rounded-xl">
                <div class="relative h-40 overflow-hidden bg-gradient-to-br {{ $thumbs[$i % 3] }}">
                    @if($related->cover_image)
                        <img src="{{ asset('storage/'.$related->cover_image) }}" alt="{{ $related->title }}"
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @endif
                    @if($related->category)
                        <span class="absolute top-3 left-3 rounded-full bg-accent px-3 py-1 text-[0.58rem] font-bold uppercase tracking-[0.2em] text-white">
                            {{ $related->category->name }}
                        </span>
                    @endif
                </div>
                <div class="p-5">
                    <p class="text-[0.62rem] text-muted uppercase tracking-wide mb-2">{{ $related->published_at->format('M j, Y') }}</p>
                    <h3 class="font-display text-base font-bold text-ink leading-snug group-hover:text-accent transition-colors">
                        {{ $related->title }}
                    </h3>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ── COMMENTS ── --}}
<section class="bg-cream border-t border-rule px-6 lg:px-10 py-16">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center gap-3 mb-10">
            <span class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent">Comments</span>
            <span class="w-10 h-px bg-accent"></span>
        </div>

        @auth
        <form action="{{ route('comment.store', $post->id) }}" method="POST" class="mb-12">
            @csrf
            <textarea name="content" rows="4" required placeholder="Share your thoughts..."
                      class="w-full border border-rule bg-white px-5 py-4 text-sm text-ink placeholder-muted outline-none focus:border-accent2 transition-colors resize-none font-body mb-3"></textarea>
            <button type="submit"
                    class="bg-ink text-ivory text-[0.7rem] font-bold uppercase tracking-widest px-6 py-3 hover:bg-accent transition-colors">
                Post Comment
            </button>
        </form>
        @else
        <p class="text-sm text-muted mb-10">
            <a href="{{ route('login') }}" class="text-accent2 hover:underline font-semibold">Sign in</a> to leave a comment.
        </p>
        @endauth

        @forelse($post->comments->whereNull('parent_id') as $comment)
        <div class="mb-8">
            <div class="flex items-start gap-4">
                <div class="w-9 h-9 rounded-full bg-ink text-ivory flex items-center justify-center text-[0.58rem] font-bold flex-shrink-0">
                    {{ strtoupper(substr($comment->user->display_name ?? $comment->user->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-sm font-semibold text-ink">{{ $comment->user->display_name ?? $comment->user->name }}</span>
                        <span class="text-[0.62rem] text-muted">{{ $comment->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-sm text-charcoal leading-relaxed">{{ $comment->content }}</p>
                </div>
            </div>
        </div>
        @empty
        <p class="text-sm text-muted">No comments yet. Be the first!</p>
        @endforelse

    </div>
</section>

@endsection