@extends('layout.default')

@section('content')
    <style nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('style') }}">
        .stack {
            font-size: 0.85em;
        }

        .date {
            min-width: 75px;
        }

        .text {
            word-break: break-all;
        }

        a.llv-active {
            z-index: 2;
            background-color: #f5f5f5;
            border-color: #777777;
        }

        .log_file {
            width: 200px;
            float: right;
            position: relative;
            display: block;
            padding: 10px 15px;
            margin-bottom: -1px;
            background-color: #ffffff;
            border: 1px solid #dddddd;
        }

    </style>

    <div class="container-fluid">
        <div class="row">
            <br><br>
            <div class="text-center">
                <h3><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span> Laravel Log Viewer</h3>
            </div>
            @foreach ($files as $file)
                <a href="?l={{ base64_encode($file) }}" class="log_file @if ($current_file == $file) llv-active @endif">
                    {{ $file }}
                </a>
            @endforeach
            <div class="col-sm-9 col-md-12 table-container">
                @if ($logs === null)
                    <div>
                        Log file >50M, please download it.
                    </div>
                @else
                    <table id="table-log" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Level</th>
                            <th>Context</th>
                            <th>Date</th>
                            <th>Content</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach ($logs as $key => $log)
                            <tr>
                                <td class="text-{{ $log['level_class'] }}"><span
                                            class="glyphicon glyphicon-{{ $log['level_img'] }}-sign"
                                            aria-hidden="true"></span>
                                    &nbsp;{{ $log['level'] }}</td>
                                <td class="text">{{ $log['context'] }}</td>
                                <td class="date">{{ $log['date'] }}</td>
                                <td class="text">
                                    @if ($log['stack']) <a class="pull-right expand btn btn-default btn-xs"
                                                           data-display="stack{{ $key }}"><span
                                                class="glyphicon glyphicon-search"></span></a>@endif
                                    {{ $log['text'] }}
                                    @if (isset($log['in_file'])) <br/>{{ $log['in_file'] }}@endif
                                    @if ($log['stack'])
                                        <div class="stack" id="stack{{ $key }}"
                                             style="display: none; white-space: pre-wrap;">
                                            {{ trim($log['stack']) }}</div>@endif
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @endif
                <div>
                    <a href="?dl={{ base64_encode($current_file) }}"><span
                                class="glyphicon glyphicon-download-alt"></span>
                        Download file</a>
                    -
                    <a id="delete-log" href="?del={{ base64_encode($current_file) }}"><span
                                class="glyphicon glyphicon-trash"></span> {{ __('common.delete') }} file</a>
                </div>
            </div>
        </div>
    </div>
@endsection
