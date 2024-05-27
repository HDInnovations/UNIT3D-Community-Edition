@foreach ($articles as $article)
    <section class="panelV2 blocks__news" x-data="{ show: {{ $article->newNews }} }">
        <header class="panel__header" x-on:click="show = !show" style="cursor: pointer">
            <h2 class="panel__heading panel__heading--centered">
                @if ($article->newNews)
                    @joypixels(':rotating_light:')
                    {{ __('blocks.new-news') }} {{ $article->created_at->diffForHumans() }}
                    @joypixels(':rotating_light:')
                @else
                    {{ __('blocks.check-news') }} {{ $article->created_at->diffForHumans() }}
                @endif
            </h2>
            <div class="panel__actions">
                <div class="panel__action">
                    <a
                        href="{{ route('articles.index') }}"
                        class="form__button form__button--text"
                    >
                        {{ __('common.view-all') }}
                    </a>
                </div>
            </div>
        </header>
        <div class="panel__body" x-cloak x-show="show">
            <article class="article-preview">
                <header class="article-preview__header">
                    <h2 class="article-preview__title">
                        <a
                            class="article-preview__link"
                            href="{{ route('articles.show', ['article' => $article]) }}"
                        >
                            {{ $article->title }}
                        </a>
                    </h2>
                    <time
                        class="article-preview__published-date"
                        datetime="{{ $article->created_at }}"
                        title="{{ $article->created_at }}"
                    >
                        {{ $article->created_at->diffForHumans() }}
                    </time>
                    <img
                        class="article-preview__image"
                        src="{{ url($article->image ? 'files/img/' . $article->image : 'img/missing-image.png') }}"
                        alt=""
                    />
                </header>
                <p class="article-preview__content">
                    @joypixels(preg_replace('#\[[^\]]+\]#', '', Str::limit($article->content, 500, '...'), 150))
                </p>
                <a
                    href="{{ route('articles.show', ['article' => $article]) }}"
                    class="article-preview__read-more"
                >
                    {{ __('articles.read-more') }}
                </a>
            </article>
        </div>
    </section>
@endforeach
