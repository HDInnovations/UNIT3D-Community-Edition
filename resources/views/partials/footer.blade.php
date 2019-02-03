@php $bg = rand(1, 13); $bgchange = $bg.".jpg"; @endphp
<br>
<div id="l-footer" style="background-image: url('/img/footer/<?php echo $bgchange; ?>');">
    <div class="container">
        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title"><span class="text-bold">{{ config('other.title') }}</span></h2>
            <footer>{{ config('other.meta_description') }}</footer>
            <br>
            <i class="{{ config('other.font-awesome') }} fa-tv-retro disk-good" style="font-size: 90px;"></i>
        </div>

        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">@lang('common.account')</h2>
            <ul>
                @if (auth()->check())
                    <li>
                        <a href="{{ route('profile', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}">@lang('user.my-profile')</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">@lang('common.logout')</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">@csrf</form>
                    </li>
                @endif
            </ul>
        </div>

        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">@lang('common.community')</h2>
            <ul>
                <li><a href="{{ route('forum_index') }}">@lang('forum.forums')</a></li>
                <li><a href="{{ route('members') }}">@lang('common.members')</a></li>
                <li><a href="{{ route('articles') }}">@lang('common.news')</a></li>
            </ul>
        </div>

        @if ($pages)
        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">@lang('common.pages')</h2>
            <ul>
                @foreach ($pages as $p)
                    <li><a href="{{ route('page', ['slug' => $p->slug, 'id' => $p->id]) }}">{{ $p->name }}</a></li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">@lang('common.info')</h2>
            <ul>
                <li><a href="{{ route('staff') }}">@lang('common.staff')</a></li>
                <li><a href="{{ route('internal') }}">@lang('common.internal')</a></li>
                <li><a href="{{ route('blacklist') }}">@lang('common.blacklist')</a></li>
                @if (config('email-white-blacklist.enabled') == 'allow')
                    <li><a href="{{ route('emaillist') }}">@lang('common.email-whitelist')</a></li>
                @endif
                @if (config('email-white-blacklist.enabled') == 'block')
                    <li><a href="{{ route('emaillist') }}">@lang('common.email-blacklist')</a></li>
                @endif
                <li><a href="{{ route('about') }}">@lang('common.about')</a></li>
            </ul>
        </div>

        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">@lang('common.other')</h2>
            <a href="https://www.patreon.com/UNIT3D"
               class="btn btn-xs btn-primary">@lang('common.patron')</a>
            <a href="https://github.com/UNIT3D/UNIT3D"
               class="btn btn-xs btn-primary">@lang('common.powered-by')</a>
        </div>
    </div>
</div>

<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button">
    <i class="{{ config('other.font-awesome') }} fa-arrow-square-up"></i>
</a>
