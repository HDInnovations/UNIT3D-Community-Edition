<div class="block pv-5">
    <div class="btn-group text-center">
        <a href="{{ route('uploaded') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-upload"></i> @lang('user.top-uploaders-data')</a>
        <a href="{{ route('downloaded') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-download"></i> @lang('user.top-downloaders-data')</a>
        <a href="{{ route('seeders') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-arrow-up"></i> @lang('user.top-seeders')</a>
        <a href="{{ route('leechers') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-arrow-down"></i> @lang('user.top-leechers')</a>
        <a href="{{ route('uploaders') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-upload"></i> @lang('user.top-uploaders-torrents')</a>
        <a href="{{ route('bankers') }}" class="btn btn-sm btn-primary"><i
                    class="{{ config('other.font-awesome') }} fa-star"></i> @lang('user.top-bankers')</a>
        {{--<a href="{{ route('seedtime') }}" class="btn btn-sm btn-primary"><i class="{{ config('other.font-awesome') }} fa-clock"></i> @lang('user.top-seedtime')</a>--}}
    </div>
</div>
