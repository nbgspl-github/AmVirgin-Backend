<?php

namespace App\Http\Modules\Admin\Controllers\Web\News;

use App\Library\Enums\News\Article\Types;
use App\Models\News\Article;
use Exception;
use Illuminate\Http\JsonResponse;

class ArticleController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (): \Illuminate\Contracts\Support\Renderable
    {
        return view('admin.news.articles.index')->with('articles',
            $this->paginateWithQuery(Article::query()->whereLike('title', $this->queryParameter())->latest())
        );
    }

    public function edit (Article $article): \Illuminate\Http\RedirectResponse
    {
        if ($article->type->is(Types::Article)) {
            return redirect()->route('admin.news.articles.content.edit', $article->id);
        } else {
            return redirect()->route('admin.news.articles.videos.edit', $article->id);
        }
    }

    public function store (): \Illuminate\Http\RedirectResponse
    {
    }

    public function update (): \Illuminate\Http\RedirectResponse
    {
    }

    /**
     * @param Article $article
     * @return JsonResponse
     * @throws Exception
     */
    public function delete (Article $article): JsonResponse
    {
        $article->delete();
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'News article deleted successfully.'
        );
    }
}