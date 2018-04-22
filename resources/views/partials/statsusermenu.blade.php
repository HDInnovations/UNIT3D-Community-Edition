<div class="block pv-5">
    <div class="btn-group text-center">
        <a href="{{ route('uploaded') }}" class="btn btn-sm btn-primary"><i
                    class="fa fa-upload"></i> {{ trans('user.top-uploaders-data') }}</a>
        <a href="{{ route('downloaded') }}" class="btn btn-sm btn-primary"><i
                    class="fa fa-download"></i> {{ trans('user.top-downloaders-data') }}</a>
        <a href="{{ route('seeders') }}" class="btn btn-sm btn-primary"><i
                    class="fa fa-arrow-up"></i> {{ trans('user.top-seeders') }}</a>
        <a href="{{ route('leechers') }}" class="btn btn-sm btn-primary"><i
                    class="fa fa-arrow-down"></i> {{ trans('user.top-leechers') }}</a>
        <a href="{{ route('uploaders') }}" class="btn btn-sm btn-primary"><i
                    class="fa fa-upload"></i> {{ trans('user.top-uploaders-torrents') }}</a>
        <a href="{{ route('bankers') }}" class="btn btn-sm btn-primary"><i
                    class="fa fa-star"></i> {{ trans('user.top-bankers') }}</a>
        {{--<a href="{{ route('seedtime') }}" class="btn btn-sm btn-primary"><i class="fa fa-clock"></i> {{ trans('user.top-seedtime') }}</a>--}}
    </div>
</div>
