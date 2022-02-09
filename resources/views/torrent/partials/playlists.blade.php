<div class="panel-body" style="padding: 5px;">
    <section class="playlists" style="height: 210px;">
        <div class="scroller" style="padding-bottom: 10px;">
            <div class="row">
                @forelse($torrent->playlists as $base)
                    <div class="col-md-2">
                        <a href="{{ route('playlists.show', ['id' => $base->playlist->id]) }}">
                            <div class="item-playlist-container-playlist"
                                 style="margin-bottom: 0 !important; min-height: 0 !important;">
                                @if(isset($base->playlist->cover_image))
                                    <img src="{{ url('files/img/' . $base->playlist->cover_image) }}" alt="Cover Image">
                                @else
                                    <div class="no_image_holder w273_and_h153 playlist"></div>
                                @endif
                                <div class="item-playlist-text-playlist">
                                    @if ($base->playlist->user->image != null)
                                        <img src="{{ url('files/img/' . $base->playlist->user->image) }}"
                                             alt="{{ $base->playlist->user->username }}">
                                    @else
                                        <img src="{{ url('img/profile.png') }}"
                                             alt="{{ $base->playlist->user->username }}">
                                    @endif
                                    <h3 class="text-bold"
                                        style="margin: 0; color: #cccccc;">{{ $base->playlist->name }}</h3>
                                    <h5>
                                        {{ __('playlist.added-by') }} <b>{{ $base->playlist->user->username }}</b>
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="text-center">
                        <h4 class="text-bold text-danger">
                            <i class="{{ config('other.font-awesome') }} fa-frown"></i> No Playlists Found!
                        </h4>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</div>