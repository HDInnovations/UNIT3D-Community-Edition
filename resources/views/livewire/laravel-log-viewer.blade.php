<div>
    @section('breadcrumbs')
        <li class="breadcrumbV2">
            <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
                {{ __('staff.staff-dashboard') }}
            </a>
        </li>
        <li class="breadcrumb--active">
            Laravel Log Viewer
        </li>
    @endsection

    <div class="jumbotron">
        <div class="pull-right">
            <select wire:model="file" class="form-control">
                @foreach($files as $file)
                    <option value="{{ $loop->index }}">{{ $file->getFilename() }}</option>
                @endforeach
            </select>
        </div>

        @include('vendor.pagination.laravel-log-paginator')

        @if($log->count() > 0)
            <ul class='text-monospace'>
                @for($i=0; $i < $log->count(); $i++)
                    @if(\str_starts_with($log[$i],'[stacktrace]') || \str_starts_with($log[$i],'#'))
                        <li x-data="{expanded:false}" x-on:click="expanded = !expanded">[stacktrace]
                            <ul class="ml-8" x-show="expanded" x-cloak >
                                @while($i < $log->count())
                                    <li wire:key="{{ $page }}-line-{{ $i }}">{{ $log[$i] }}</li>
                                    @break(\str_starts_with($log[$i++],'"}'))
                                @endwhile
                            </ul>
                        </li>
                    @endif
                    @break($i>=$log->count())

                    <li wire:key="{{ $page }}-line-{{ $i }}" class="font-mono text-xs leading-5
                        {{ \str_contains($log[$i], '.CRITICAL:') ? 'text-danger':''}}
                        {{ \str_contains($log[$i], '.ERROR:') ? 'text-warning':'' }}
                        {{ \str_contains($log[$i], '.INFO:') ? 'text-info':'' }}
                        {{ \str_contains($log[$i], '.WARNING:') ? 'text-info':'' }}
                        ">{{ $log[$i] }}
                    </li>
                @endfor
            </ul>
        @endif
    </div>
</div>

