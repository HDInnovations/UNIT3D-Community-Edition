<div class="modal fade" id="vote" tabindex="-1" role="dialog">
    <div class="modal-dialog{{ modal_style() }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                <h2><i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i> {{ __('request.vote-that') }}!</h2>
            </div>
            <form role="form" method="POST" action="{{ route('add_votes', ['id' => $torrentRequest->id]) }}">
                @csrf
                <div class="modal-body text-center">
                    <p>{{ __('request.enter-bp') }}</p>
                    <fieldset>
                        <input type='hidden' tabindex='3' name='request_id' value='{{ $torrentRequest->id }}'>
                        <label>
                            <input class="form-control" type="number" tabindex="3" name='bonus_value' min='100'
                                   value="100">
                        </label>
                        <p>Anonymous Bounty?</p>
                        <div class="radio-inline">
                            <label><input type="radio" name="anon" value="1">{{ __('common.yes') }}</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="anon" value="0" checked>{{ __('common.no') }}</label>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal">{{ __('common.cancel') }}</button>
                        <button type="submit" @if ($user->seedbonus < 100) disabled
                                title="{{ __('request.dont-have-bps') }}"
                                @endif class="btn btn-success">{{ __('request.vote') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="fill" tabindex="-1" role="dialog">
    <div class="modal-dialog{{ modal_style() }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                <h2><i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i> {{ __('request.fill-request') }}!</h2>
            </div>
            <form role="form" method="POST" action="{{ route('fill_request', ['id' => $torrentRequest->id]) }}">
                @csrf
                <div class="modal-body text-center">
                    <p>{{ __('request.enter-hash') }}.</p>
                    <fieldset>
                        <input type='hidden' tabindex='3' name='request_id' value='{{ $torrentRequest->id }}'>
                        <label>
                            <input class="form-control" type="text" tabindex="3" name='info_hash'
                                   placeholder="{{ __('request.torrent-hash') }}">
                        </label>
                        <p>Anonymous Fill?</p>
                        <div class="radio-inline">
                            <label><input type="radio" name="filled_anon" value="1">{{ __('common.yes') }}</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="filled_anon" value="0" checked>{{ __('common.no') }}</label>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('request.fill') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="reset" tabindex="-1" role="dialog">
    <div class="modal-dialog{{ modal_style() }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                <h2><i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>{{ __('request.reset-request') }}!</h2>
            </div>
            <form role="form" method="POST" action="{{ route('resetRequest', ['id' => $torrentRequest->id]) }}">
                @csrf
                <div class="modal-body">
                    <p class="text-center">{{ __('request.reset-confirmation') }}?</p>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal">{{ __('common.cancel') }}</button>
                        <button type="submit"
                                @if (!$user->group->is_modo || $torrentRequest->filled_hash == null) disabled
                                @endif class="btn btn-warning">{{ __('request.reset') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="delete" tabindex="-1" role="dialog">
    <div class="modal-dialog{{ modal_style() }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                <h2><i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>{{ __('request.delete') }}</h2>
            </div>
            <form role="form" method="POST" action="{{ route('deleteRequest', ['id' => $torrentRequest->id]) }}">
                @csrf
                <div class="modal-body text-center">
                    <p>{{ __('request.delete-confirmation') }}?</p>
                    <p>{{ __('request.delete-filled') }}.</p>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal">{{ __('common.cancel') }}</button>
                        <button type="submit" @if ($torrentRequest->filled_hash != null) disabled
                                @endif class="btn btn-danger">{{ __('common.delete') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="claim" tabindex="-1" role="dialog">
    <div class="modal-dialog{{ modal_style() }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                            aria-hidden="true">&times;</span></button>
                <h2><i class="{{ config('other.font-awesome') }} fa-thumbs-up"></i>{{ __('request.claim') }}</h2>
            </div>
            <form role="form" method="POST" action="{{ route('claimRequest', ['id' => $torrentRequest->id]) }}">
                @csrf
                <div class="modal-body text-center">
                    <p>{{ __('request.claim-as-anon') }}</p>
                    <br>
                    <fieldset>
                        <p>{{ __('request.claim-anon-choose') }}</p>
                        <div class="radio-inline">
                            <label><input type="radio" name="anon" value="1">{{ __('common.yes') }}</label>
                        </div>
                        <div class="radio-inline">
                            <label><input type="radio" name="anon" value="0" checked>{{ __('common.no') }}</label>
                        </div>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary"
                                data-dismiss="modal">{{ __('common.cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ __('request.claim-now') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_request_report" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog{{ modal_style() }}">
        <div class="modal-content">
            <meta charset="utf-8">
            <title>{{ __('request.report') }}: {{ $torrentRequest->name }}</title>
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('common.close') }}"><span
                            aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ __('request.report') }}
                    : {{ $torrentRequest->name }}</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" role="form" method="POST"
                      action="{{ route('report_request', ['id' => $torrentRequest->id]) }}">
                    @csrf
                    <div class="form-group">
                        <label for="file_name" class="col-sm-2 control-label">{{ __('request.request') }}</label>
                        <div class="col-sm-10">
                            <input id="title" name="title" type="hidden" value="{{ $torrentRequest->name }}">
                            <p class="form-control-static">{{ $torrentRequest->name }}</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="report_reason" class="col-sm-2 control-label">{{ __('request.reason') }}</label>
                        <div class="col-sm-10">
                            <label for="message"></label>
                            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input class="btn btn-danger" type="submit" value="{{ __('request.report') }}">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal">{{ __('common.close') }}</button>
            </div>
        </div>
    </div>
</div>
