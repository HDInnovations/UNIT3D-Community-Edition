<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4>{{ trans('blocks.latest-posts') }}</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th class="torrents-filename">{{ trans('forum.post') }}</th>
                    <th>{{ trans('forum.topic') }}</th>
                    <th>{{ trans('forum.author') }}</th>
                    <th>{{ trans('forum.created') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach($posts as $p)
                    @if ($p->topic->viewable())
                        <tr class="">
                            <td>
                                <a href="{{ route('forum_topic', ['slug' => $p->topic->slug, 'id' => $p->topic->id]) }}?page={{$p->getPageNumber()}}#post-{{$p->id}}">{{ preg_replace('#\[[^\]]+\]#', '', str_limit($p->content), 75) }}
                                    ...</a></td>
                            <td>{{ $p->topic->name }}</td>
                            <td>{{ $p->user->username }}</td>
                            <td>{{ $p->updated_at->diffForHumans() }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
