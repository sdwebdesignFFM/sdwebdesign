<?php

namespace App\Http\Controllers;

use App\Models\BlogArticle;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $search = $request->query('search');

        $query = BlogArticle::query()->published()->latest('published_at');

        if ($category && $category !== 'Alle Artikel') {
            $query->byCategory($category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $articles = $query->paginate(9);
        $categories = BlogArticle::query()
            ->published()
            ->select('category')
            ->distinct()
            ->pluck('category')
            ->toArray();

        SEOMeta::setTitle('Blog - Fachwissen zu digitalen Systemen');
        SEOMeta::setDescription('Technische Einblicke, Erfahrungen aus der Praxis und fundiertes Wissen zu digitalen Systemen, Integrationen und modernen Architekturen.');

        return view('pages.blog.index', compact('articles', 'categories', 'category', 'search'));
    }

    public function show(string $slug): View
    {
        $article = BlogArticle::query()
            ->published()
            ->bySlug($slug)
            ->firstOrFail();

        $relatedArticles = BlogArticle::query()
            ->published()
            ->where('id', '!=', $article->id)
            ->byCategory($article->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        if ($relatedArticles->count() < 3) {
            $additionalArticles = BlogArticle::query()
                ->published()
                ->where('id', '!=', $article->id)
                ->whereNotIn('id', $relatedArticles->pluck('id'))
                ->latest('published_at')
                ->limit(3 - $relatedArticles->count())
                ->get();

            $relatedArticles = $relatedArticles->concat($additionalArticles);
        }

        SEOMeta::setTitle($article->meta_title ?? $article->title);
        SEOMeta::setDescription($article->meta_description ?? $article->excerpt);
        OpenGraph::setTitle($article->meta_title ?? $article->title);
        OpenGraph::setDescription($article->meta_description ?? $article->excerpt);
        OpenGraph::addProperty('type', 'article');
        OpenGraph::addProperty('article:published_time', $article->published_at?->toIso8601String());
        JsonLd::setType('Article');
        JsonLd::setTitle($article->meta_title ?? $article->title);
        JsonLd::setDescription($article->meta_description ?? $article->excerpt);

        return view('pages.blog.show', compact('article', 'relatedArticles'));
    }
}
