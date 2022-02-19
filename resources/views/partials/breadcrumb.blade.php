<ol class="breadcrumb">
    <li>
        <a href="{{ route('home.index') }}">
            <i class="{{ config('other.font-awesome') }} fa-home"></i> {{ __('common.home') }}
        </a>
    </li>
    @yield('breadcrumb')
</ol>
