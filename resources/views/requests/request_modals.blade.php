{{-- Vote Modal --}}
<div class="modal fade" id="vote" tabindex="-1" role="dialog" aria-labelledby="vote">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('common.close') }}"><span aria-hidden="true">&times;</span></button>
				<h2><i class="fa fa-thumbs-up"></i> {{ trans('request.vote-that') }}!</h2>
			</div>
			<form role="form" method="POST" action="{{ route('add_votes',['id' => $torrentRequest->id]) }}">
			{{ csrf_field() }}
			<div class="modal-body">
				<p class="text-center">{{ trans('request.enter-bp') }}.</p>
					<fieldset>
						<input type='hidden' tabindex='3' name='request_id' value='{{ $torrentRequest->id }}'>
						<input type="number" tabindex="3" name='bonus_value' min='100' value="100">
    				</fieldset>
					<br>
					<div class="btns">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
						<button type="submit" @if($user->seedbonus < 100) disabled title='{{ trans('request.dont-have-bps') }}'@endif class="btn btn-success">{{ trans('request.vote') }}</button>
					</div>
			</div>
		</form>
		</div>
	</div>
</div>

{{-- Fulfill Modal --}}
<div class="modal fade" id="fill" tabindex="-1" role="dialog" aria-labelledby="fill">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('common.close') }}"><span aria-hidden="true">&times;</span></button>
				<h2><i class="fa fa-thumbs-up"></i> {{ trans('request.fill-request') }}!</h2>
			</div>
			<form role="form" method="POST" action="{{ route('fill_request',['id' => $torrentRequest->id]) }}">
			{{ csrf_field() }}
			<div class="modal-body">
				<p class="text-center">{{ trans('request.enter-hash') }}.</p>
					<fieldset>
						<input type='hidden' tabindex='3' name='request_id' value='{{ $torrentRequest->id }}'>
      					<input type="text" tabindex="3" name='info_hash' placeholder="{{ trans('request.torrent-hash') }}">
    				</fieldset>
					<br>
					<div class="btns">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
						<button type="submit" class="btn btn-success">{{ trans('request.fill') }}</button>
					</div>
			</div>
		</form>
		</div>
	</div>
</div>

{{-- Reset Modal --}}
<div class="modal fade" id="reset" tabindex="-1" role="dialog" aria-labelledby="reset">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('common.close') }}"><span aria-hidden="true">&times;</span></button>
				<h2><i class="fa fa-thumbs-up"></i>{{ trans('request.reset-request') }}!</h2>
			</div>
			<form role="form" method="POST" action="{{ route('resetRequest',['id' => $torrentRequest->id]) }}">
			{{ csrf_field() }}
			<div class="modal-body">
				<p class="text-center">{{ trans('request.reset-confirmation') }}?</p>
					<div class="btns">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
						<button type="submit" @if(!$user->group->is_modo || $torrentRequest->filled_hash == null) disabled @endif class="btn btn-warning">{{ trans('request.reset') }}</button>
					</div>
			</div>
		</form>
		</div>
	</div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="delete">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('common.close') }}"><span aria-hidden="true">&times;</span></button>
				<h2><i class="fa fa-thumbs-up"></i>{{ trans('request.delete') }}</h2>
			</div>
			<form role="form" method="POST" action="{{ route('deleteRequest',['id' => $torrentRequest->id]) }}">
			{{ csrf_field() }}
			<div class="modal-body">
				<p class="text-center">{{ trans('request.delete-confirmation') }}?</p>
					<fieldset>
						<p>{{ trans('request.delete-filled') }}.</p>
					</fieldset>
					<div class="btns">
						<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
						<button type="submit" @if($torrentRequest->filled_hash != null) disabled @endif class="btn btn-warning">{{ trans('common.delete') }}</button>
					</div>
			</div>
		</form>
		</div>
	</div>
</div>

{{-- Claim Modal --}}
<div class="modal fade" id="claim" tabindex="-1" role="dialog" aria-labelledby="claim">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('common.close') }}"><span aria-hidden="true">&times;</span></button>
        <h2><i class="fa fa-thumbs-up"></i>{{ trans('request.claim') }}</h2>
      </div>
	  <form role="form" method="POST" action="{{ route('claimRequest',['id' => $torrentRequest->id]) }}">
	  {{ csrf_field() }}
      <div class="modal-body">
        <p class="text-center">{{ trans('request.claim-as-anon') }}?</p>
        <br>
          <fieldset>
            <p>{{ trans('request.claim-anon-choose') }}</p>
            <div class="radio-inline">
                <label><input type="radio" name="anon" value="1">{{ trans('request.yes') }}</label>
              </div>
            <div class="radio-inline">
                <label><input type="radio" name="anon" value="0" checked>{{ trans('request.no') }}</label>
            </div>
          </fieldset>
          <br>
          <center>
          <div class="btns">
            <button type="submit" class="btn btn-success">{{ trans('request.claim-now') }}!</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.cancel') }}</button>
          </div>
        </center>
      </div>
  </form>
    </div>
  </div>
</div>

{{-- Report Modal --}}
<div class="modal fade" id="modal_request_report" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <meta charset="utf-8">
      <title>{{ trans('request.report') }}: {{ $torrentRequest->name }}</title>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('common.close') }}"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('request.report') }}: {{ $torrentRequest->name }}</h4>
      </div>
      <div class="modal-body">
        <form class="form-horizontal" role="form" method="POST" action="{{ route('postReport') }}">
          {{ csrf_field() }}
        <div class="form-group">
          <input id="type" name="type" type="hidden" value="Request">
          <label for="file_name" class="col-sm-2 control-label">{{ trans('request.request') }}</label>
          <div class="col-sm-10">
            <input id="title" name="title" type="hidden" value="{{ $torrentRequest->name }}">
            <p class="form-control-static">{{ $torrentRequest->name }}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="report_reason" class="col-sm-2 control-label">{{ trans('request.reason') }}</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 col-sm-offset-2">
            <input class="btn btn-danger" type="submit" value="{{ trans('request.report') }}">
          </div>
        </div>
      </form>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">{{ trans('common.close') }}</button>
      </div>
    </div>
  </div>
</div>
