<footer class="footer">
    <div class="footer__wrapper">
        <section class="footer__section">
            <h2 class="footer__section-title">
                <b>{{ config('other.title') }}</b>
            </h2>
            <p>{{ config('other.meta_description') }}</p>
            <i
                class="{{ config('other.font-awesome') }} fa-tv-retro footer__icon"
                style="font-size: 90px"
            ></i>
        </section>
        <section class="footer__section">
            <h2 class="footer__section-title">{{ __('common.account') }}</h2>
            <ul class="footer__section-list">
                <li>
                    <a href="{{ route('users.show', ['user' => auth()->user()]) }}">
                        {{ __('user.my-profile') }}
                    </a>
                </li>
                <li>
                    <form action="{{ route('logout') }}" method="POST" style="display: contents">
                        @csrf
                        <button style="display: contents">
                            {{ __('common.logout') }}
                        </button>
                    </form>
                </li>
            </ul>
        </section>
        <section class="footer__section">
            <h2 class="footer__section-title">{{ __('common.community') }}</h2>
            <ul class="footer__section-list">
                <li>
                    <a href="{{ route('forums.index') }}">{{ __('forum.forums') }}</a>
                </li>
                <li>
                    <a href="{{ route('articles.index') }}">{{ __('common.news') }}</a>
                </li>
                <li><a href="{{ route('wikis.index') }}">Wikis</a></li>
            </ul>
        </section>
        @if ($footer_pages)
            <section class="footer__section">
                <h2 class="footer__section-title">{{ __('common.pages') }}</h2>
                <ul class="footer__section-list">
                    @foreach ($footer_pages as $page)
                        <li>
                            <a href="{{ route('pages.show', ['page' => $page]) }}">
                                {{ $page->name }}
                            </a>
                        </li>
                    @endforeach

                    <li>
                        <a href="{{ route('pages.index') }}">[View All]</a>
                    </li>
                </ul>
            </section>
        @endif

        <section class="footer__section">
            <h2 class="footer__section-title">{{ __('common.info') }}</h2>
            <ul class="footer__section-list">
                <li>
                    <a href="{{ route('staff') }}">{{ __('common.staff') }}</a>
                </li>
                <li>
                    <a href="{{ route('internal') }}">{{ __('common.internal') }}</a>
                </li>
                <li>
                    <a href="{{ route('client_blacklist') }}">{{ __('common.blacklist') }}</a>
                </li>
                <li>
                    <a href="{{ route('about') }}">{{ __('common.about') }}</a>
                </li>
                <li>
                    <a
                        href="https://github.com/HDInnovations/UNIT3D-Community-Edition/wiki/Torrent-API-(UNIT3D-v8.x.x)"
                    >
                        API Documentation
                    </a>
                </li>
            </ul>
        </section>
        <section class="footer__section">
            <h2 class="footer__section-title">{{ __('common.other') }}</h2>
            <ul class="footer__section-list">
                <li>
                    <a
                        href="https://polar.sh/HDInnovations"
                        target="_blank"
                        class="form__button form__button--outlined"
                    >
                        Support UNIT3D Development
                    </a>
                </li>
                <li>
                    <a
                        href="https://github.com/HDInnovations/UNIT3D"
                        target="_blank"
                        class="form__button form__button--outlined"
                    >
                        {{ __('common.powered-by') }}
                    </a>
                </li>
            </ul>
        </section>
    </div>
    <p class="footer__stats">
        This page took
        {{ number_format(microtime(true) - (defined('LARAVEL_START') ? LARAVEL_START : request()->server('REQUEST_TIME_FLOAT')), 3) }}
        seconds to render and {{ number_format(memory_get_peak_usage(true) / 1024 / 1024, 2) }} MB
        of memory
    </p>
</footer>
