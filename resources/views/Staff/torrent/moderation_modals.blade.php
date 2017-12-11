{{-- Torrent Reject Modal --}}
<div class="modal fade" id="modal_torrent_reject" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <meta charset="utf-8">
      <title>Reject Torrent: {{ $pending->name }}</title>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Reject Torrent: {{ $pending->name }}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
        {{ Form::open(array('route' => array('moderation_reject', ['slug' => $pending->slug, 'id' => $pending->id]))) }}
          <input id="type" name="type" type="hidden" value="Torrent">
          <label for="file_name" class="col-sm-2 control-label">Torrent</label>
          <div class="col-sm-10">
            <input id="title" name="title" type="hidden" value="{{ $pending->name }}">
            <p class="form-control-static">{{ $pending->name }}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="report_reason" class="col-sm-2 control-label">Reason (Sent To Uploader Via PM)</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 col-sm-offset-2">
            <input class="btn btn-danger" type="submit" value="Report">
          </div>
        {{ Form::close() }}
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
