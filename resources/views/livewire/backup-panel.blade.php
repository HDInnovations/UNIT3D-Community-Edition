<div style="display: flex; flex-direction: column; gap: 1rem;">
    <section class="panelV2" x-data>
        <header class="panel__header">
            <h2 class="panel__heading">UNIT3D Backup Manager</h2>
            <div class="panel__actions">
                <button id="create-backup" class="panel__action form__button form__button--text" x-on:click="backupFun()">
                    {{ __('backup.create_a_new_backup') }}
                </button>
                <a class="panel__action form__button form__button--text" id="create-backup-only-db"
                    wire:click.prevent="">
                    {{ __('backup.create_a_new_db_backup') }}
                </a>
                <a class="panel__action form__button form__button--text" id="create-backup-only-files"
                    wire:click.prevent="">
                    {{ __('backup.create_a_new_files_backup') }}
                </a>
                <button
                    class="form__contained-icon-button form__contained-icon-button--filled"
                    wire:loading.attr="disabled"
                    wire:click="updateBackupStatuses"
                >
                    <i class="{{ config('other.font-awesome') }} fa-sync" wire:loading.class="fa-spin"></i>
                </button>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th scope="col">Disk</th>
                    <th scope="col">Healthy</th>
                    <th scope="col">Amount of backups</th>
                    <th scope="col">Newest backup</th>
                    <th scope="col">Used storage</th>
                </tr>
                </thead>
                <tbody>
                @foreach($backupStatuses as $backupStatus)
                    <tr>
                        <td>{{ $backupStatus['disk'] }}</td>
                        <td>
                            @if($backupStatus['healthy'])
                                <i class="{{ config('other.font-awesome') }} fa-check-circle text-success"></i>
                            @else
                                <i class="{{ config('other.font-awesome') }} fa-times-circle text-danger"></i>
                            @endif
                        </td>
                        <td>{{ $backupStatus['amount'] }}</td>
                        <td>{{ $backupStatus['newest'] }}</td>
                        <td>{{ $backupStatus['usedStorage'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">
            {{ __('backup.existing_backups') }}
        </h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                <tr>
                    <th scope="col">{{ __('backup.location') }}</th>
                    <th scope="col">{{ __('backup.date') }}</th>
                    <th scope="col">{{ __('backup.file_size') }}</th>
                    <th scope="col">{{ __('backup.actions') }}</th>
                </tr>
                </thead>
                <tbody>
                @forelse($files as $file)
                    <tr>
                        <td>{{ $file['path'] }}</td>
                        <td>{{ $file['date'] }}</td>
                        <td>{{ $file['size'] }}</td>
                        <td>
                            <menu class="data-table__actions">
                                <li class="data-table__action">
                                    <a
                                        class="form__button form__button--text"
                                        target="_blank"
                                        wire:click.prevent="downloadFile('{{ $file['path'] }}')"
                                    >
                                        {{ __('common.download') }}
                                    </a>
                                </li>
                                <li class="data-table__action">
                                    <a
                                        class="form__button form__button--text"
                                        href="#"
                                        target="_blank"
                                        wire:click.prevent="showDeleteModal({{ $loop->index }})"
                                    >
                                        {{ __('common.delete') }}
                                    </a>
                                </li>
                            </menu>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No backups present</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h5 class="modal-title mb-3">Delete backup</h5>
                        @if($deletingFile)
                            <span class="text-muted">
                                Are you sure you want to delete the backup created at {{ $deletingFile['date'] }} ?
                            </span>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary cancel-button" data-dismiss="modal">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger delete-button" wire:click="deleteFile">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
      document.addEventListener('livewire:load', function () {
        const Toast = Swal.mixin({
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        })
        @this.updateBackupStatuses()
        @this.on('backupStatusesUpdated', function () {
        @this.getFiles()
        })
        @this.on('showErrorToast', function (message) {
          Toast.fire({
            text: message,
            duration: 10000,
            gravity: 'bottom',
            position: 'right',
            backgroundColor: 'red',
            className: 'toastify-custom',
          })
        })
        const backupFun = function (option = '') {
          Swal.fire({
            title: '<strong style=" color: rgb(17,17,17);">Success</strong>',
            icon: 'success',
            html: 'Creating a new backup in the background...' + (option ? ' (' + option + ')' : ''),
            showCloseButton: true,
          })
        @this.createBackup(option)
        }
        $('#create-backup').on('click', function () {
          backupFun()
        })
        $('#create-backup-only-db').on('click', function () {
          backupFun('only-db')
        })
        $('#create-backup-only-files').on('click', function () {
          backupFun('only-files')
        })
        const deleteModal = $('#deleteModal')
      @this.on('showDeleteModal', function () {
        deleteModal.modal('show')
      })
      @this.on('hideDeleteModal', function () {
        deleteModal.modal('hide')
      })
        deleteModal.on('hidden.bs.modal', function () {
        @this.deletingFile
          = null
        })
    })
    </script>
</div>
