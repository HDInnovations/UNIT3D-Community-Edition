@extends('layout.default')

@section('styles')
	<style>
		.no-padding {
			padding: 0;
		}

		.w90 {
			width: 90% !important;
		}
		.w25 {
			width: 25%;
		}
	</style>
@endsection

@section('content')

<div class="container" id="configExtension" file="{{ (isset($fileData) ? 'yes' : 'no') }}" path="{{ (isset($fileData) ? $fileData->path : '') }}"  view="{{ route('configmanager.view') }}" index="{{ route('configmanager.index') }}" update="{{ route('configmanager.update') }}">
	<div id="configBox"></div>
	<div class="panel panel-primary">
	    <div class="panel-heading">
	        <span class="panel-title">@lang('configmanager.title')</span>
	    </div>
	    <div class="panel-body">
	        <p>@lang('configmanager.info_choose')</p>
	        @php
        		$optgroup = false;
        	@endphp
            <label>
                <select class="file-select">
                    <option value="">Select a config file</option>
                    @foreach ($configFiles as $file)
                    @if ($file->parent != null) {
                        @php
                            $optgroup = true;
                        @endphp
                        <optgroup label="{{ $file->parent }}">
                    @else
                        @if ($optgroup == true)
                            </optgroup>
                        @endif
                        @php
                            $optgroup = true;
                        @endphp
                    @endif
                    <option value="{{ ($file->parent) ? $file->parent.'/'.$file->name : $file->name }}" {{ (isset($fileData) && $fileData->path == $file->path) ? 'selected' : '' }}>{{ $file->name }}</option>
                    @endforeach
                </select>
            </label>
        </div>
	</div>

	@if (isset($fileData))
	<div class="panel panel-primary">
	    <div class="panel-heading">

			<div class="pull-left">
				<span class="panel-title">@lang('configmanager.file'): {{ $fileData->name }}.php</span>
	        </div>
	        @if ($fileData->parent)
	        <div class="pull-right">
				<span class="panel-title">@lang('configmanager.path'): {{ $fileData->parent }}/{{ $fileData->name }}</span>
	        </div>
	        @endif
	        <div class="clearfix"></div>
	    </div>
	    <div class="panel-body">
	    	<table class="table table-hover table-condensed">
			<thead>
				<tr>
					<th>@lang('configmanager.header.key')</th>
					<th>@lang('configmanager.header.value')</th>
					<th class="w25">@lang('configmanager.header.actions')</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($fileParsed as $values)
					<tr id="key_{{ $values["key"] }}">
						<td class="key">{{ $values["key"] }}</td>
						<td class="value" id="key_val_{{ $values["key"] }}">{{ (!$values["value"]) ? 'null' : $values["value"] }}</td>
						<td>
							<button id="button_{{ $values["key"] }}" class="btn btn-sm btn-info edit" keyv="{{ $values["key"] }}" val="{{ (!$values["value"]) ? 'null' : $values["value"] }}"><i class="{{ config('other.font-awesome') }} fa-pencil-square"></i>Edit</button>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
	    </div>
	</div>
	<div id="content-edit" class="hide">
        <div class="form-group">
            <label>
                <input type="text" class="form-control" placeholder="New value…">
            </label>
        </div>
        <div class="first-step">
			<button type="submit" id="confirm-new-key" class="btn btn-primary btn-block">@lang('configmanager.actions.confirm')</button>
        </div>
        <div class="second-step" style="display:none;">
        	<div class="alert alert-warning">
				<i class="{{ config('other.font-awesome') }} fa-exclamation-triangle pr10"></i>
				@lang('configmanager.sure')
			</div>
			<div class="col-xs-6 text-left no-padding">
				<button type="submit" id="save-new-key" class="btn btn-primary w90">@lang('configmanager.actions.save')</button>
			</div>
			<div class="col-xs-6 text-right no-padding">
				<button type="submit" id="cancel-new-key" class="btn btn-danger w90">@lang('configmanager.actions.cancel')</button>
			</div>
			<div class="clearfix"></div>
        </div>
    </div>
	@endif

</div>
@endsection
