@foreach ($articles as $article)
    <div class="col-md-10 col-sm-10 col-md-offset-1">
        @if ($article->newNews)
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4 class="text-center">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                           href="#collapse4" style="color:#ffffff;">
                            @joypixels(':rotating_light:') {{ __('blocks.new-news') }} {{ $article->created_at->diffForHumans() }}
                            @joypixels(':rotating_light:')
                        </a>
                    </h4>
                </div>
                @else
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h4 class="text-center">
                                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion"
                                   href="#collapse4" style="color:#ffffff;">
                                    {{ __('blocks.check-news') }} {{ $article->created_at->diffForHumans() }}
                                </a>
                            </h4>
                        </div>
                        @endif
                        <div aria-expanded="{{ ($article->newNews ? 'true' : 'false') }}" id="collapse4"
                             class="panel-collapse collapse {{ ($article->newNews ? 'in' : '') }}"
                             style="{{ ($article->newNews ? '' : 'height: 0;') }}">
                            <div class="panel-body no-padding">
                                <div class="news-blocks">
                                    <a href="{{ route('articles.show', ['id' => $article->id]) }}"
                                       style=" float: right; margin-right: 10px;">
                                        @if ( ! is_null($article->image))
                                            <img src="{{ url('files/img/' . $article->image) }}"
                                                 alt="{{ $article->title }}">
                                        @else
                                            <img src="{{ url('img/missing-image.png') }}" alt="{{ $article->title }}">
                                        @endif
                                    </a>

                                    <h1 class="text-bold" style="display: inline ;">{{ $article->title }}</h1>

                                    <p class="text-muted">
                                        <em>{{ __('articles.published-at') }}
                                            {{ $article->created_at->toDayDateTimeString() }}</em>
                                    </p>

                                    <p style="margin-top: 20px;">
                                        @joypixels(preg_replace('#\[[^\]]+\]#', '', Str::limit($article->content),
                                        150))...
                                    </p>

                                    <a href="{{ route('articles.show', ['id' => $article->id]) }}"
                                       class="btn btn-success">
                                        {{ __('articles.read-more') }}
                                    </a>

                                    <div class="pull-right">
                                        <a href="{{ route('articles.index') }}" class="btn btn-primary">
                                            {{ __('common.view-all') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
@endforeach
