<div class="modal fade" id="modal_edit_subtitle-{{ $subtitle->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog{{ modal_style() }}">
        <div class="modal-content">
            <div class="container-fluid">
                <form role="form" method="POST" action="{{ route('subtitles.update', ['id' =>$subtitle->id]) }}">
                    @csrf
                    <input id="torrent_id" name="torrent_id" type="hidden" value="{{ $torrent->id }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">{{ __('common.edit') }} {{ __('common.subtitle') }}
                            - {{ $torrent->name }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="language_id" class="col-sm-2 control-label">{{ __('common.language') }}</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="language_id" name="language_id">
                                    <option value="{{ $subtitle->language_id }}"
                                            selected>{{ $subtitle->language->name }}
                                        ({{ __('torrent.current') }})
                                    </option>
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
                                <input class="form-control" name="note" type="text" id="note"
                                       value="{{ $subtitle->note }}">
                                <span class="help-block">{{ __('subtitle.note-help') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="text-center">
                            <button class="btn btn-sm btn-primary" type="button"
                                    data-dismiss="modal">{{ __('common.close') }}</button>
                            <input class="btn btn-success" type="submit" value="{{ __('common.save') }}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_delete_subtitle-{{ $subtitle->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog{{ modal_style() }}">
        <div class="modal-content">
            <div class="container-fluid">
                <form role="form" method="POST" action="{{ route('subtitles.destroy', ['id' => $subtitle->id]) }}">
                    @csrf
                    @method('DELETE')
                    <input id="torrent_id" name="torrent_id" type="hidden" value="{{ $torrent->id }}">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}">
                            <span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title text-center" id="myModalLabel">{{ __('subtitle.delete-confirm') }}</h4>
                    </div>
                    <div class="modal-body text-center">
                        <input class="btn btn-danger" type="submit"
                               value="{{ __('common.delete') }} - {{ $subtitle->language->name }} {{ __('common.subtitle') }}">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-sm btn-primary" type="button"
                                data-dismiss="modal">{{ __('common.close') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>