<div>
    <table class="table table-condensed table-striped table-bordered" id="torrent-similar">
        <thead>
        <tr>
            @if ($user->group->is_modo)
                <th><input type="checkbox" wire:model="selectPage" style="vertical-align: middle;"></th>
            @endif
            <th class="torrents-filename">
                <div sortable wire:click="sortBy('name')" :direction="$sortField === 'name' ? $sortDirection : null"
                     role="button">
                    {{ __('common.name') }}
                    @include('livewire.includes._sort-icon', ['field' => 'name'])
                </div>
            </th>
            <th>
                <div sortable wire:click="sortBy('created_at')"
                     :direction="$sortField === 'created_at' ? $sortDirection : null" role="button">
                    {{ __('common.created_at') }}
                    @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                </div>
            </th>
            <th>
                <div sortable wire:click="sortBy('size')" :direction="$sortField === 'size' ? $sortDirection : null"
                     role="button">
                    <i class="{{ config('other.font-awesome') }} fa-database"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'size'])
                </div>
            </th>
            <th>
                <div sortable wire:click="sortBy('seeders')"
                     :direction="$sortField === 'seeders' ? $sortDirection : null" role="button">
                    <i class="{{ config('other.font-awesome') }} fa-arrow-alt-circle-up"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'seeders'])
                </div>
            </th>
            <th>
                <div sortable wire:click="sortBy('leechers')"
                     :direction="$sortField === 'leechers' ? $sortDirection : null" role="button">
                    <i class="{{ config('other.font-awesome') }} fa-arrow-alt-circle-down"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'leechers'])
                </div>
            </th>
            <th>
                <div sortable wire:click="sortBy('times_completed')"
                     :direction="$sortField === 'times_completed' ? $sortDirection : null"
                     role="button">
                    <i class="{{ config('other.font-awesome') }} fa-check-circle"></i>
                    @include('livewire.includes._sort-icon', ['field' => 'times_completed'])
                </div>
            </th>
        </tr>
        </thead>
    </table>

    <div>
        @if ($checked && $user->group->is_modo)
            <div class="dropdown">
                <a class="dropdown btn btn-xs btn-warning" data-toggle="dropdown" href="#" aria-expanded="true">
                    With Checked ({{ count($checked) }})
                    <i class="fas fa-caret-circle-right"></i>
                </a>
                <ul class="dropdown-menu">
                    <li role="presentation">
                        <a role="menuitem" tabindex="-1" href="#" wire:click="alertConfirm()">
                            <span class="menu-text">Delete</span>
                            <span class="selected"></span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif
    </div>

    <div class="table-responsive mt-20">
        <table class="table table-condensed table-bordered table-striped">
            @foreach(App\Models\Type::all()->sortBy('position') as $type)
                @if($torrents->where('type_id', '=', $type->id)->count() > 0)
                    <thead>
                    <tr>
                        <th class="text-center dark-th" colspan="12">
                            <span style="font-size: 16px;">{{ $type->name }}</span>
                        </th>
                    </tr>
                    </thead>
                @endif
                @foreach(App\Models\Resolution::all()->sortBy('position') as $resolution)
                    @if($torrents->where('type_id', '=', $type->id)->where('resolution_id', '=', $resolution->id)->count() > 0)
                        <thead>
                        <tr>
                            <th colspan="12">
                                <span class="label label-default text-bold">{{ $resolution->name }}</span>
                            </th>
                        </tr>
                        </thead>
                    @endif
                    <tbody>
                    @foreach($torrents->where('type_id', '=', $type->id)->where('resolution_id', '=', $resolution->id) as $torrent)
                        <tr>
                            @if ($user->group->is_modo)
                                <td><input type="checkbox" value="{{ $torrent->id }}" wire:model="checked"
                                           style="vertical-align: middle;"></td>
                            @endif
                            <td>
                                <a class="text-bright" style="font-size: 16px;"
                                   href="{{ route('torrent', ['id' => $torrent->id]) }}">
                                    {{ $torrent->name }}
                                </a>
                                @if (config('torrent.download_check_page') == 1)
                                    <a href="{{ route('download_check', ['id' => $torrent->id]) }}">
                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="{{ __('common.download') }}">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                @else
                                    <a href="{{ route('download', ['id' => $torrent->id]) }}">
                                        <button class="btn btn-primary btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="{{ __('common.download') }}">
                                            <i class="{{ config('other.font-awesome') }} fa-download"></i>
                                        </button>
                                    </a>
                                @endif

                                @php $history = App\Models\History::where('user_id', '=', $user->id)->where('info_hash', '=', $torrent->info_hash)->first() @endphp
                                @if ($history)
                                    @if ($history->seeder == 1 && $history->active == 1)
                                        <button class="btn btn-success btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="{{ __('torrent.currently-seeding') }}!">
                                            <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 1)
                                        <button class="btn btn-warning btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="{{ __('torrent.currently-leeching') }}!">
                                            <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at == null)
                                        <button class="btn btn-info btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="{{ __('torrent.not-completed') }}!">
                                            <i class="{{ config('other.font-awesome') }} fa-spinner"></i>
                                        </button>
                                    @endif

                                    @if ($history->seeder == 0 && $history->active == 0 && $history->completed_at != null)
                                        <button class="btn btn-danger btn-circle" type="button" data-toggle="tooltip"
                                                data-original-title="{{ __('torrent.completed-not-seeding') }}!">
                                            <i class="{{ config('other.font-awesome') }} fa-thumbs-down"></i>
                                        </button>
                                    @endif
                                @endif

                                <br>
                                @if ($torrent->anon == 1)
                                    <span class="badge-extra">
										<i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip"
                                           data-original-title="{{ __('torrent.uploader') }}"></i> {{ __('common.anonymous') }}
                                        @if ($user->id == $torrent->user->id || $user->group->is_modo)
                                            <a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
												({{ $torrent->user->username }})
											</a>
                                        @endif
                                    </span>
                                @else
                                    <span class="badge-extra">
										<i class="{{ config('other.font-awesome') }} fa-upload" data-toggle="tooltip"
                                           data-original-title="{{ __('torrent.uploader') }}"></i>
										<a href="{{ route('users.show', ['username' => $torrent->user->username]) }}">
											{{ $torrent->user->username }}
										</a>
									</span>
                                @endif

                                <span class="badge-extra text-pink">
									<i class="{{ config('other.font-awesome') }} fa-heart" data-toggle="tooltip"
                                       data-original-title="{{ __('torrent.thanks-given') }}"></i>
									{{ $torrent->thanks_count }}
								</span>

                                <span class="badge-extra text-green">
									<i class="{{ config('other.font-awesome') }} fa-comment" data-toggle="tooltip"
                                       data-original-title="{{ __('common.comments') }}"></i>
									{{ $torrent->comments_count }}
								</span>

                                @if ($torrent->internal == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-magic' data-toggle='tooltip'
                                           title='' data-original-title='{{ __('torrent.internal-release') }}'
                                           style="color: #baaf92;"></i>
									</span>
                                @endif

                                @if ($torrent->stream == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-play text-red'
                                           data-toggle='tooltip'
                                           title='' data-original-title='{{ __('torrent.stream-optimized') }}'></i>
									</span>
                                @endif

                                @if ($torrent->featured == 0)
                                    @if ($torrent->doubleup == 1)
                                        <span class='badge-extra'>
											<i class='{{ config('other.font-awesome') }} fa-gem text-green'
                                               data-toggle='tooltip' title=''
                                               data-original-title='{{ __('torrent.double-upload') }}'></i>
										</span>
                                    @endif
                                    @if ($torrent->free == 1)
                                        <span class='badge-extra'>
											<i class='{{ config('other.font-awesome') }} fa-star text-gold'
                                               data-toggle='tooltip' title=''
                                               data-original-title='{{ __('torrent.freeleech') }}'></i>
										</span>
                                    @endif
                                @endif

                                @if ($personalFreeleech)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-id-badge text-orange'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('torrent.personal-freeleech') }}'></i>
									</span>
                                @endif

                                @php $freeleech_token = App\Models\FreeleechToken::where('user_id', '=', $user->id)->where('torrent_id', '=', $torrent->id)->first() @endphp
                                @if ($freeleech_token)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-star'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('torrent.freeleech-token') }}'></i>
									</span>
                                @endif

                                @if ($torrent->featured == 1)
                                    <span class='badge-extra' style='background-image:url(/img/sparkels.gif);'>
										<i class='{{ config('other.font-awesome') }} fa-certificate text-pink'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('torrent.featured') }}'></i>
									</span>
                                @endif

                                @if ($user->group->is_freeleech == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-trophy text-purple'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('torrent.special-freeleech') }}'></i>
									</span>
                                @endif

                                @if (config('other.freeleech') == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-globe text-blue'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('torrent.global-freeleech') }}'></i>
									</span>
                                @endif

                                @if (config('other.doubleup') == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-globe text-green'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('torrent.double-upload') }}'></i>
									</span>
                                @endif

                                @if ($user->group->is_double_upload == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-trophy text-purple'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('torrent.special-double_upload') }}'></i>
									</span>
                                @endif

                                @if ($torrent->leechers >= 5)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-fire text-orange'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('common.hot') }}!'></i>
									</span>
                                @endif

                                @if ($torrent->sticky == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-thumbtack text-black'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('torrent.sticky') }}!'></i>
									</span>
                                @endif

                                @if ($user->updated_at->getTimestamp() < $torrent->created_at->getTimestamp())
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-magic text-black'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('common.new') }}!'></i>
									</span>
                                @endif

                                @if ($torrent->highspeed == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-tachometer text-red'
                                           data-toggle='tooltip' title=''
                                           data-original-title='{{ __('common.high-speeds') }}'></i>
									</span>
                                @endif

                                @if ($torrent->sd == 1)
                                    <span class='badge-extra'>
										<i class='{{ config('other.font-awesome') }} fa-ticket text-orantorrent.sd-content'
                                           )!'></i>
									</span>
                                @endif
                            </td>

                            <td>
                                <time>{{ $torrent->created_at->diffForHumans() }}</time>
                            </td>
                            <td>
                                <span class='badge-extra text-blue'>{{ $torrent->getSize() }}</span>
                            </td>
                            <td>
                                <a href="{{ route('peers', ['id' => $torrent->id]) }}">
									<span class='badge-extra text-green'>
										{{ $torrent->seeders }}
									</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('peers', ['id' => $torrent->id]) }}">
									<span class='badge-extra text-red'>
										{{ $torrent->leechers }}
									</span>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('history', ['id' => $torrent->id]) }}">
									<span class='badge-extra text-orange'>
										{{ $torrent->times_completed }} {{ __('common.times') }}
									</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                @endforeach
            @endforeach
        </table>
    </div>
</div>

@section('javascripts')
    @if ($user->group->is_modo)
        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
          window.addEventListener('swal:modal', event => {
            Swal.fire({
              title: event.detail.message,
              text: event.detail.text,
              icon: event.detail.type,
            })
          })

          window.addEventListener('swal:confirm', event => {
            const { value: text } = Swal.fire({
              input: 'textarea',
              inputLabel: 'Delete Reason',
              inputPlaceholder: 'Type your reason here...',
              inputAttributes: {
                'aria-label': 'Type your reason here'
              },
              inputValidator: (value) => {
                if (!value) {
                  return 'You need to write something!'
                }
              },
              title: event.detail.message,
              html: event.detail.body,
              icon: event.detail.type,
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
              if (result.isConfirmed) {
              @this.set('reason', result.value);
                Livewire.emit('destroy')
              }
            })
          })
        </script>
    @endif
@endsection