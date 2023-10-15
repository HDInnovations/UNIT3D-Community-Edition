<li class="form__group form__group--short-horizontal">
    <form
        method="POST"
        action="{{ route("requests.approved_fills.destroy", ['torrentRequest' => $torrentRequest]) }}"
        x-data
        style="display: contents"
    >
        @csrf
        @method('DELETE')
        <button
            x-on:click.prevent="Swal.fire({
                title: 'Are you sure?',
                text: 'Are you sure you want to revoke the torrent request fill\'s approval and revert the filler\'s bon reward?',
                icon: 'warning',
                showConfirmButton: true,
                showCancelButton: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    $root.submit();
                }
            })"
            class="form__button form__button--outlined form__button--centered"
        >
            Revoke Approval
        </button>
    </form>
</li>

