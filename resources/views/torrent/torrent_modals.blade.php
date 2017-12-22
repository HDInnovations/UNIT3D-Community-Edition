{{-- Report Modal --}}
<div class="modal fade" id="modal_torrent_report" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <meta charset="utf-8">
      <title>Report Torrent: {{ $torrent->name }}</title>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Report Torrent: {{ $torrent->name }}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('postReport') }}">
            {{ csrf_field() }}
          <input id="type" name="type" type="hidden" value="Torrent">
          <label for="file_name" class="col-sm-2 control-label">Torrent</label>
          <div class="col-sm-10">
            <input id="title" name="title" type="hidden" value="{{ $torrent->name }}">
            <p class="form-control-static">{{ $torrent->name }}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="report_reason" class="col-sm-2 control-label">Reason</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 col-sm-offset-2">
            <input class="btn btn-danger" type="submit" value="Report">
          </div>
        </form>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="modal_torrent_delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <meta charset="utf-8">
      <title>Delete Torrent: {{ $torrent->name }}</title>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">Delete Torrent: {{ $torrent->name }}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('delete', array('id' => $torrent->id)) }}">
            {{ csrf_field() }}
          <input id="type" name="type" type="hidden" value="Torrent">
          <label for="file_name" class="col-sm-2 control-label">Torrent</label>
          <div class="col-sm-10">
            <input id="title" name="title" type="hidden" value="{{ $torrent->name }}">
            <p class="form-control-static">{{ $torrent->name }}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="report_reason" class="col-sm-2 control-label">Reason (sent to uploader)</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 col-sm-offset-2">
            <input class="btn btn-danger" type="submit" value="Delete">
          </div>
        </form>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Files Modal -->
  <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myModalLabel">Files</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('common.size') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($torrent->files as $k => $f)
              <tr>
                <td>{{ $k + 1 }}</td>
                <td>{{ $f->name }}</td>
                <td>{{ $f->getSize() }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

<!-- NFO Modal -->
@if($torrent->nfo != null)
<div class="modal fade slideExpandUp" id="modal-10" role="dialog" aria-labelledby="Modallabel3dsign">
  <div class="modal-dialog" role="document">
    <div class="modal-content ">
      <div class="modal-header bg-info ">
        <h4 class="modal-title" id="Modallabel3dsign">NFO</h4>
      </div>
      <div class="modal-body">
        <pre class="torrent-bottom-nfo">
            {{ $torrent->nfo }}
        </pre>
      </div>
      <div class="modal-footer">
        <button class="btn btn-info" data-dismiss="modal">Close me! </button>
      </div>
    </div>
  </div>
</div>
@endif
<!-- /NFO Modal -->
