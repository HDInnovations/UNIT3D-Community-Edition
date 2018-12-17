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

<div class="container">

	<div class="panel panel-primary">
	    <div class="panel-heading">
	        <span class="panel-title">@lang('configmanager.title')</span>
	    </div>
	    <div class="panel-body">
	        <p>@lang('configmanager.info_choose')</p>
	        @php
        		$optgroup = false;
        	@endphp
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
						<td class="value">{{ (!$values["value"]) ? 'null' : $values["value"] }}</td>
						<td>
							<button class="btn btn-sm btn-info edit" data-placement="top" data-key="{{ $values["key"] }}" data-loading-text="Saving new key..."><i class="{{ config('other.font-awesome') }} fa-pencil-square" aria-hidden="true"></i> Edit</button>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
	    </div>
	</div>
	<div id="content-edit" class="hide">
        <div class="form-group">
            <input type="text" class="form-control" placeholder="New value…">
        </div>
        <div class="first-step">
			<button type="submit" id="confirm-new-key" class="btn btn-primary btn-block">@lang('configmanager.actions.confirm')</button>
        </div>
        <div class="second-step" style="display:none">
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

@section('scripts')
	<script type="text/javascript">
		jQuery(document).ready(function(){

			$('.file-select').on('change', function(){
	            var file = $(this).val();
	            if (file) {
	                window.location.href = "{{ route('configmanager.view') }}/"+$(this).val();
	            } else {
	                window.location.href = "{{ route('configmanager.index') }}";
	            }
	        });

			@if (isset($fileData))

	        $('.edit').popover({
			    html: true,
			    title: function () {
			        return "@lang('configmanager.actions.edit'): " + $(this).parent().parent().find('.key').html();
			    },
			    content: function () {
			        return $('#content-edit').html();
			    }
			});

			$('.edit').on('hidden.bs.popover', function () {
		  		$(".first-step").show();
				$(".second-step").hide();
			});

			$(document).on('click', '#confirm-new-key', function(){
				$popover = $('.popover.in');
				$input = $popover.find('input');
				var newValue = $input.val();
				if (!newValue.trim()) {
					$input.val('');
					$input.focus();
					return false;
				}


				$input.attr('disabled', true);
				$(".first-step").hide();
				$(".second-step").show();
			});

			$(document).on('click', '#cancel-new-key', function(){
				$popover = $('.popover.in');
				$popover.find('input').attr('disabled', false);
				$(".first-step").show();
				$(".second-step").hide();
			});

			$(document).on('click', '#save-new-key', function(){
				$popover = $('.popover.in');
				$input = $popover.find('input');
				$input.attr('disabled', false);
				var newValue = $input.val();
				var key = $popover.parent().find('button').data('key');
				$row = $("#key_"+key);


				// CLose popover
				(($popover.popover('hide').data('bs.popover')||{}).inState||{}).click = false;



				$.ajax({
                        url: "{{ route('configmanager.update') }}",
                        data: {
                        	'filePath' : "{{ $fileData->path }}",
                        	'key' : key,
                        	'value' : newValue
                        },
                        beforeSend: function (request){
                            request.setRequestHeader("X-CSRF-TOKEN", "{{ csrf_token() }}");
                        },
                        type: 'PUT',
                        success: function(result) {
                        	$row.find('td.value').html(newValue);
                            $popover.parent().find('button').button('reset');
                        },
                        error: function(result) {
                        	console.log('Error');
                           	$popover.parent().find('button').button('reset');
                        }
                    });

			});
			@endif

		});
		@if (isset($fileData))
		$(document).on('click', function (e) {
		    $('[data-toggle="popover"],[data-original-title]').each(function(){
		        //the 'is' for buttons that trigger popups
		        //the 'has' for icons within a button that triggers a popup
		        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
		            (($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
		        }

		    });
		});
		@endif


	</script>
@endsection
