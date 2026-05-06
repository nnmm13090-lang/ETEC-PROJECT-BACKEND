@extends('layouts.app')
@section('title', 'All Users — Admin')

@section('content')

<section class="bg-ink text-ivory px-6 lg:px-10 py-10 border-b border-white/10">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-4">
        <div class="flex items-center gap-10">
            <a href="{{ route('admin.dashboard') }}"
               class="text-ivory/60 hover:text-ivory transition-colors text-sm">
                ← Back
            </a>
            <div>
                <p class="text-[0.65rem] font-bold uppercase tracking-[0.25em] text-accent mb-1">Admin Panel</p>
                <h1 class="font-display text-3xl font-bold tracking-tight">All Users</h1>
            </div>
        </div>
        <span class="text-xs text-muted uppercase tracking-widest">
            {{ $users->total() }} total
        </span>
    </div>
</section>

<section class="bg-ivory px-6 lg:px-10 py-10">
    <div class="max-w-7xl mx-auto">

        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 text-sm rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white border border-rule overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-cream border-b border-rule">
                        <th class="text-left px-5 py-3 text-[0.6rem] uppercase text-muted">#</th>
                        <th class="text-left px-5 py-3 text-[0.6rem] uppercase text-muted">Name</th>
                        <th class="hidden md:table-cell text-left px-4 py-3 text-[0.6rem] uppercase text-muted">Email</th>
                        <th class="text-left px-4 py-3 text-[0.6rem] uppercase text-muted">Role</th>
                        <th class="hidden lg:table-cell text-left px-4 py-3 text-[0.6rem] uppercase text-muted">Joined</th>
                        <th class="text-right px-5 py-3 text-[0.6rem] uppercase text-muted">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr class="border-b last:border-0 hover:bg-cream/50 {{ $user->id === auth()->id() ? 'bg-gold/5' : '' }}">
                        <td class="px-5 py-4 text-xs text-muted">{{ $user->id }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-ink text-ivory flex items-center justify-center text-[0.6rem] font-bold flex-shrink-0">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm">{{ $user->name }}</p>
                                    @if($user->id === auth()->id())
                                        <p class="text-[0.6rem] text-gold uppercase tracking-wide">You</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4 hidden md:table-cell text-xs text-muted">
                            {{ $user->email }}
                        </td>
                        <td class="px-4 py-4">
                            @if($user->role === 'admin')
                                <span class="text-xs bg-accent/10 text-accent font-bold px-2 py-1 rounded uppercase tracking-wide">
                                    Admin
                                </span>
                            @else
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded uppercase tracking-wide">
                                    User
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-4 hidden lg:table-cell text-xs text-muted">
                            {{ $user->created_at->format('M j, Y') }}
                        </td>
                        <td class="px-5 py-4 text-right space-x-2">
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.toggle-role', $user) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button class="text-xs text-accent2 hover:text-ink"
                                            onclick="return confirm('Change role for {{ $user->name }}?')">
                                        {{ $user->role === 'admin' ? 'Make User' : 'Make Admin' }}
                                    </button>
                                </form>

                                <span class="text-muted">|</span>

                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-500 hover:text-red-700"
                                            onclick="return confirm('Delete {{ $user->name }}? This cannot be undone.')">
                                        Delete
                                    </button>
                                </form>
                            @else
                                <span class="text-xs text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-10 text-muted">No users found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="mt-6">{{ $users->links() }}</div>
        @endif

    </div>
</section>

@endsection