<?php

namespace App\Presenters;

class PostPresenter extends Presenter
{
    /**
     * @return string
     */
    public function route()
    {
        $slug = $this->entity->topic->slug;
        $id = $this->entity->topic->id;

        $pageNumber = $this->entity->getPageNumber();
        $postId = $this->entity->id;

        return route('forum_topic', compact('slug', 'id')) . '?page=' . $pageNumber . '#post-' . $postId;
    }
}