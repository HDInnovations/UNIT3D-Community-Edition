{{-- Report Modal --}}
<div class="modal fade" id="modal_torrent_report" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <meta charset="utf-8">
      <title>{{ trans('common.report') }} {{ strtolower(trans('torrent.torrent')) }}: {{ $torrent->name }}</title>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('common.close') }}"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('common.report') }} {{ strtolower(trans('torrent.torrent')) }}: {{ $torrent->name }}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <form class="form-horizontal" role="form" method="POST" action="{{ route('postReport') }}">
            {{ csrf_field() }}
          <input id="type" name="type" type="hidden" value="Torrent">
          <label for="file_name" class="col-sm-2 control-label">{{ trans('torrent.torrent') }}</label>
          <div class="col-sm-10">
            <input id="title" name="title" type="hidden" value="{{ $torrent->name }}">
            <p class="form-control-static">{{ $torrent->name }}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="report_reason" class="col-sm-2 control-label">{{ trans('common.reason') }}</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 col-sm-offset-2">
            <input class="btn btn-danger" type="submit" value="{{ trans('common.report') }}">
          </div>
        </form>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">{{ trans('common.close') }}</button>
      </div>
    </div>
  </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="modal_torrent_delete" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <meta charset="utf-8">
      <title>{{ trans('common.delete') }} {{ strtolower(trans('torrent.torrent')) }}: {{ $torrent->name }}</title>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('common.close') }}"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title" id="myModalLabel">{{ trans('common.delete') }} {{ strtolower(trans('torrent.torrent')) }}: {{ $torrent->name }}</h4>
      </div>
      <div class="modal-body">
        <div class="form-group">
          {{ Form::open(['route' => ['delete'] , 'method' => 'post']) }}
          <input id="type" name="type" type="hidden" value="Torrent">
          <input id="id" name="id" type="hidden" value="{{ $torrent->id }}">
          <input id="slug" name="slug" type="hidden" value="{{ $torrent->slug }}">
          <label for="file_name" class="col-sm-2 control-label">{{ trans('torrent.torrent') }}</label>
          <div class="col-sm-10">
            <input id="title" name="title" type="hidden" value="{{ $torrent->name }}">
            <p class="form-control-static">{{ $torrent->name }}</p>
          </div>
        </div>
        <div class="form-group">
          <label for="report_reason" class="col-sm-2 control-label">{{ trans('common.reason') }}</label>
          <div class="col-sm-10">
            <textarea class="form-control" rows="5" name="message" cols="50" id="message"></textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-10 col-sm-offset-2">
            <input class="btn btn-danger" type="submit" value="{{ trans('common.delete') }}">
          </div>
        {{ Form::close() }}
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">{{ trans('common.close') }}</button>
      </div>
    </div>
  </div>
</div>

{{-- Files Modal --}}
  <div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myModalLabel">{{ trans('common.files') }}</h4>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
          <table class="table table-striped table-condensed">
            <thead>
              <tr>
                <th>#</th>
                <th>{{ trans('common.name') }}</th>
                <th>{{ trans('torrent.size') }}</th>
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
          <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('common.close') }}</button>
        </div>
      </div>
    </div>
  </div>

{{-- NFO Modal --}}
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
        <button class="btn btn-info" data-dismiss="modal">{{ trans('common.close') }}</button>
      </div>
    </div>
  </div>
</div>
@endif
