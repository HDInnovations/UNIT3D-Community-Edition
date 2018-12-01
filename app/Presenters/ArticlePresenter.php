<?php

namespace App\Presenters;

use ChristofferOK\LaravelEmojiOne\LaravelEmojiOne;
use Illuminate\Support\Facades\Lang;

class ArticlePresenter extends Presenter
{
    /**
     * Return the type of panel this should be for the news block
     *
     * @return string
     */
    public function newsPanelType()
    {
        if (auth()->user()->updated_at->lt($this->entity->created_at)) {
            return 'danger';
        }

        return 'success';
    }

    /**
     * @return string
     */
    public function newsPanelContent()
    {
        $emoji = '';

        if (auth()->user()->updated_at->lt($this->entity->created_at)) {
            $emoji = LaravelEmojiOne::toImage(':rotating_light:');
        }

        return $emoji . Lang::trans('blocks.new-news') . $emoji;
    }

    /**
     * @return string
     */
    public function image()
    {
        $alt = e($this->entity->title);
        $image = url($this->entity->image ? 'files/img/' . $this->entity->image : 'img/missing-image.jpg');

        return "<img src=\"{$image}\" alt=\"{$alt}\">";
    }
}
