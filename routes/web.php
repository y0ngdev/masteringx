<?php

use App\Http\Resources\ModuleResource;
use App\Models\Article;
use App\Models\Lesson;
use App\Models\Module;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');
Route::get('/', function () {
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

Route::get('watch', function () {

    $module = Module::published()
        ->orderBy('order')
        ->firstOrFail();
    $query = $module?->lessons->
    where('is_published', true)
        ->where('status', 'ready');

    $user = Auth::user();

    if (!$user || !$user->subscribed()) {
        $query->where('can_preview', true);
    }

    $lesson = $query->first();
    if (!$lesson) {
        return redirect()->route('home');
    }

    return redirect()->route('watch.lesson', $lesson->slug);
})->name('watch');


Route::get('watch/{slug}', function () {


    $modules = Module::published()->with('lessons')
        ->orderBy('order')
        ->get();


    return Inertia::render('Watch/Show', [
        'lesson' => Lesson::where('slug', request()->route('slug'))->firstOrFail(),
        'modules' => new ModuleResource($modules),

    ]);
})->name('watch.lesson');


require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
