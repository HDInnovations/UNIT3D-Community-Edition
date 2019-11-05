@extends('layout.default')

@section('title')
    <title>@lang('backup.backup') @lang('backup.manager') - @lang('staff.staff-dashboard')
        - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="@lang('backup.backup') @lang('backup.manager') - @lang('staff.staff-dashboard')">
@endsection

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.backups.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title"
                  class="l-breadcrumb-item-link-title">@lang('backup.backup') @lang('backup.manager')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="box-body">
            <button id="create-full-backup-button" href="{{ route('staff.backups.full') }}" class="btn btn-primary ladda-button" data-style="zoom-in">
                <span class="ladda-label"><i class="{{ config('other.font-awesome') }} fa-plus"></i> @lang('backup.create_a_new_backup')</span>
            </button>
            <button id="create-files-backup-button" href="{{ route('staff.backups.files') }}" class="btn btn-primary ladda-button" data-style="zoom-in">
                <span class="ladda-label"><i class="{{ config('other.font-awesome') }} fa-plus"></i> @lang('backup.create_a_new_files_backup')</span>
            </button>
            <button id="create-db-backup-button" href="{{ route('staff.backups.database') }}" class="btn btn-primary ladda-button" data-style="zoom-in">
                <span class="ladda-label"><i class="{{ config('other.font-awesome') }} fa-plus"></i> @lang('backup.create_a_new_db_backup')</span>
            </button>
            <br>
            <h3>@lang('backup.existing_backups'):</h3>
            <table class="table table-hover table-condensed">
                <thead>
                <tr>
                    <th>#</th>
                    <th>@lang('backup.location')</th>
                    <th>@lang('backup.date')</th>
                    <th class="text-right">@lang('backup.file_size')</th>
                    <th class="text-right">@lang('backup.actions')</th>
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
                                <a class="btn btn-xs btn-default"
                                   href="{{ route('staff.backups.download') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}"><i
                                            class="{{ config('other.font-awesome') }} fa-cloud-download"></i> @lang('backup.download')</a>
                            @endif
                            <a class="btn btn-xs btn-danger" data-disk="{{ $b['disk'] }}" data-file="{{ $b['file_name'] }}" data-button-type="delete"
                               href="{{ route('staff.backups.destroy') }}"><i
                                        class="{{ config('other.font-awesome') }} fa-trash"></i> @lang('backup.delete')</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce() }}">
        jQuery(document).ready(function ($) {

            // capture the Create full backup button
            $("#create-full-backup-button").click(function (e) {
                e.preventDefault();
                var create_backup_url = $(this).attr('href');
                // Create a new instance of ladda for the specified button
                var l = Ladda.create(document.querySelector('#create-full-backup-button'));

                // Start loading
                l.start();

                // Will display a progress bar for 10% of the button width
                l.setProgress(0.3);

                setTimeout(function () {
                    l.setProgress(0.6);
                }, 2000);

                // do the backup through ajax
                $.ajax({
                    url: create_backup_url,
                    data: {_token: '{{csrf_token()}}'},
                    type: 'POST',
                    success: function (result) {
                        l.setProgress(0.9);
                        // Show an alert with the result
                        if (result.indexOf('failed') >= 0) {
                          const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });

                          Toast.fire({
                            type: 'warning',
                            title:'@lang('backup.create_warning_message')'
                          })
                        }
                        else {
                          const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });

                          Toast.fire({
                            type: 'success',
                            title:'@lang('backup.create_confirmation_message')'
                          })
                        }

                        // Stop loading
                        l.setProgress(1);
                        l.stop();

                        // refresh the page to show the new file
                        setTimeout(function () {
                            location.reload();
                        }, 3000);
                    },
                    error: function (result) {
                        l.setProgress(0.9);
                        // Show an alert with the result
                      const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                      });

                      Toast.fire({
                        type: 'warning',
                        title:'@lang('backup.create_error_message')'
                      })
                        // Stop loading
                        l.stop();
                    }
                });
            });

          // capture the Create files backup button
          $("#create-files-backup-button").click(function (e) {
            e.preventDefault();
            var create_backup_url = $(this).attr('href');
            // Create a new instance of ladda for the specified button
            var l = Ladda.create(document.querySelector('#create-files-backup-button'));

            // Start loading
            l.start();

            // Will display a progress bar for 10% of the button width
            l.setProgress(0.3);

            setTimeout(function () {
              l.setProgress(0.6);
            }, 2000);

            // do the backup through ajax
            $.ajax({
              url: create_backup_url,
              data: {_token: '{{csrf_token()}}'},
              type: 'POST',
              success: function (result) {
                l.setProgress(0.9);
                // Show an alert with the result
                if (result.indexOf('failed') >= 0) {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  });

                  Toast.fire({
                    type: 'warning',
                    title:'@lang('backup.create_warning_message')'
                  })
                }
                else {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  });

                  Toast.fire({
                    type: 'success',
                    title:'@lang('backup.create_confirmation_message')'
                  })
                }

                // Stop loading
                l.setProgress(1);
                l.stop();

                // refresh the page to show the new file
                setTimeout(function () {
                  location.reload();
                }, 3000);
              },
              error: function (result) {
                l.setProgress(0.9);
                // Show an alert with the result
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
                });

                Toast.fire({
                  type: 'warning',
                  title:'@lang('backup.create_error_message')'
                })
                // Stop loading
                l.stop();
              }
            });
          });

          // capture the Create db backup button
          $("#create-db-backup-button").click(function (e) {
            e.preventDefault();
            var create_backup_url = $(this).attr('href');
            // Create a new instance of ladda for the specified button
            var l = Ladda.create(document.querySelector('#create-db-backup-button'));

            // Start loading
            l.start();

            // Will display a progress bar for 10% of the button width
            l.setProgress(0.3);

            setTimeout(function () {
              l.setProgress(0.6);
            }, 2000);

            // do the backup through ajax
            $.ajax({
              url: create_backup_url,
              data: {_token: '{{csrf_token()}}'},
              type: 'POST',
              success: function (result) {
                l.setProgress(0.9);
                // Show an alert with the result
                if (result.indexOf('failed') >= 0) {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  });

                  Toast.fire({
                    type: 'warning',
                    title:'@lang('backup.create_warning_message')'
                  })
                }
                else {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  });

                  Toast.fire({
                    type: 'success',
                    title:'@lang('backup.create_confirmation_message')'
                  })
                }

                // Stop loading
                l.setProgress(1);
                l.stop();

                // refresh the page to show the new file
                setTimeout(function () {
                  location.reload();
                }, 3000);
              },
              error: function (result) {
                l.setProgress(0.9);
                // Show an alert with the result
                const Toast = Swal.mixin({
                  toast: true,
                  position: 'top-end',
                  showConfirmButton: false,
                  timer: 3000
                });

                Toast.fire({
                  type: 'warning',
                  title:'@lang('backup.create_error_message')'
                })
                // Stop loading
                l.stop();
              }
            });
          });

            // capture the delete button
            $("[data-button-type=delete]").click(function (e) {
                e.preventDefault();
                var delete_button = $(this);
                var delete_url = $(this).attr('href');
                var disk = $(this).attr('data-disk');
                var file = $(this).attr('data-file');

                if (confirm("@lang('backup.delete_confirm')") == true) {
                    $.ajax({
                        url: delete_url,
                        data: {_token: '{{csrf_token()}}', disk: disk, file_name: file },
                        type: 'POST',
                        success: function (result) {
                            // Show an alert with the result
                          const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });

                          Toast.fire({
                            type: 'success',
                            title:'@lang('backup.delete_confirmation_message')'
                          })
                            // delete the row from the table
                            delete_button.parentsUntil('tr').parent().remove();
                        },
                        error: function (result) {
                          // Show an alert with the result
                          const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000
                          });

                          Toast.fire({
                            type: 'warning',
                            title:'@lang('backup.delete_error_title')'
                          })
                        }
                    });
                } else {
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  });

                  Toast.fire({
                    type: 'info',
                    title:'@lang('backup.delete_cancel_message')'
                  })
                }
            });

        });
    </script>
@endsection
