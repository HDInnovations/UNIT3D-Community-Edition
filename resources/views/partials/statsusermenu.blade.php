<div class="block pv-5">
    <div class="btn-group text-center">
        <a href="{{ route('uploaded') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-upload"></i> {{ __('user.top-uploaders-data') }}</a>
        <a href="{{ route('downloaded') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-download"></i> {{ __('user.top-downloaders-data') }}</a>
        <a href="{{ route('seeders') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-arrow-up"></i> {{ __('user.top-seeders') }}</a>
        <a href="{{ route('leechers') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-arrow-down"></i> {{ __('user.top-leechers') }}</a>
        <a href="{{ route('uploaders') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-upload"></i> {{ __('user.top-uploaders-count') }}</a>
        <a href="{{ route('bankers') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-coins"></i> {{ __('user.top-bankers') }}</a>
    </div>
</div>
