@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('edit_form', ['id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ __('torrent.torrent') }} {{ __('common.edit') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="col-md-10">
            <h2>{{ __('common.edit') }}: {{ $torrent->name }}</h2>
            <div class="block">
                <form role="form" method="POST" action="{{ route('edit', ['id' => $torrent->id]) }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="title">{{ __('torrent.title') }}</label>
                        <label>
                            <input type="text" class="form-control" name="name" value="{{ $torrent->name }}" required>
                        </label>
                    </div>

                    @if ($torrent->category->no_meta)
                        <div class="form-group">
                            <label for="torrent-cover">Cover {{ __('torrent.file') }} ({{ __('torrent.optional') }})</label>
                            <input class="upload-form-file" type="file" accept=".jpg, .jpeg, .png" name="torrent-cover">
                        </div>
                        <div class="form-group">
                            <label for="torrent-banner">Banner {{ __('torrent.file') }} ({{ __('torrent.optional') }})</label>
                            <input class="upload-form-file" type="file" accept=".jpg, .jpeg, .png"
                                   name="torrent-banner">
                        </div>
                    @endif

                    @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                        <div class="form-group">
                            <label for="name">TMDB ID <b>({{ __('common.required') }})</b></label>
                            <label>
                                <input type="number" name="tmdb" value="{{ $torrent->tmdb }}" class="form-control"
                                       required>
                            </label>
                        </div>
                    @else
                        <input type="hidden" name="tmdb" value="0">
                    @endif

                    @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                        <div class="form-group">
                            <label for="name">IMDB ID <b>({{ __('torrent.optional') }})</b></label>
                            <label>
                                <input type="number" name="imdb" value="{{ $torrent->imdb }}" class="form-control"
                                       required>
                            </label>
                        </div>
                    @else
                        <input type="hidden" name="imdb" value="0">
                    @endif

                    @if ($torrent->category->tv_meta)
                        <div class="form-group">
                            <label for="name">TVDB ID <b>({{ __('torrent.optional') }})</b></label>
                            <label>
                                <input type="number" name="tvdb" value="{{ $torrent->tvdb }}" class="form-control"
                                       required>
                            </label>
                        </div>
                    @else
                        <input type="hidden" name="tvdb" value="0">
                    @endif

                    @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                        <div class="form-group">
                            <label for="name">MAL ID <b>({{ __('request.required') }} For Anime)</b></label>
                            <label>
                                <input type="number" name="mal" value="{{ $torrent->mal }}" class="form-control"
                                       required>
                            </label>
                        </div>
                    @else
                        <input type="hidden" name="mal" value="0">
                    @endif

                    @if ($torrent->category->game_meta)
                        <div class="form-group">
                            <label for="name">IGDB ID <b>{{ __('request.required') }} For Games)</b></label>
                            <label>
                                <input type="number" name="igdb" value="{{ $torrent->igdb }}" class="form-control"
                                       required>
                            </label>
                        </div>
                    @else
                        <input type="hidden" name="igdb" value="0">
                    @endif

                    <div class="form-group">
                        <label for="category_id">{{ __('torrent.category') }}</label>
                        <label>
                            <select name="category_id" class="form-control">
                                <option value="{{ $torrent->category->id }}" selected>{{ $torrent->category->name }}
                                    ({{ __('torrent.current') }})
                                </option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="type">{{ __('torrent.type') }}</label>
                        <label>
                            <select name="type_id" class="form-control">
                                <option value="{{ $torrent->type->id }}" selected>{{ $torrent->type->name }}
                                    ({{ __('torrent.current') }})
                                </option>
                                @foreach ($types as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>

                    @if ($torrent->category->movie_meta || $torrent->category->tv_meta)
                        <div class="form-group">
                            <label for="resolution_id">{{ __('torrent.resolution') }}</label>
                            <label>
                                <select name="resolution_id" class="form-control">
                                    @if (! $torrent->resolution)
                                        <option hidden="" disabled="disabled" selected="selected" value="">--Select
                                            Resolution--
                                        </option>)
                                    @else
                                        <option value="{{ $torrent->resolution->id }}"
                                                selected>{{ $torrent->resolution->name }}
                                            ({{ __('torrent.current') }})
                                        </option>
                                    @endif
                                    @foreach ($resolutions as $resolution)
                                        <option value="{{ $resolution->id }}">{{ $resolution->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    @endif

                    @if ($torrent->category->tv_meta)
                        <div class="form-group">
                            <label for="season_number">{{ __('torrent.season-number') }} <b>({{ __('common.required') }} For
                                    TV)</b></label>
                            <input type="number" name="season_number" id="season_number" class="form-control"
                                   value="{{ $torrent->season_number }}" required>
                        </div>
                    @endif

                    @if ($torrent->category->tv_meta)
                        <div class="form-group">
                            <label for="episode_number">{{ __('torrent.episode-number') }} <b>({{ __('common.required') }} For
                                    TV. Use "0" For Season Packs.)</b></label>
                            <input type="number" name="episode_number" id="episode_number" class="form-control"
                                   value="{{ $torrent->episode_number }}" required>
                        </div>
                    @endif

                    @if($torrent->type->name === 'Full Disc')
                        <div class="form-group">
                            <label for="distributor_id">{{ __('torrent.distributor') }}</label>
                            <label>
                                <select name="distributor_id" class="form-control">
                                    @if (! $torrent->distributor)
                                        <option hidden="" disabled="disabled" selected="selected" value="">--Select
                                            Distributor--
                                        </option>)
                                    @else
                                        <option value="{{ $torrent->distributor->id }}"
                                                selected>{{ $torrent->distributor->name }}
                                            ({{ __('torrent.current') }})
                                        </option>
                                    @endif
                                    <option value="">No Distributor</option>
                                    @foreach ($distributors as $distributor)
                                        <option value="{{ $distributor->id }}">{{ $distributor->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="region_id">{{ __('torrent.region') }}</label>
                            <label>
                                <select name="region_id" class="form-control">
                                    @if (! $torrent->region)
                                        <option hidden="" disabled="disabled" selected="selected" value="">--Select
                                            Region--
                                        </option>)
                                    @else
                                        <option value="{{ $torrent->region->id }}" selected>{{ $torrent->region->name }}
                                            ({{ __('torrent.current') }})
                                        </option>
                                    @endif
                                    <option value="">No Region</option>
                                    @foreach ($regions as $region)
                                        <option value="{{ $region->id }}">{{ $region->name }}</option>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    @endif

                    <div class="form-group">
                        <label for="description">{{ __('common.description') }}</label>
                        <label for="upload-form-description"></label>
                        <textarea id="upload-form-description" name="description" cols="30" rows="10"
                                  class="form-control">{{ $torrent->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="description">{{ __('torrent.media-info') }}</label>
                        <label>
                            <textarea name="mediainfo" cols="30" rows="10"
                                      class="form-control">{{ $torrent->mediainfo }}</textarea>
                        </label>
                    </div>

                    <div class="form-group">
                        <label for="description">BDInfo (Quick Summary)</label>
                        <label>
                            <textarea name="bdinfo" cols="30" rows="10"
                                      class="form-control">{{ $torrent->bdinfo }}</textarea>
                        </label>
                    </div>

                    @if (auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
                        <label for="hidden" class="control-label">{{ __('common.anonymous') }}?</label>
                        <div class="radio-inline">
                            <label><input type="radio" name="anonymous" @if ($torrent->anon == 1) checked
                                          @endif value="1">{{ __('common.yes') }}</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="anonymous" @if ($torrent->anon == 0) checked
                                          @endif value="0">{{ __('common.no') }}</label>
                        </div>
                        <br>
                        <br>
                    @else
                        <input type="hidden" name="anonymous" value={{ $torrent->anon }}>
                    @endif
                    <label for="hidden" class="control-label">{{ __('torrent.stream-optimized') }}?</label>
                    <div class="radio-inline">
                        <label><input type="radio" name="stream" @if ($torrent->stream == 1) checked
                                      @endif value="1">{{ __('common.yes') }}</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name="stream" @if ($torrent->stream == 0) checked
                                      @endif value="0">{{ __('common.no') }}</label>
                    </div>
                    <br>
                    <br>
                    <label for="hidden" class="control-label">{{ __('torrent.sd-content') }}?</label>
                    <div class="radio-inline">
                        <label><input type="radio" name="sd" @if ($torrent->sd == 1) checked
                                      @endif value="1">{{ __('common.yes') }}</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name="sd" @if ($torrent->sd == 0) checked
                                      @endif value="0">{{ __('common.no') }}</label>
                    </div>
                    <br>
                    <br>
                    @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                        <label for="internal" class="control-label">Internal?</label>
                        <div class="radio-inline">
                            <label><input type="radio" name="internal" @if ($torrent->internal == 1) checked
                                          @endif value="1">{{ __('common.yes') }}</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="internal" @if ($torrent->internal == 0) checked
                                          @endif value="0">{{ __('common.no') }}</label>
                        </div>
                        <br>
                        <br>
                    @else
                        <input type="hidden" name="internal" value="0">
                    @endif
                    @if (auth()->user()->group->is_modo || auth()->user()->id === $torrent->user_id)
                        <label for="personal" class="control-label">Personal Release?</label>
                        <div class="radio-inline">
                            <label><input type="radio" name="personal_release"
                                          @if ($torrent->personal_release == 1) checked
                                          @endif value="1">{{ __('common.yes') }}</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="personal_release"
                                          @if ($torrent->personal_release == 0) checked
                                          @endif value="0">{{ __('common.no') }}</label>
                        </div>
                    @else
                        <input type="hidden" name="personal_release" value="0">
                    @endif
                    <br>
                    <br>
                    <button type="submit" class="btn btn-primary">{{ __('common.submit') }}</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(document).ready(function () {
        $('#upload-form-description').wysibb({})
      })

    </script>
@endsection
