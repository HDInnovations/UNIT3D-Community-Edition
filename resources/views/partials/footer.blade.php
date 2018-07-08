@php $bg = rand(1, 13); $bgchange = $bg.".jpg"; @endphp
<br>
<div id="l-footer" style="background-image: url('/img/footer/<?php echo $bgchange; ?>');">
    <div class="container">
        <div class="col-md-3 l-footer-section">
            <h2 class="l-footer-section-title"><span class="text-bold">{{ config('other.title') }}</span></h2>
            <footer>{{ config('other.meta_description') }}</footer>
            <br>
            <i class="fa fa-tv disk-good" style="font-size: 90px;"></i>
        </div>

        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">{{ trans('common.account') }}</h2>
            <ul>
                @if(auth()->check())
                    <li>
                        <a href="{{ route('profile', ['username' => auth()->user()->username, 'id' => auth()->user()->id]) }}">{{ trans('user.my-profile') }}</a>
                    </li>
                    <li>
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ trans('common.logout') }}</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                              style="display: none;">{{ csrf_field() }}</form>
                    </li>
                @endif
            </ul>
        </div>

        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">{{ trans('common.community') }}</h2>
            <ul>
                <li><a href="{{ route('forum_index') }}">{{ trans('forum.forums') }}</a></li>
                <li><a href="{{ route('members') }}">{{ trans('common.members') }}</a></li>
                <li><a href="{{ route('articles') }}">{{ trans('common.news') }}</a></li>
            </ul>
        </div>

        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">{{ trans('common.pages') }}</h2>
            <ul>
                <li><a href="{{ route('home') }}/p/rules.1">{{ trans('common.rules') }}</a></li>
                <li><a href="{{ route('home') }}/p/faq.3">{{ trans('common.faq') }}</a></li>
                <li><a href="{{ route('blacklist') }}">{{ trans('common.blacklist') }}</a></li>
                <li><a href="{{ route('home') }}/p/tracker-codes.6">{{ trans('common.tracker-codes') }}</a></li>
                <li><a href="{{ route('home') }}/p/upload-guide.5">{{ trans('common.upload-guide') }}</a></li>
                @if (config('email-white-blacklist.enabled') == 'allow')
                <li><a href="{{ route('emaillist') }}">{{ trans('common.email-whitelist') }}</a></li>
                @endif
                @if (config('email-white-blacklist.enabled') == 'block')
                <li><a href="{{ route('emaillist') }}">{{ trans('common.email-blacklist') }}</a></li>
                @endif
            </ul>
        </div>

        <div class="col-md-2 l-footer-section">
            <h2 class="l-footer-section-title">{{ trans('common.info') }}</h2>
            <ul>
                <li><a href="{{ route('staff') }}">{{ trans('common.staff') }}</a></li>
                <li><a href="{{ route('internal') }}">{{ trans('common.internal') }}</a></li>
                <li><a href="{{ route('about') }}">{{ trans('common.about') }}</a></li>
                <li><a href="{{ route('home') }}/p/terms_of_use.7">{{ trans('common.terms') }}</a></li>
            </ul>
        </div>

        <div class="col-md-1 l-footer-section">
            <h2 class="l-footer-section-title">{{ trans('common.other') }}</h2>
            <a href="https://anon.to/?https://www.patreon.com/UNIT3D"
               class="btn btn-xs btn-primary">{{ trans('common.patron') }}</a>
            <a href="https://anon.to/?https://github.com/UNIT3D/UNIT3D"
               class="btn btn-xs btn-primary">{{ trans('common.powered-by') }}</a>
        </div>
    </div>
</div>

<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button">
    <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
</a>
