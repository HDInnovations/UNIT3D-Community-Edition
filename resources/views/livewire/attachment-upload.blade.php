<div>
    <input type="file" wire:model="attachment">

    @error('attachment') <span class="error">{{ $message }}</span> @enderror

    <button wire:click="upload">{{ __('ticket.attachments-save') }}</button>
</div>
