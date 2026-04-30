<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use App\Models\Post;

/*
|--------------------------------------------------------------------------
| PUBLIC PAGES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('pages.home');
})->name('home');

Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact', function (Request $request) {

    $request->validate([
        'name' => 'required',
        'email' => 'required|email',
        'subject' => 'required',
        'message' => 'required|min:10',
    ]);

    return back()->with('success', 'Message sent!');
})->name('contact.send');

Route::get('/blog', function () {

    $posts = Post::latest()->paginate(9);

    return view('pages.blog', compact('posts'));

})->name('blog');

/*
|--------------------------------------------------------------------------
| AUTH (GUEST ONLY)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    // LOGIN
    Route::get('/login', fn () => view('auth.login'))->name('login');

    Route::post('/login', function (Request $request) {

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Invalid credentials',
            ]);
        }

        $request->session()->regenerate();

        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('home');
    });

    // REGISTER
    Route::get('/register', fn () => view('auth.register'))->name('register');

    Route::post('/register', function (Request $request) {

        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'user',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('home');
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
| ADMIN PANEL (POST SYSTEM)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | POSTS (FULL CRUD STRUCTURE)
    |--------------------------------------------------------------------------
    */

    // LIST POSTS
    Route::get('/posts', function () {
        return view('admin.posts.index');
    })->name('posts.index');

    // CREATE POST FORM
    Route::get('/posts/create', function () {
        return view('admin.posts.create');
    })->name('posts.create');

    // STORE POST (FAKE FOR NOW)
    Route::post('/posts', function (Request $request) {

        $request->validate([
            'title' => 'required',
            'content' => 'required',
        ]);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully');
    })->name('posts.store');

    // EDIT POST
    Route::get('/posts/{id}/edit', function ($id) {
        return view('admin.posts.edit', compact('id'));
    })->name('posts.edit');

    /*
    |--------------------------------------------------------------------------
    | CATEGORIES
    |--------------------------------------------------------------------------
    */

    Route::get('/categories', fn () => view('admin.category.index'))->name('categories.index');
    Route::get('/categories/create', fn () => view('admin.category.create'))->name('categories.create');

    /*
    |--------------------------------------------------------------------------
    | PLACEHOLDERS
    |--------------------------------------------------------------------------
    */

    Route::get('/users', fn () => view('admin.user'))->name('users');
    Route::get('/media', fn () => view('admin.dashboard'))->name('media');
    Route::get('/comments', fn () => view('admin.dashboard'))->name('comments');
});

Route::get('/', function () {

    $recentPosts = Post::latest()->take(6)->get();

    return view('pages.home', [
        'recentPosts' => $recentPosts
    ]);

})->name('home');