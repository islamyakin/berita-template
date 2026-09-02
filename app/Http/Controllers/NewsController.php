<?php

namespace App\Http\Controllers;

use App\Support\NewsRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(Request $request): View
    {
        $keyword = trim((string) $request->query('q', ''));

        if ($keyword !== '') {
            return view('berita.search', [
                'keyword' => $keyword,
                'articles' => NewsRepository::search($keyword),
                'popular' => NewsRepository::popular(),
            ]);
        }

        return view('berita.index', [
            'headline' => NewsRepository::headline(),
            'editorPicks' => NewsRepository::latest(2, NewsRepository::headline()['slug']),
            'articles' => NewsRepository::latest(9, NewsRepository::headline()['slug']),
            'popular' => NewsRepository::popular(),
        ]);
    }

    public function category(string $slug): View
    {
        $category = NewsRepository::category($slug);

        abort_if($category === null, 404);

        return view('berita.category', [
            'category' => $category,
            'articles' => NewsRepository::byCategory($slug),
            'popular' => NewsRepository::popular(),
        ]);
    }

    public function show(string $slug): View
    {
        $article = NewsRepository::find($slug);

        abort_if($article === null, 404);

        return view('berita.show', [
            'article' => $article,
            'related' => NewsRepository::related($article),
            'popular' => NewsRepository::popular(),
        ]);
    }
}
