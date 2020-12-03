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
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('backup.backup')
                @lang('backup.manager')</span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <div class="box-body">
            <button id="create-full-backup-button" href="{{ route('staff.backups.full') }}"
                class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label"><i class="{{ config('other.font-awesome') }} fa-plus"></i>
                    @lang('backup.create_a_new_backup')</span>
            </button>
            <button id="create-files-backup-button" href="{{ route('staff.backups.files') }}"
                class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label"><i class="{{ config('other.font-awesome') }} fa-plus"></i>
                    @lang('backup.create_a_new_files_backup')</span>
            </button>
            <button id="create-db-backup-button" href="{{ route('staff.backups.database') }}"
                class="btn btn-primary" data-style="zoom-in">
                <span class="ladda-label"><i class="{{ config('other.font-awesome') }} fa-plus"></i>
                    @lang('backup.create_a_new_db_backup')</span>
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
                            <td>{{ \Carbon\Carbon::createFromTimeStamp($b['last_modified'])->formatLocalized('%d %B %Y, %H:%M') }}
                            </td>
                            <td class="text-right">{{ round((int) $b['file_size'] / 1048576, 2) . ' MB' }}</td>
                            <td class="text-right">
                                @if ($b['download'])
                                    <a class="btn btn-xs btn-success" href="{{ route('staff.backups.download') }}?disk={{ $b['disk'] }}&path={{ urlencode($b['file_path']) }}&file_name={{ urlencode($b['file_name']) }}">
                                        <i class="{{ config('other.font-awesome') }} fa-cloud-download"></i >@lang('backup.download')
                                    </a>
                                @endif
                                <a class="btn btn-xs btn-danger" data-button-type="delete" href="{{ route('staff.backups.destroy') }}?file_name={{ urlencode($b['file_name']) }}&disk={{ $b['disk'] }}">
                                    <i class="{{ config('other.font-awesome') }} fa-trash"></i> @lang('common.delete')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
        jQuery(document).ready(function($) {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // capture the Create new backup button
            $("#create-full-backup-button").click(function(e) {
                e.preventDefault();
              const create_backup_url = $(this).attr('href')
              // do the backup through ajax
                Toast.fire({
                    icon: 'success',
                    title: '@lang('backup.backup_process_started')'
                })
                $.ajax({
                    url: create_backup_url,
                    type: 'POST',
                    success: function(result) {
                        // Show an alert with the result
                        if (result.indexOf('failed') >= 0) {
                            Toast.fire({
                                icon: 'warning',
                                title: '@lang('backup.create_warning_message')'
                            })
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'success',
                                title: '@lang('backup.create_confirmation_message')'
                            })
                        }
                    },
                });
            });

            // capture the Create new backup button
            $("#create-files-backup-button").click(function(e) {
                e.preventDefault();
              const create_backup_url = $(this).attr('href')
              // do the backup through ajax
                Toast.fire({
                    icon: 'success',
                    title: '@lang('backup.backup_process_started')'
                })
                $.ajax({
                    url: create_backup_url,
                    type: 'POST',
                    success: function(result) {
                        // Show an alert with the result
                        if (result.indexOf('failed') >= 0) {
                            Toast.fire({
                                icon: 'warning',
                                title: '@lang('backup.create_warning_message')'
                            })
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'success',
                                title: '@lang('backup.create_confirmation_message')'
                            })
                        }
                    },
                });
            });

            // capture the Create new backup button
            $("#create-db-backup-button").click(function(e) {
                e.preventDefault();
              const create_backup_url = $(this).attr('href')
              // do the backup through ajax
                Toast.fire({
                    icon: 'success',
                    title: '@lang('backup.backup_process_started')'
                })
                $.ajax({
                    url: create_backup_url,
                    type: 'POST',
                    success: function(result) {
                        // Show an alert with the result
                        if (result.indexOf('failed') >= 0) {
                            Toast.fire({
                                icon: 'warning',
                                title: '@lang('backup.create_warning_message')'
                            })
                        }
                        else
                        {
                            Toast.fire({
                                icon: 'success',
                                title: '@lang('backup.create_confirmation_message')'
                            })
                        }
                    },
                });
            });

            // capture the delete button
            $("[data-button-type=delete]").click(function(e) {
                e.preventDefault();
              const delete_button = $(this)
              const delete_url = $(this).attr('href')
              if (confirm("@lang('backup.delete_confirm')") == true) {
                    $.ajax({
                        url: delete_url,
                        type: 'DELETE',
                        success: function(result) {
                            // Show an alert with the result
                            Toast.fire({
                                icon: 'success',
                                title: '@lang('backup.delete_confirmation_message')'
                            })
                            // delete the row from the table
                            delete_button.parentsUntil('tr').parent().remove();
                        },
                        error: function(result) {
                            // Show an alert with the result
                            Toast.fire({
                                icon: 'warning',
                                title: '@lang('backup.delete_error_title')'
                            })
                        }
                    });
                } else {
                    Toast.fire({
                        icon: 'warning',
                        title: '@lang('backup.delete_error_title')'
                    })
                }
            });
        });
    </script>
@endsection
