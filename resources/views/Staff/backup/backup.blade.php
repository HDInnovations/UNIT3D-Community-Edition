@extends('layout.default')

@section('title')
	<title>{{ trans('backup.backup') }} {{ trans('backup.manager') }} - Staff Dashboard - {{ config('other.title') }}</title>
@endsection

@section('meta')
	<meta name="description" content="{{ trans('backup.backup') }} {{ trans('backup.manager') }} - Staff Dashboard">
@endsection

@section('breadcrumb')
<li>
  <a href="{{ route('staff_dashboard') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">Staff Dashboard</span>
  </a>
</li>
<li class="active">
  <a href="{{ route('backupManager') }}" itemprop="url" class="l-breadcrumb-item-link">
    <span itemprop="title" class="l-breadcrumb-item-link-title">{{ trans('backup.backup') }} {{ trans('backup.manager') }}</span>
  </a>
</li>
@endsection

@section('content')
  <div class="container box">
    <div class="box-body">
    <button id="create-new-backup-button" href="{{ url('staff_dashboard/backup/create') }}" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i> {{ trans('backup.create_a_new_backup') }}</span></button>
      <br>
      <h3>{{ trans('backup.existing_backups') }}:</h3>
      <table class="table table-hover table-condensed">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ trans('backup.location') }}</th>
            <th>{{ trans('backup.date') }}</th>
            <th class="text-right">{{ trans('backup.file_size') }}</th>
            <th class="text-right">{{ trans('backup.actions') }}</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($backups as $k => $b)
          <tr>
            <th scope="row">{{ ++$k }}</th>
            <td>{{ $b['disk'] }}</td>
            <td>{{ \Carbon\Carbon::createFromTimeStamp($b['last_modified'])->formatLocalized('%d %B %Y, %H:%M') }}</td>
            <td class="text-right">{{ round((int)$b['file_size']/1048576, 2).' MB' }}</td>
            <td class="text-right">
                @if ($b['download'])
                <a class="btn btn-xs btn-default" href="{{ url('staff_dashboard/backup/download/') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i class="fa fa-cloud-download"></i> {{ trans('backup.download') }}</a>
                @endif
                <a class="btn btn-xs btn-danger" data-button-type="delete" href="{{ url('staff_dashboard/backup/delete/'.$b['file_name']) }}?disk={{ $b['disk'] }}"><i class="fa fa-trash-o"></i> {{ trans('backup.delete') }}</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('javascripts')
<script>
  jQuery(document).ready(function($) {

    // capture the Create new backup button
    $("#create-new-backup-button").click(function(e) {
        e.preventDefault();
        var create_backup_url = $(this).attr('href');
        // Create a new instance of ladda for the specified button
        var l = Ladda.create( document.querySelector( '#create-new-backup-button' ) );

        // Start loading
        l.start();

        // Will display a progress bar for 10% of the button width
        l.setProgress( 0.3 );

        setTimeout(function(){ l.setProgress( 0.6 ); }, 2000);

        // do the backup through ajax
        $.ajax({
                url: create_backup_url,
                data: { _token: '{{csrf_token()}}' },
                type: 'POST',
                success: function(result) {
                    l.setProgress( 0.9 );
                    // Show an alert with the result
                    if (result.indexOf('failed') >= 0) {
                        toastr.warning("{{ trans('backup.create_warning_title') }}", "{{ trans('backup.create_warning_message') }}");
                    }
                    else
                    {
                        toastr.success("{{ trans('backup.create_confirmation_title') }}", "{{ trans('backup.create_confirmation_message') }}");
                    }

                    // Stop loading
                    l.setProgress( 1 );
                    l.stop();

                    // refresh the page to show the new file
                    setTimeout(function(){ location.reload(); }, 3000);
                },
                error: function(result) {
                    l.setProgress( 0.9 );
                    // Show an alert with the result
                    toastr.warning("{{ trans('backup.create_error_title') }}", "{{ trans('backup.create_error_message') }}");
                    // Stop loading
                    l.stop();
                }
            });
    });

    // capture the delete button
    $("[data-button-type=delete]").click(function(e) {
        e.preventDefault();
        var delete_button = $(this);
        var delete_url = $(this).attr('href');

        if (confirm("{{ trans('backup.delete_confirm') }}") == true) {
            $.ajax({
                url: delete_url,
                data: { _token: '{{csrf_token()}}' },
                type: 'POST',
                success: function(result) {
                    // Show an alert with the result
                    toastr.success("{{ trans('backup.delete_confirmation_title') }}", "{{ trans('backup.delete_confirmation_message') }}");
                    // delete the row from the table
                    delete_button.parentsUntil('tr').parent().remove();
                },
                error: function(result) {
                    // Show an alert with the result
                    toastr.warning("{{ trans('backup.delete_error_message') }}", "{{ trans('backup.delete_error_title') }}");
                }
            });
        } else {
            toastr.info("{{ trans('backup.delete_cancel_title') }}", "{{ trans('backup.delete_cancel_message') }}");
        }
      });

  });
</script>
@endsection
