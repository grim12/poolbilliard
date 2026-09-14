<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ArticleController extends Controller
{
    /**
     * "Novinky" — mirrors ui/src/novinky.njk (article grid only; the "Důležité zprávy" sidebar
     * reuses the Notice model and is deferred until Notice gets its own public surface).
     * Category filter pills are decorative, same as ui/'s own (search/filter aren't wired up
     * to real filtering there either — see components/news-header.blade.php).
     */
    public function index(): View
    {
        $articles = Article::with('category')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->paginate(9)
            ->withQueryString();

        $categoryTabs = collect([['text' => 'Vše', 'value' => 'all', 'isActive' => true]])
            ->concat(
                ArticleCategory::orderBy('sort_order')->get()->map(fn (ArticleCategory $category) => [
                    'text' => $category->name,
                    'value' => Str::slug($category->name),
                    'isActive' => false,
                ])
            );

        return view('novinky', [
            'articles' => $articles,
            'categoryTabs' => $categoryTabs,
        ]);
    }

    /**
     * Single article — mirrors ui/src/article-detail.njk. Related articles are the 3 most
     * recent other articles in the same category (or just most recent overall when the
     * article has no category), unlike ui/'s hand-picked static example list.
     */
    public function show(Article $article): View
    {
        $article->load('category');

        $related = Article::with('category')
            ->where('id', '!=', $article->id)
            ->when(
                $article->article_category_id,
                fn ($query) => $query->where('article_category_id', $article->article_category_id)
            )
            ->orderByDesc('published_at')
            ->take(3)
            ->get();

        return view('clanek', [
            'article' => $article,
            'related' => $related,
        ]);
    }
}
