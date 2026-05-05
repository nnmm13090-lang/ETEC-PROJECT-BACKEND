<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'The Desk'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Source+Serif+4:ital,opsz,wght@0,8..60,300;0,8..60,400;0,8..60,600;1,8..60,300&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'Georgia', 'serif'],
                        body: ['"Source Serif 4"', 'Georgia', 'serif'],
                    },
                    colors: {
                        ivory: '#f8f5f0',
                        cream: '#f0ebe2',
                        warm: '#e8e0d3',
                        ink: '#1a1714',
                        charcoal: '#3a3530',
                        muted: '#8a8278',
                        rule: '#d5cec4',
                        accent: '#b5451b',
                        accent2: '#2c5f8a',
                        gold: '#c9a84c',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    },
                    animation: {
                        'fade-up':   'fadeUp 0.5s ease both',
                        'fade-up-1': 'fadeUp 0.5s ease 0.1s both',
                        'fade-up-2': 'fadeUp 0.5s ease 0.2s both',
                        'fade-up-3': 'fadeUp 0.5s ease 0.3s both',
                        'fade-up-4': 'fadeUp 0.5s ease 0.4s both',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Source Serif 4', Georgia, serif; }
        .font-display { font-family: 'Playfair Display', Georgia, serif; }
    </style>
</head>

<body class="bg-ivory text-ink antialiased">

    {{-- NAVBAR --}}
    @unless(View::hasSection('hideChrome'))
    <nav class="bg-ink sticky top-0 z-50 border-b-[3px] border-accent">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 flex items-center justify-between h-16">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="font-display text-2xl font-bold text-ivory tracking-tight">
                The<span class="text-accent">.</span>Desk
            </a>

            {{-- Desktop Nav --}}
            <div class="hidden md:flex items-center gap-40">
                <div class="md:flex items-center gap-8">
                    <a href="{{ route('home') }}"
                        class="text-md font-semibold uppercase tracking-widest transition-colors
                        {{ request()->routeIs('home') ? 'text-gold' : 'text-warm hover:text-gold' }}">
                        Home
                    </a>
                    <a href="{{ route('blog') }}"
                        class="text-md font-semibold uppercase tracking-widest transition-colors
                        {{ request()->routeIs('blog') ? 'text-gold' : 'text-warm hover:text-gold' }}">
                        Blog
                    </a>
                    <a href="{{ route('about') }}"
                        class="text-md font-semibold uppercase tracking-widest transition-colors
                        {{ request()->routeIs('about') ? 'text-gold' : 'text-warm hover:text-gold' }}">
                        About
                    </a>
                    <a href="{{ route('contact') }}"
                        class="text-md font-semibold uppercase tracking-widest transition-colors
                        {{ request()->routeIs('contact') ? 'text-gold' : 'text-warm hover:text-gold' }}">
                        Contact
                    </a>
                </div>

                <div class="md:flex items-center gap-8">
                    @auth
                        <span class="text-xs font-semibold uppercase tracking-widest text-warm">
                            {{ auth()->user()->name }}
                        </span>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}"
                               class="text-xs font-semibold uppercase tracking-widest text-gold hover:text-white transition-colors">
                                Dashboard
                            </a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="text-xs font-semibold uppercase tracking-widest bg-accent text-ivory px-5 py-2 rounded-sm hover:bg-orange-800 transition-colors">
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="text-xs font-semibold uppercase tracking-widest bg-accent text-ivory px-5 py-2 rounded-sm hover:bg-orange-800 transition-colors">
                            Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="text-xs font-semibold uppercase tracking-widest bg-accent text-ivory px-5 py-2 rounded-sm hover:bg-orange-800 transition-colors">
                            Register
                        </a>
                    @endauth
                </div>
            </div>

            {{-- Mobile toggle --}}
            <button id="nav-toggle" class="md:hidden text-ivory text-2xl focus:outline-none">☰</button>
        </div>

        {{-- Mobile menu --}}
        <div id="mobile-menu" class="hidden md:hidden bg-ink border-t border-white/10 px-6 py-4 flex flex-col gap-4">
            <a href="{{ route('home') }}"
                class="text-xs font-semibold uppercase tracking-widest transition-colors {{ request()->routeIs('home') ? 'text-gold' : 'text-warm hover:text-gold' }}">
                Home
            </a>
            <a href="{{ route('blog') }}"
                class="text-xs font-semibold uppercase tracking-widest transition-colors {{ request()->routeIs('blog') ? 'text-gold' : 'text-warm hover:text-gold' }}">
                Blog
            </a>
            <a href="{{ route('about') }}"
                class="text-xs font-semibold uppercase tracking-widest transition-colors {{ request()->routeIs('about') ? 'text-gold' : 'text-warm hover:text-gold' }}">
                About
            </a>
            <a href="{{ route('contact') }}"
                class="text-xs font-semibold uppercase tracking-widest transition-colors {{ request()->routeIs('contact') ? 'text-gold' : 'text-warm hover:text-gold' }}">
                Contact
            </a>
            @auth
                <span class="text-xs font-semibold uppercase tracking-widest text-warm">
                    {{ auth()->user()->name }}
                </span>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}"
                       class="text-xs font-semibold uppercase tracking-widest text-gold hover:text-white transition-colors">
                        Dashboard
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="w-fit">
                    @csrf
                    <button type="submit"
                        class="text-xs font-semibold uppercase tracking-widest bg-accent text-ivory px-5 py-2 rounded-sm w-fit mt-5">
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}"
                    class="text-xs font-semibold uppercase tracking-widest text-warm hover:text-gold">
                    Login
                </a>
                <a href="{{ route('register') }}"
                    class="text-xs font-semibold uppercase tracking-widest bg-accent text-ivory px-5 py-2 rounded-sm w-fit">
                    Register
                </a>
            @endauth
        </div>
    </nav>
    @endunless

    {{-- PAGE CONTENT --}}
    @yield('content')

    {{-- FOOTER --}}
    @unless(View::hasSection('hideChrome'))
    <footer class="bg-ink text-warm pt-16 pb-8 px-6 lg:px-10">
        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 mb-14">
                <div class="lg:col-span-1">
                    <a href="{{ route('home') }}" class="font-display text-xl font-bold text-ivory inline-block mb-4">
                        The<span class="text-accent">.</span>Desk
                    </a>
                    <p class="text-sm text-muted leading-relaxed max-w-xs">
                        A personal blog on technology, business, and the craft of clear thinking. Published since {{ date('Y') }}.
                    </p>
                </div>
                <div>
                    <h4 class="text-[0.62rem] font-bold uppercase tracking-[0.2em] text-muted mb-4">Writing</h4>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('blog') }}" class="text-sm text-muted hover:text-gold transition-colors">All Posts</a>
                        <a href="{{ route('blog', ['featured' => 1]) }}" class="text-sm text-muted hover:text-gold transition-colors">Featured</a>
                        <a href="{{ route('blog', ['type' => 'book-notes']) }}" class="text-sm text-muted hover:text-gold transition-colors">Book Notes</a>
                        <a href="{{ route('blog', ['archive' => 1]) }}" class="text-sm text-muted hover:text-gold transition-colors">Archive</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-[0.62rem] font-bold uppercase tracking-[0.2em] text-muted mb-4">Topics</h4>
                    <div class="flex flex-col gap-2">
                        @foreach(collect($categories ?? [])->take(5) as $cat)
                        <a href="{{ route('blog', ['category' => $cat->slug]) }}" class="text-sm text-muted hover:text-gold transition-colors">{{ $cat->name }}</a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <h4 class="text-[0.62rem] font-bold uppercase tracking-[0.2em] text-muted mb-4">More</h4>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('about') }}" class="text-sm text-muted hover:text-gold transition-colors">About</a>
                        <a href="#newsletter" class="text-sm text-muted hover:text-gold transition-colors">Newsletter</a>
                        <a href="{{ url('/feed') }}" class="text-sm text-muted hover:text-gold transition-colors">RSS Feed</a>
                        <a href="{{ route('contact') }}" class="text-sm text-muted hover:text-gold transition-colors">Contact</a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row justify-between items-center pt-7 border-t border-white/[0.07] gap-2">
                <span class="text-xs text-[#5a5450]">© {{ date('Y') }} The Desk. All rights reserved.</span>
                <span class="text-xs text-[#5a5450]">Made with care &amp; good fonts.</span>
            </div>
        </div>
    </footer>
    @endunless

    <script>
        const toggle = document.getElementById('nav-toggle');
        const menu = document.getElementById('mobile-menu');
        toggle?.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            toggle.textContent = menu.classList.contains('hidden') ? '☰' : '✕';
        });
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                const el = document.querySelector(a.getAttribute('href'));
                if (el) {
                    e.preventDefault();
                    el.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
    @stack('scripts')
</body>

</html>