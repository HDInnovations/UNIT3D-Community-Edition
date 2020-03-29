@extends('layout.default')

@section('title')
    <title>Upload Subtitle - {{ config('other.title') }}</title>
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('subtitles.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('torrent.torrents')</span>
        </a>
    </li>
    <li>
        <a href="{{ route('subtitles.create', ['torrent_id' => $torrent->id]) }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('common.upload') Subtitle</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container">
        <h2 class="upload-title">
            Upload Subtitle for - {{ $torrent->name }}
        </h2>
        <div class="well">
            <h2 class="text-center">Subtitle Rules!</h2>
            <ul>
                <li>Only proper subtitles are allowed to be uploaded (Correct frame rate. translation, spelling, timing).</li>
                <li>No google translated / machine translated / incorrect subtitles allowed.</li>
                <li>Subtitle must be in sync with the video.</li>
                <li>.srt only allowed.</li>
                <li>Repeated uploads of junk sub will constitute a violation and subject to disciplinary action.</li>
                <li>Keep the note of the subtitle short. NO urls/links are allowed.</li>
                <li>All Subtitles must be confirmed, verified, timed correctly for the specific Torrent/Video.</li>
            </ul>
        </div>
        <div class="block">
            <form method="POST" action="{{ route('subtitles.store') }}" accept-charset="UTF-8" id="form_upload_subtitle" class="form-horizontal" role="form" enctype="multipart/form-data">
                @csrf
                <input name="torrent_id" type="hidden" value="{{ $torrent->id }}">
                <div class="form-group">
                    <label for="torrent_id" class="col-sm-2 control-label">Torrent</label>
                    <div class="col-sm-9">
                        <p class="form-control-static">
                            <a href="{{ route('torrent', ['id' => $torrent->id]) }}" title="{{ $torrent->name }}">{{ $torrent->name }}</a>
                        </p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="subtitle_file" class="col-sm-2 control-label">Subtitle File</label>
                    <div class="col-sm-9">
                        <input class="form-control" accept=".srt" name="subtitle_file" type="file" id="subtitle_file">
                        <span class="help-block">Accepted files are SRT</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="language_id" class="col-sm-2 control-label">Language</label>
                    <div class="col-sm-9">
                        <select class="form-control" id="language_id" name="language_id">
                            @foreach ($media_languages as $media_language)
                                <option value="{{ $media_language->id }}">{{ $media_language->name }} ({{ $media_language->code }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description" class="col-sm-2 control-label">Note</label>
                    <div class="col-sm-9">
                        <input class="form-control" name="note" type="text" id="note">
                        <span class="help-block">Extra Info for this subtitle</span>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-2">
                        <input class="btn btn-primary" type="submit" value="Upload">
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
