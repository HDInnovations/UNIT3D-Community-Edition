<div class="col-md-10 col-sm-10 col-md-offset-1">
    <div class="clearfix visible-sm-block"></div>
    <div class="panel panel-chat shoutbox">
        <div class="panel-heading">
            <h4><i class="{{ config('other.font-awesome') }} fa-trophy-alt"></i> {{ __('user.top-uploaders-count') }}</h4>
        </div>

        <ul class="nav nav-tabs mb-12" role="tablist">
            <li class="col-md-6">
                <a href="#alltime" role="tab" data-toggle="tab" aria-expanded="true">
                    <i class="{{ config('other.font-awesome') }} fa-trophy-alt text-gold"></i> {{ __('stat.all-time') }}
                </a>
            </li>
            <li class="active col-md-6">
                <a href="#30days" role="tab" data-toggle="tab" aria-expanded="false">
                    <i class="{{ config('other.font-awesome') }} fa-trophy text-success"></i> {{ __('stat.last30days') }}
                </a>
            </li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane fade" id="alltime">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="torrents-icon"></th>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('user.total-uploads') }}</th>
                            <th>{{ __('stat.place') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($uploaders as $key => $uploader)
                            <tr>
                                <td>
                                    <div class="text-center">
                                        <i
                                                class="{{ config('other.font-awesome') }} fa-trophy-alt text-gold torrent-icon"></i>
                                    </div>
                                </td>

                                <td>
                                    @if ($uploader->user->private_profile == 1)
                                        <span class="badge-user text-bold">
                                                <span class="text-orange">
                                                    <i class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                       aria-hidden="true"></i> {{ strtoupper(__('common.hidden')) }}
                                                </span>
                                                @if ($user->id == $uploader->user->id || $user->group->is_modo == 1)
                                                <a href="{{ route('users.show', ['username' => $uploader->user->username]) }}">
                                                        ({{ $uploader->user->username }})
                                                    </a>
                                            @endif
                                            </span>
                                    @else
                                        <a href="{{ route('users.show', ['username' => $uploader->user->username]) }}">
                                                <span class="badge-user text-bold"
                                                      style="color:{{ $uploader->user->group->color }}; background-image:{{ $uploader->user->group->effect }}; margin-bottom: 10px;">
                                                    <i class="{{ $uploader->user->group->icon }}" data-toggle="tooltip"
                                                       data-original-title="{{ $uploader->user->group->name }}"></i>
                                                    {{ $uploader->user->username }}
                                                </span>
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    <span class="text-green">{{ $uploader->user->getUploads() }}</span>
                                </td>
                                <td>
                                        <span class="text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-ribbon"></i>
                                            {{ App\Helpers\StringHelper::ordinal(++$key) }} {{ __('stat.place') }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade active in" id="30days">
                <div class="table-responsive">
                    <table class="table table-condensed table-striped table-bordered">
                        <thead>
                        <tr>
                            <th class="torrents-icon"></th>
                            <th>{{ __('common.user') }}</th>
                            <th>{{ __('user.total-uploads') }}</th>
                            <th>{{ __('stat.place') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($past_uploaders as $key => $past_uploader)
                            <tr>
                                <td>
                                    <div class="text-center">
                                        <i
                                                class="{{ config('other.font-awesome') }} fa-trophy text-success torrent-icon"></i>
                                    </div>
                                </td>

                                <td>
                                    @if ($past_uploader->user->private_profile == 1)
                                        <span class="badge-user text-bold">
                                                <span class="text-orange">
                                                    <i class="{{ config('other.font-awesome') }} fa-eye-slash"
                                                       aria-hidden="true"></i> {{ strtoupper(__('common.hidden')) }}
                                                </span>
                                                @if ($user->id == $past_uploader->user->id || $user->group->is_modo == 1)
                                                <a
                                                        href="{{ route('users.show', ['username' => $past_uploader->user->username]) }}">
                                                        ({{ $past_uploader->user->username }})
                                                    </a>
                                            @endif
                                            </span>
                                    @else
                                        <a href="{{ route('users.show', ['username' => $past_uploader->user->username]) }}">
                                                <span class="badge-user text-bold"
                                                      style="color:{{ $past_uploader->user->group->color }}; background-image:{{ $past_uploader->user->group->effect }}; margin-bottom: 10px;">
                                                    <i class="{{ $past_uploader->user->group->icon }}"
                                                       data-toggle="tooltip"
                                                       data-original-title="{{ $past_uploader->user->group->name }}"></i>
                                                    {{ $past_uploader->user->username }}
                                                </span>
                                        </a>
                                    @endif
                                </td>

                                <td>
                                    <span class="text-green">{{ $past_uploader->user->getLast30Uploads() }}</span>
                                </td>
                                <td>
                                        <span class="text-bold"><i
                                                    class="{{ config('other.font-awesome') }} fa-ribbon"></i>
                                            {{ App\Helpers\StringHelper::ordinal(++$key) }} {{ __('stat.place') }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
