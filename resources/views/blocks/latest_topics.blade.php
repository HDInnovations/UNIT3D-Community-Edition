<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4><i class="{{ config('other.font-awesome') }} fa-list-alt"></i> {{ __('blocks.latest-topics') }}</h4>
        </div>
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered">
                <thead>
                <tr>
                    <th class="torrents-filename">{{ __('forum.forum') }}</th>
                    <th class="torrents-filename">{{ __('forum.topic') }}</th>
                    <th>{{ __('forum.author') }}</th>
                    <th>{{ __('forum.created') }}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($topics as $topic)
                    @if ($topic->viewable())
                        <tr>
                            <td>
                                <a href="{{ route('forums.show', ['id' => $topic->forum->id]) }}">
                                    {{ $topic->forum->name }}
                                </a>
                            </td>

                            <td>
                                <a href="{{ route('forum_topic', ['id' => $topic->id]) }}">
                                    {{ $topic->name }}
                                </a>
                            </td>

                            <td>{{ $topic->first_post_user_username }}</td>
                            <td>{{ $topic->created_at->diffForHumans() }}</td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
                <thead>
                <tr>
                    <th colspan="4" class="text-right"><a href="{{ route('forum_latest_topics') }}"
                                                          class="text-info">{{ __('articles.read-more') }}</a></th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
