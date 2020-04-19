<div class="modal fade" id="modal_edit_subtitle-{{ $subtitle->id }}" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dark">
		<div class="modal-content">
			<div class="container-fluid">
				<form role="form" method="POST" action="{{ route('subtitles.update', ['id' =>$subtitle->id]) }}">
					@csrf
					<input id="torrent_id" name="torrent_id" type="hidden" value="{{ $torrent->id }}">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Edit Subtitle For - {{ $torrent->name }}</h4>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label for="language_id" class="col-sm-2 control-label">Language</label>
							<div class="col-sm-9">
								<select class="form-control" id="language_id" name="language_id">
									<option value="{{ $subtitle->language_id }}" selected>{{ $subtitle->language->name }}
										(@lang('torrent.current'))
									</option>
									@foreach ($media_languages as $media_language)
										<option value="{{ $media_language->id }}">{{ $media_language->name }} ({{ $media_language->code }})</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group">
							<label for="description" class="col-sm-2 control-label">Note</label>
							<div class="col-sm-9">
								<input class="form-control" name="note" type="text" id="note" value="{{ $subtitle->note }}">
								<span class="help-block">Extra Info for this subtitle</span>
							</div>
						</div>
						<div class="form-group">
							<input class="btn btn-primary" type="submit" value="Save">
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal_delete_subtitle-{{ $subtitle->id }}" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-dark">
		<div class="modal-content">
			<div class="container-fluid">
				<form role="form" method="POST" action="{{ route('subtitles.destroy', ['id' => $subtitle->id]) }}">
					@csrf
					@method('DELETE')
					<input id="torrent_id" name="torrent_id" type="hidden" value="{{ $torrent->id }}">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">×</span>
						</button>
						<h4 class="modal-title" id="myModalLabel">Are You Sure You Want To Delete This?</h4>
					</div>
					<div class="modal-body text-center">
						<div class="form-group">
							<input class="btn btn-primary" type="submit" value="Delete - {{ $subtitle->language->name }} Subtitle">
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>