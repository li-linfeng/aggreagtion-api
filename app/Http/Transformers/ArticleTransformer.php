<?php

namespace App\Http\Transformers;

use App\Models\Article;

class ArticleTransformer extends BaseTransformer
{

    protected $availableIncludes = ['is_browsed', 'is_collected'];

    public function transform(Article  $article)
    {
        $route = request()->route()->getName();
        switch ($route) {
            default:
                return [
                    'id'           => $article->id,
                    'title'        => $article->title,
                    'description'  => $article->description,
                    'publish_time' => $article->publish_time,
                ];
        }
    }

    public function includeIsBrowsed(Article $article)
    {
        if (!$article->visit) {
            return $this->primitive(false);
        }
        return $this->primitive(true);
    }

    public function includeIsCollected(Article $article)
    {
        if (!$article->collect) {
            return $this->primitive(false);
        }
        return $this->primitive(true);
    }
}
