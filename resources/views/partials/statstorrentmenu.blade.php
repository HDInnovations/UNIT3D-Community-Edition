<div class="block pv-5">
    <div class="btn-group text-center">
        <a href="{{ route('seeded') }}" class="btn btn-sm btn-primary"><i
                class="{{ config('other.font-awesome') }} fa-upload"></i> @lang('torrent.top-seeded')</a>
        <a href="{{ route('leeched') }}" class="btn btn-sm btn-primary"><i
                class="{{ config('other.font-awesome') }} fa-download"></i> @lang('torrent.top-leeched')</a>
        <a href="{{ route('completed') }}" class="btn btn-sm btn-primary"><i
                class="{{ config('other.font-awesome') }} fa-arrow-up"></i> @lang('torrent.top-completed')</a>
        <a href="{{ route('dying') }}" class="btn btn-sm btn-primary"><i
                class="{{ config('other.font-awesome') }} fa-exclamation-triangle"></i> @lang('torrent.top-dying')</a>
        <a href="{{ route('dead') }}" class="btn btn-sm btn-primary"><i
                class="{{ config('other.font-awesome') }} fa-recycle"></i> @lang('torrent.top-dead')</a>
    </div>
</div>
