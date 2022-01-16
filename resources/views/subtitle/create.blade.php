@extends('layout.default')

@section('title')
    <title>{{ __('common.upload') }} {{ __('common.subtitle') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('subtitles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">{{ __('torrent.torrents') }}</span>
        </a>
    </li>
    <li>
        <a href="{{ route('subtitles.create', ['torrent_id' => $torrent->id]) }}" itemprop="url"
           class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">{{ __('common.upload') }} {{ __('common.subtitle') }}</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <h2 class="upload-title">
            {{ __('common.upload') }} {{ __('common.subtitle') }} - {{ $torrent->name }}
        </h2>
        <div class="well">
            <h2 class="text-center">{{ __('subtitle.rules-title') }}</h2>
            {{ __('subtitle.rules') }}
        </div>
        <div class="block">
            <form method="POST" action="{{ route('subtitles.store') }}" id="form_upload_subtitle"
                  class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <input name="torrent_id" type="hidden" value="{{ $torrent->id }}">
                <input name="torrent_name" type="hidden" value="{{ $torrent->name }}">
                <div class="form-group">
                    <label for="torrent_id" class="col-sm-2 control-label">{{ __('torrent.torrent') }}</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                            <a href="{{ route('torrent', ['id' => $torrent->id]) }}"
                               title="{{ $torrent->name }}">{{ $torrent->name }}</a>
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="subtitle_file" class="col-sm-2 control-label">{{ __('subtitle.subtitle-file') }}</label>
                    <div class="col-sm-9">
                        <input class="form-control" name="subtitle_file" accept=".srt,.ass,.sup,.zip" type="file"
                               id="subtitle_file">
                        <span class="help-block">{{ __('subtitle.subtitle-file-types') }}</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language_id" class="col-sm-2 control-label">{{ __('common.language') }}</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="language_id" name="language_id">
                            @foreach ($media_languages as $media_language)
                                <option value="{{ $media_language->id }}">{{ $media_language->name }}
                                    ({{ $media_language->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">{{ __('subtitle.note') }}</label>
                    <div class="col-sm-9">
                        <input class="form-control" name="note" type="text" id="note">
                        <span class="help-block">{{ __('subtitle.note-help') }}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="anonymous" class="col-sm-2 control-label">{{ __('common.anonymous') }}?</label>
                    <div class="col-sm-9">
                        <div class="radio-inline">
                            <input type="radio" name="anonymous" value="1">{{ __('common.yes') }}
                        </div>
                        <div class="radio-inline">
                            <input type="radio" name="anonymous" checked="checked" value="0">{{ __('common.no') }}
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="{{ __('common.upload') }}">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
