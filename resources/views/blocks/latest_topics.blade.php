<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4><i class="{{ config("other.font-awesome") }} fa-list-alt"></i> @lang('blocks.latest-topics')</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th width="40%" class="torrents-filename">@lang('forum.forum')</th>
                    <th width="20%" class="torrents-filename">@lang('forum.topic')</th>
                    <th width="20%">@lang('forum.author')</th>
                    <th width="20%">@lang('forum.created')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($topics as $topic)
                    @if ($topic->viewable())
                        <tr class="">
                            <td width="40%">
                                <a href="{{ route('forum_display', ['slug' => $topic->forum->slug, 'id' => $topic->forum->id]) }}">
                                    {{ $topic->forum->name }}
                                </a>
                            </td>

                            <td width="20%">
                                <a href="{{ route('forum_topic', ['slug' => $topic->slug, 'id' => $topic->id]) }}">
                                    {{ $topic->name }}
                                </a>
                            </td>

                            <td width="20%">{{ $topic->first_post_user_username }}</td>
                            <td width="20%">{{ $topic->created_at->diffForHumans() }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
                <thead>
                <tr>
                    <th colspan="4" class="text-right"><a href="{{ route('forum_latest_topics') }}" class="text-info">@lang('articles.read-more')</a></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
