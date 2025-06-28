<?php

use App\Models\Article;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('buy');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('articles/', function () {
    return Inertia::render('Article/Index', [
        'articles' =>
            Article::published()->with('user')->latest()
//            ->paginate(10)
                ->get()->map(function ($article) {
                    return [
                        'id' => $article->id,
                        'title' => $article->title,
                        'excerpt' => $article->excerpt,
                        'body' => $article->body,
                        'author' => [
                            'name' => $article->user->name,
                        ],
                        'seo' => [
                            'title' => $article->seo_title,
                            'description' => $article->meta_description,
                            'keywords' => $article->meta_keywords,
                        ],
                        'link' => $article->link(),
                        'createdAt' => $article->created_at->toIso8601String(),
                    ];
                }),
    ]);
})->name('articles');

Route::get('article/{slug}', function () {
    return Inertia::render('Article/Show', [
        'article' => Article::published()->with('user')->where('slug', request()->route('slug'))->firstOrFail()->toResource(),
    ]);
})->name('articles.show');

Route::get('watch/{slug}', function () {
    return Inertia::render('Watch/Show', [
        'article' => Article::published()->with('user')->where('slug', request()->route('slug'))->firstOrFail()->toResource(),
    ]);
})->name('watch');

Route::get('watch', function () {
    return Inertia::render('Watch/Show', [

       ]);
})->name('watch');


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
