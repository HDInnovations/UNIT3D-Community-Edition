@foreach ($articles as $article)
    <div class="col-md-10 col-sm-10 col-md-offset-1">
        @if (auth()->user()->updated_at->getTimestamp() < $article->created_at->getTimestamp())
        <div class="panel panel-danger">
            <div class="panel-heading">
                <h4 class="text-center">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                       href="#collapse4" style="color:#fff">
                        @emojione(':rotating_light:') @lang('blocks.new-news') @emojione(':rotating_light:')
                    </a>
                </h4>
            </div>
        @else
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4 class="text-center">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                       href="#collapse4" style="color:#fff">
                        @lang('blocks.check-news')
                    </a>
                </h4>
            </div>
        @endif
        <div id="collapse4" class="panel-collapse collapse" style="height: 0px;">
            <div class="panel-body no-padding">
                <div class="news-blocks">
                    <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}"
                       style=" float: right; margin-right: 10px;">
                        @if ( ! is_null($article->image))
                            <img src="{{ url('files/img/' . $article->image) }}" alt="{{ $article->title }}">
                        @else
                            <img src="{{ url('img/missing-image.jpg') }}" alt="{{ $article->title }}">
                        @endif
                    </a>

                    <h1 class="text-bold" style="display: inline ;">{{ $article->title }}</h1>

                    <p class="text-muted">
                        <em>@lang('articles.published-at') {{ $article->created_at->toDayDateTimeString() }}</em>
                    </p>

                    <p style="margin-top: 20px;">
                        @emojione(preg_replace('#\[[^\]]+\]#', '', str_limit($article->content), 150))...
                    </p>

                    <a href="{{ route('article', ['slug' => $article->slug, 'id' => $article->id]) }}" class="btn btn-success">
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
</div>
@endforeach
