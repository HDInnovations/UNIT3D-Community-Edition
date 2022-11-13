<form
    action="{{ route("deleteRequest", ['id' => $torrentRequest->id]) }}"
    method="POST"
    x-data
>
    @csrf
    <div class="form__group form__group--short-horizontal">
        <button
            x-on:click.prevent="Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to delete this torrent request and lose the BON?',
                icon: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $root.submit();
                }
            })"
            class="form__button form__button--filled form__button--centered"
        >
            {{ __('common.delete') }}
        </button>
    </div>
</form>
