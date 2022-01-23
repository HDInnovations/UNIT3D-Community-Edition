<?php
/**
 * @var  Arcanedev\LogViewer\Entities\Log           $log
 * @var  Illuminate\Pagination\LengthAwarePaginator $entries
 * @var  string|null                                $query
 */
?>

@extends('log-viewer::bootstrap-4._master')

@section('content')
    <div class="page-header mb-4">
        <h1>{{ __('Log') }} [{{ $log->date }}]</h1>
    </div>

    <div class="row">
        <div class="col-lg-2">
            {{-- Log Menu --}}
            <div class="card mb-4">
                <div class="card-header"><i class="fa fa-fw fa-flag"></i> {{ __('Levels') }}</div>
                <div class="list-group list-group-flush log-menu">
                    @foreach($log->menu() as $levelKey => $item)
                        @if ($item['count'] === 0)
                            <a class="list-group-item list-group-item-action d-flex justify-content-between align-items-center disabled">
                                <span class="level-name">{!! $item['icon'] !!} {{ $item['name'] }}</span>
                                <span class="badge empty">{{ $item['count'] }}</span>
                            </a>
                        @else
                            <a href="{{ $item['url'] }}"
                               class="list-group-item list-group-item-action d-flex justify-content-between align-items-center level-{{ $levelKey }}{{ $level === $levelKey ? ' active' : ''}}">
                                <span class="level-name">{!! $item['icon'] !!} {{ $item['name'] }}</span>
                                <span class="badge badge-level-{{ $levelKey }}">{{ $item['count'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-10">
            {{-- Log Details --}}
            <div class="card mb-4">
                <div class="card-header">
                    {{ __('Log info') }} :
                    <div class="group-btns pull-right">
                        <a href="{{ route('log-viewer::logs.download', [$log->date]) }}" class="btn btn-sm btn-success">
                            <i class="fa fa-download"></i> {{ __('Download') }}
                        </a>
                        <a href="#delete-log-modal" class="btn btn-sm btn-danger" data-toggle="modal">
                            <i class="fa fa-trash-o"></i> {{ __('Delete') }}
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed mb-0">
                        <tbody>
                        <tr>
                            <td>{{ __('File path') }} :</td>
                            <td colspan="7">{{ $log->getPath() }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Log entries') }} :</td>
                            <td>
                                <span class="badge badge-primary">{{ $entries->total() }}</span>
                            </td>
                            <td>{{ __('Size') }} :</td>
                            <td>
                                <span class="badge badge-primary">{{ $log->size() }}</span>
                            </td>
                            <td>{{ __('Created at') }} :</td>
                            <td>
                                <span class="badge badge-primary">{{ $log->createdAt() }}</span>
                            </td>
                            <td>{{ __('Updated at') }} :</td>
                            <td>
                                <span class="badge badge-primary">{{ $log->updatedAt() }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    {{-- Search --}}
                    <form action="{{ route('log-viewer::logs.search', [$log->date, $level]) }}" method="GET">
                        <div class="form-group">
                            <div class="input-group">
                                <input id="query" name="query" class="form-control" value="{{ $query }}"
                                       placeholder="{{ __('Type here to search') }}">
                                <div class="input-group-append">
                                    @unless (is_null($query))
                                        <a href="{{ route('log-viewer::logs.show', [$log->date]) }}"
                                           class="btn btn-secondary">
                                            (@lang(':count results', ['count' => $entries->count()])) <i
                                                    class="fa fa-fw fa-times"></i>
                                        </a>
                                    @endunless
                                    <button id="search-btn" class="btn btn-primary">
                                        <span class="fa fa-fw fa-search"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Log Entries --}}
            <div class="card mb-4">
                @if ($entries->hasPages())
                    <div class="card-header">
                        <span class="badge badge-info float-right">
                            {{ __('Page :current of :last', ['current' => $entries->currentPage(), 'last' => $entries->lastPage()]) }}
                        </span>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="entries" class="table mb-0">
                        <thead>
                        <tr>
                            <th>{{ __('ENV') }}</th>
                            <th style="width: 120px;">{{ __('Level') }}</th>
                            <th style="width: 65px;">{{ __('Time') }}</th>
                            <th>{{ __('Header') }}</th>
                            <th class="text-right">{{ __('Actions') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($entries as $key => $entry)
                            <?php /** @var  Arcanedev\LogViewer\Entities\LogEntry $entry */ ?>
                            <tr>
                                <td>
                                    <span class="badge badge-env">{{ $entry->env }}</span>
                                </td>
                                <td>
                                        <span class="badge badge-level-{{ $entry->level }}">
                                            {!! $entry->level() !!}
                                        </span>
                                </td>
                                <td>
                                        <span class="badge badge-secondary">
                                            {{ $entry->datetime->format('H:i:s') }}
                                        </span>
                                </td>
                                <td>
                                    {{ $entry->header }}
                                </td>
                                <td class="text-right">
                                    @if ($entry->hasStack())
                                        <a class="btn btn-sm btn-light" role="button" data-toggle="collapse"
                                           href="#log-stack-{{ $key }}" aria-expanded="false"
                                           aria-controls="log-stack-{{ $key }}">
                                            <i class="fa fa-toggle-on"></i> {{ __('Stack') }}
                                        </a>
                                    @endif

                                    @if ($entry->hasContext())
                                        <a class="btn btn-sm btn-light" role="button" data-toggle="collapse"
                                           href="#log-context-{{ $key }}" aria-expanded="false"
                                           aria-controls="log-context-{{ $key }}">
                                            <i class="fa fa-toggle-on"></i> {{ __('Context') }}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @if ($entry->hasStack() || $entry->hasContext())
                                <tr>
                                    <td colspan="5" class="stack py-0">
                                        @if ($entry->hasStack())
                                            <div class="stack-content collapse" id="log-stack-{{ $key }}">
                                                {!! $entry->stack() !!}
                                            </div>
                                        @endif

                                        @if ($entry->hasContext())
                                            <div class="stack-content collapse" id="log-context-{{ $key }}">
                                                <pre>{{ $entry->context() }}</pre>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    <span class="badge badge-secondary">{{ __('The list of logs is empty!') }}</span>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {!! $entries->appends(compact('query'))->render() !!}
        </div>
    </div>
@endsection

@section('modals')
    {{-- DELETE MODAL --}}
    <div id="delete-log-modal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <form id="delete-log-form" action="{{ route('log-viewer::logs.delete') }}" method="POST">
                @method('DELETE')
                @csrf
                <input type="hidden" name="date" value="{{ $log->date }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Delete log file') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('Are you sure you want to delete this log file: :date ?', ['date' => $log->date]) }}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary mr-auto"
                                data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-sm btn-danger"
                                data-loading-text="{{ __('Loading') }}&hellip;">{{ __('Delete') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      $(function () {
        const deleteLogModal = $('div#delete-log-modal'),
          deleteLogForm = $('form#delete-log-form'),
          submitBtn = deleteLogForm.find('button[type=submit]')

        deleteLogForm.on('submit', function (event) {
          event.preventDefault()
          submitBtn.button('loading')

          $.ajax({
            url: $(this).attr('action'),
            type: $(this).attr('method'),
            dataType: 'json',
            data: $(this).serialize(),
            success: function (data) {
              submitBtn.button('reset')
              if (data.result === 'success') {
                deleteLogModal.modal('hide')
                location.replace("{{ route('log-viewer::logs.list') }}")
              } else {
                alert('OOPS ! This is a lack of coffee exception !')
              }
            },
            error: function (xhr, textStatus, errorThrown) {
              alert('AJAX ERROR ! Check the console !')
              console.error(errorThrown)
              submitBtn.button('reset')
            }
          })

          return false
        })

          @unless (empty(log_styler()->toHighlight()))
          @php
              $htmlHighlight = version_compare(PHP_VERSION, '7.4.0') >= 0
                  ? join('|', log_styler()->toHighlight())
                  : join(log_styler()->toHighlight(), '|')
          @endphp

          $('.stack-content').each(function () {
            const $this = $(this)
            const html = $this.html().trim()
              .replace(/({!! $htmlHighlight !!})/gm, '<strong>$1</strong>')

            $this.html(html)
          })
          @endunless
      })
    </script>
@endsection
