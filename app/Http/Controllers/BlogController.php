<?php

namespace App\Http\Controllers;

use App\Models\BlogArticle;
use App\Models\Setting;
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

        $blogPostingSchema = $this->buildBlogPostingSchema($article);

        return view('pages.blog.show', compact('article', 'relatedArticles', 'blogPostingSchema'));
    }

    /**
     * Build the full BlogPosting schema. We emit this as a raw <script> in the
     * view so all required Article rich-result fields are actually present —
     * the JsonLd facade was previously emitting a bare "Article" with no dates,
     * author, or publisher, which disqualifies the page from rich results.
     *
     * @return array<string, mixed>
     */
    private function buildBlogPostingSchema(BlogArticle $article): array
    {
        $settings = Setting::first();
        $baseUrl = rtrim(config('app.url'), '/');
        if (! str_contains($baseUrl, '.test') && ! str_contains($baseUrl, 'localhost')) {
            $baseUrl = preg_replace('#^http://#', 'https://', $baseUrl);
        }

        $url = $baseUrl.'/ratgeber/'.$article->getTranslation('slug', 'de');
        $orgId = $baseUrl.'/#organization';

        return array_filter([
            '@context' => 'https://schema.org',
            '@type' => 'BlogPosting',
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $url,
            ],
            'headline' => $article->meta_title ?? $article->title,
            'description' => $article->meta_description ?? $article->excerpt,
            'url' => $url,
            'datePublished' => $article->published_at?->toIso8601String(),
            'dateModified' => ($article->updated_at ?? $article->published_at)?->toIso8601String(),
            'inLanguage' => 'de-DE',
            'author' => [
                '@type' => 'Organization',
                '@id' => $orgId,
                'name' => $settings?->company_name ?? 'sdWebdesign',
                'url' => $baseUrl,
            ],
            'publisher' => [
                '@type' => 'Organization',
                '@id' => $orgId,
                'name' => $settings?->company_name ?? 'sdWebdesign',
                'url' => $baseUrl,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $baseUrl.'/apple-touch-icon.png',
                ],
            ],
            'image' => $baseUrl.'/apple-touch-icon.png',
        ], fn ($v) => $v !== null && $v !== '');
    }
}
