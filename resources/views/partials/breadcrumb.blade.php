<ol class="breadcrumb">
    <li>
        <a href="{{ route('home') }}">
            <i class="{{ config('other.font-awesome') }} fa-home"></i> @lang('common.home')
        </a>
    </li>
    @yield('breadcrumb')
</ol>
