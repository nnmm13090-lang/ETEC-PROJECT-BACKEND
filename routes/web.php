<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Models\Categories;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $recentPosts = Post::with(['author', 'category'])
        ->published()
        ->latest('published_at')
        ->take(6)
        ->get();

    $categories = Categories::withCount('posts')->get();

    return view('pages.home', compact('recentPosts', 'categories'));
})->name('home');

Route::get('/about', function () {
    $categories = Categories::all();
    return view('pages.about', compact('categories'));
})->name('about');

Route::get('/contact', fn () => view('pages.contact'))->name('contact');

Route::post('/contact', function (Request $request) {
    $request->validate([
        'name'    => 'required',
        'email'   => 'required|email',
        'subject' => 'required',
        'message' => 'required|min:10',
    ]);
    return back()->with('success', 'Message sent!');
})->name('contact.send');

Route::get('/blog', function (Request $request) {
    $query = Post::with(['author', 'category'])
        ->published()
        ->latest('published_at');

    if ($request->category) {
        $query->whereHas('category', fn ($q) =>
            $q->where('slug', $request->category)
        );
    }

    $posts      = $query->paginate(9)->withQueryString();
    $categories = Categories::withCount('posts')->get();

    return view('pages.blog', compact('posts', 'categories'));
})->name('blog');

// Single post — public
Route::get('/post/{slug}', [PostController::class, 'show'])->name('post');

// Comments — auth required
Route::post('/post/{post}/comment', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('comment.store');

/*
|--------------------------------------------------------------------------
| AUTH (GUEST ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', fn () => view('auth.login'))->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials',
            ]);
        }

        $request->session()->regenerate();

        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    });

    Route::get('/register', fn () => view('auth.register'))->name('register');

    Route::post('/register', function (Request $request) {
        $data = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'user',
        ]);

        return redirect()->route('login')->with('status', 'Account created! Please sign in.');
    });

});

/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('home');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        $stats = [
            'posts'       => Post::count(),
            'published'   => Post::where('status', 'published')->count(),
            'views'       => Post::sum('views'),
            'subscribers' => 0,
        ];

        $posts = Post::with(['author', 'category'])
            ->latest()
            ->take(10)
            ->get();

        $recentPosts = $posts;

        return view('admin.dashboard', compact('stats', 'posts', 'recentPosts'));
    })->name('dashboard');

    // Posts CRUD
    Route::resource('posts', PostController::class);

    // Categories
    Route::resource('categories', CategoriesController::class)->only([
        'index', 'create', 'store', 'destroy'
    ]);

    // Placeholders
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-role', [UserController::class, 'toggleRole'])->name('users.toggle-role');
    Route::get('/media',    fn () => view('admin.dashboard'))->name('media');
    Route::get('/comments', fn () => view('admin.dashboard'))->name('comments');
    Route::get('/post/{slug}', [PostController::class, 'show'])->name('post');

});