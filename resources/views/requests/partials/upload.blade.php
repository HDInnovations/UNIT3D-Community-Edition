<a
    href="{{ route('upload_form', ['category_id' => $torrentRequest->category_id, 'title' => $meta->title ?? ' ', 'imdb' => $meta->imdb ?? 0, 'tmdb' => $meta->tmdb ?? 0]) }}"
    class="form__button form__button--filled form__button--centered"
>
    {{ __('common.upload') }}
</a>
