<div style="display: inline;">
	<button wire:click="thank({{ $torrent->id }})" class="btn btn-sm btn-primary">
		<i class="{{ config('other.font-awesome') }} fa-heart text-pink"></i> @lang('torrent.thank') @lang('torrent.uploader')
		({{ $torrent->thanks()->count() }} @lang('common.given'))
	</button>
</div>
