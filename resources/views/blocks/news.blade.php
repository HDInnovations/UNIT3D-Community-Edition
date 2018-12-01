@foreach ($articles as $article)
    <div class="panel panel-{{ $article->present()->newsPanelType() }}">
        <div class="panel-heading">
            <h4 class="text-center">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                   href="#collapse4" style="color:#fff">
                    {{ $article->present()->newsPanelContent() }}
                </a>
            </h4>
        </div>

        <div id="collapse4" class="panel-collapse collapse" style="height: 0px;">
            <div class="panel-body no-padding">
                <div class="news-blocks">
                    <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}"
                       style="float: right; margin-right: 10px;">
                        {!! $article->present()->image() !!}
                    </a>

                    <h1 class="text-bold" style="display: inline ;">{{ $article->title }}</h1>

                    <p class="text-muted">
                        <em>@lang('articles.published-at') {{ $article->created_at->toDayDateTimeString() }}</em>
                    </p>

                    <p style="margin-top: 20px;">
                        @emojione(preg_replace('#\[[^\]]+\]#', '', str_limit($article->content), 150))
                    </p>

                    <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}"
                       class="btn btn-success">
                        @lang('articles.read-more')
                    </a>

                    <div class="pull-right">
                        <a href="{{ route('articles') }}" class="btn btn-primary">
                            @lang('common.view-all')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
