<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4>@lang('blocks.latest-posts')</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th width="40%" class="torrents-filename">@lang('forum.post')</th>
                    <th width="20%">@lang('forum.topic')</th>
                    <th width="20%">@lang('forum.author')</th>
                    <th width="20%">@lang('forum.created')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($posts as $p)
                    @if ($p->topic->viewable())
                        <tr class="">
                            <td width="40%">
                                <a href="{{ route('forum_topic', ['slug' => $p->topic->slug, 'id' => $p->topic->id]) }}?page={{$p->getPageNumber()}}#post-{{$p->id}}">{{ preg_replace('#\[[^\]]+\]#', '', str_limit($p->content), 75) }}
                                    ...</a></td>
                            <td width="20%">{{ $p->topic->name }}</td>
                            <td width="20%">{{ $p->user->username }}</td>
                            <td width="20%">{{ $p->updated_at->diffForHumans() }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
                <thead>
                <tr>
                    <th colspan="4" class="text-right"><a href="{{ route('forum_latest_posts') }}" class="text-info">@lang('articles.read-more')</a></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
