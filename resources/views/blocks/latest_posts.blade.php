<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4><i class="{{ config('other.font-awesome') }} fa-comments"></i> {{ __('blocks.latest-posts') }}</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th class="torrents-filename">{{ __('forum.post') }}</th>
                    <th>{{ __('forum.topic') }}</th>
                    <th>{{ __('forum.author') }}</th>
                    <th>{{ __('forum.created') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($posts as $p)
                    @if ($p->topic->viewable())
                        <tr>
                            <td>
                                <a
                                        href="{{ route('forum_topic', ['id' => $p->topic->id]) }}?page={{ $p->getPageNumber() }}#post-{{ $p->id }}">{{ preg_replace('#\[[^\]]+\]#', '', Str::limit(htmlspecialchars_decode($p->content)), 75) }}
                                    ...</a></td>
                            <td>{{ $p->topic->name }}</td>
                            <td>{{ $p->user->username }}</td>
                            <td>{{ $p->updated_at->diffForHumans() }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
                <thead>
                <tr>
                    <th colspan="4" class="text-right"><a href="{{ route('forum_latest_posts') }}"
                                                          class="text-info">{{ __('articles.read-more') }}</a></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
