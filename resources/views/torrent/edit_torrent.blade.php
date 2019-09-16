@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('edit_form', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">@lang('torrent.torrent') @lang('common.edit')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="col-md-10">
            <h2>@lang('common.edit'): {{ $torrent->name }}</h2>
            <div class="block">
                <form role="form" method="POST" action="{{ route('edit', ['slug' => $torrent->slug, 'id' => $torrent->id]) }}">
                @csrf
                <div class="form-group">
                    <label for="title">@lang('torrent.title')</label>
                    <label>
                        <input type="text" class="form-control" name="name" value="{{ $torrent->name }}" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="name">IMDB ID <b>(@lang('common.required'))</b></label>
                    <label>
                        <input type="number" name="imdb" value="{{ $torrent->imdb }}" class="form-control" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="name">TMDB ID <b>(@lang('request.required'))</b></label>
                    <label>
                        <input type="number" name="tmdb" value="{{ $torrent->tmdb }}" class="form-control" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="name">TVDB ID (Optional)</label>
                    <label>
                        <input type="number" name="tvdb" value="{{ $torrent->tvdb }}" class="form-control" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="name">MAL ID (Optional)</label>
                    <label>
                        <input type="number" name="mal" value="{{ $torrent->mal }}" class="form-control" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="name">IGDB ID <b>(@lang('request.required'))</b></label>
                    <label>
                        <input type="number" name="igdb" value="{{ $torrent->igdb }}" class="form-control" required>
                    </label>
                </div>

                <div class="form-group">
                    <label for="category_id">@lang('torrent.category')</label>
                    <label>
                        <select name="category_id" class="form-control">
                            <option value="{{ $torrent->category->id }}" selected>{{ $torrent->category->name  }}
                                (@lang('torrent.current'))
                            </option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="form-group">
                    <label for="type">@lang('torrent.type')</label>
                    <label>
                        <select name="type" class="form-control">
                            <option value="{{ $torrent->type }}" selected>{{ $torrent->type  }} (@lang('torrent.current'))
                            </option>
                            @foreach ($types as $type)
                                <option value="{{ $type->name }}">{{ $type->name }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div class="form-group">
                    <label for="description">@lang('common.description')</label>
                    <label for="upload-form-description"></label><textarea id="upload-form-description" name="description" cols="30" rows="10"
                                                                           class="form-control">{{ $torrent->description }}</textarea>
                </div>

                <div class="form-group">
                    <label for="description">@lang('torrent.media-info')</label>
                    <label>
                        <textarea name="mediainfo" cols="30" rows="10" class="form-control">{{ $torrent->mediainfo }}</textarea>
                    </label>
                </div>

                <label for="hidden"
                       class="control-label">@lang('common.anonymous')?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="anonymous" @if ($torrent->anon == 1) checked
                                  @endif value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="anonymous" @if ($torrent->anon == 0) checked
                                  @endif value="0">@lang('common.no')</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">@lang('torrent.stream-optimized')?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="stream" @if ($torrent->stream == 1) checked
                                  @endif value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="stream" @if ($torrent->stream == 0) checked
                                  @endif value="0">@lang('common.no')</label>
                </div>
                <br>
                <br>
                <label for="hidden" class="control-label">@lang('torrent.sd-content')?</label>
                <div class="radio-inline">
                    <label><input type="radio" name="sd" @if ($torrent->sd == 1) checked
                                  @endif value="1">@lang('common.yes')</label>
                </div>
                <div class="radio-inline">
                    <label><input type="radio" name="sd" @if ($torrent->sd == 0) checked
                                  @endif value="0">@lang('common.no')</label>
                </div>
                <br>
                <br>
                @if (auth()->user()->group->is_modo || auth()->user()->group->is_internal)
                    <label for="internal" class="control-label">Internal?</label>
                    <div class="radio-inline">
                        <label><input type="radio" name="internal" @if ($torrent->internal == 1) checked
                                      @endif value="1">@lang('common.yes')</label>
                    </div>
                    <div class="radio-inline">
                        <label><input type="radio" name="internal" @if ($torrent->internal == 0) checked
                                      @endif value="0">@lang('common.no')</label>
                    </div>
                    <br>
                    <br>
                @else
                    <input type="hidden" name="internal" value="0">
                @endif
                <button type="submit" class="btn btn-primary">@lang('common.submit')</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
      $(document).ready(function () {
        $('#upload-form-description').wysibb({});
        emoji.textcomplete()
      })
    </script>
@endsection
