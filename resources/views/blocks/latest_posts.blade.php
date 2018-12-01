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
            @foreach($posts as $post)
                @if($post->topic->viewable())
                    <tr>
                        <td>
                            <a href="{{ $post->present()->route() }}">
                                {{ preg_replace('#\[[^\]]+\]#', '', str_limit($post->content, 30), 75) }}
                            </a>
                        </td>

                        <td>{{ $post->topic->name }}</td>

                        <td>{{ $post->user->username }}</td>

                        <td>{{ $post->updated_at->diffForHumans() }}</td>
                    </tr>
                @endif
            @endforeach
            </tbody>
        </table>
    </div>
</div>
