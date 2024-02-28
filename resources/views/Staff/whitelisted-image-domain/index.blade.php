@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('staff.dashboard.index') }}" class="breadcrumb__link">
            {{ __('staff.staff-dashboard') }}
        </a>
    </li>
    <li class="breadcrumb--active">Whitelisted Image Domains</li>
@endsection

@section('page', 'page__whitelisted-image-domains--index')

@section('main')
    <section class="panelV2">
        <header class="panel__header">
            <h2 class="panel__heading">Whitelisted Image Domains</h2>
            <div class="panel__actions">
                <div class="panel__action" x-data="dialog">
                    <button class="form__button form__button--text" x-bind="showDialog">
                        {{ __('common.add') }}
                    </button>
                    <dialog class="dialog" x-bind="dialogElement">
                        <h3 class="dialog__heading">{{ __('common.add') }}</h3>
                        <form
                            class="dialog__form"
                            method="POST"
                            action="{{ route('staff.whitelisted_image_domains.store') }}"
                            x-bind="dialogForm"
                        >
                            @csrf
                            <p class="form__group">
                                <input
                                    id="domain"
                                    class="form__text"
                                    name="domain"
                                    placeholder=" "
                                    required
                                    type="text"
                                />
                                <label class="form__label form__label--floating" for="domain">
                                    Domain
                                </label>
                            </p>
                            <p class="form__group">
                                <button class="form__button form__button--filled">
                                    {{ __('common.add') }}
                                </button>
                                <button
                                    formmethod="dialog"
                                    formnovalidate
                                    class="form__button form__button--outlined"
                                >
                                    {{ __('common.cancel') }}
                                </button>
                            </p>
                        </form>
                    </dialog>
                </div>
            </div>
        </header>
        <div class="data-table-wrapper">
            <table class="data-table">
                <thead>
                    <th>ID</th>
                    <th>Domain</th>
                    <th>{{ __('common.created_at') }}</th>
                    <th>{{ __('forum.updated-at') }}</th>
                    <th>{{ __('common.actions') }}</th>
                </thead>
                <tbody>
                    @forelse ($whitelistedImageDomains as $whitelistedImageDomain)
                        <tr>
                            <td>{{ $whitelistedImageDomain->id }}</td>
                            <td>{{ $whitelistedImageDomain->domain }}</td>
                            <td>
                                <time
                                    datetime="{{ $whitelistedImageDomain->created_at }}"
                                    title="{{ $whitelistedImageDomain->created_at }}"
                                >
                                    {{ $whitelistedImageDomain->created_at }}
                                </time>
                            </td>
                            <td>
                                <time
                                    datetime="{{ $whitelistedImageDomain->updated_at }}"
                                    title="{{ $whitelistedImageDomain->updated_at }}"
                                >
                                    {{ $whitelistedImageDomain->updated_at }}
                                </time>
                            </td>
                            <td>
                                <menu class="data-table__actions">
                                    <li class="data-table__action" x-data="dialog">
                                        <button
                                            class="form__button form__button--text"
                                            x-bind="showDialog"
                                        >
                                            {{ __('common.edit') }}
                                        </button>
                                        <dialog class="dialog" x-bind="dialogElement">
                                            <h3 class="dialog__heading">
                                                {{ __('common.edit') }}
                                            </h3>
                                            <form
                                                class="dialog__form"
                                                method="POST"
                                                action="{{ route('staff.whitelisted_image_domains.update', ['whitelistedImageDomain' => $whitelistedImageDomain]) }}"
                                                x-bind="dialogForm"
                                            >
                                                @csrf
                                                @method('PATCH')
                                                <p class="form__group">
                                                    <input
                                                        id="domain"
                                                        class="form__text"
                                                        name="domain"
                                                        placeholder=" "
                                                        required
                                                        type="text"
                                                        value="{{ $whitelistedImageDomain->domain }}"
                                                    />
                                                    <label
                                                        class="form__label form__label--floating"
                                                        for="domain"
                                                    >
                                                        {{ __('common.position') }}
                                                    </label>
                                                </p>
                                                <p class="form__group">
                                                    <button
                                                        class="form__button form__button--filled"
                                                    >
                                                        {{ __('common.edit') }}
                                                    </button>
                                                    <button
                                                        formmethod="dialog"
                                                        formnovalidate
                                                        class="form__button form__button--outlined"
                                                    >
                                                        {{ __('common.cancel') }}
                                                    </button>
                                                </p>
                                            </form>
                                        </dialog>
                                    </li>
                                    <li class="data-table__action">
                                        <form
                                            action="{{ route('staff.whitelisted_image_domains.destroy', ['whitelistedImageDomain' => $whitelistedImageDomain]) }}"
                                            method="POST"
                                            x-data="confirmation"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                x-on:click.prevent="confirmAction"
                                                class="form__button form__button--text"
                                                data-b64-deletion-message="{{ base64_encode('Are you sure you want to remove this whitelisted image domain: ' . $whitelistedImageDomain->domain . '?') }}"
                                            >
                                                {{ __('common.delete') }}
                                            </button>
                                        </form>
                                    </li>
                                </menu>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2">No whitelisted image domains.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.info') }}</h2>
        <div class="panel__body">
            <p>
                When users add images via BBCode, other users will load the image on page load. This
                means whoever operates the image domain can view the connecting IPs. Therefore, all
                images entered via BBCode are proxied.
            </p>
            <p>
                In exception cases where the proxy blocks a popular image host, that image host
                domain should be whitelisted here. Any trusted image domains can also be included
                here to increase client image loading speeds.
            </p>
        </div>
    </section>
    <section class="panelV2">
        <h2 class="panel__heading">Warning</h2>
        <div class="panel__body">
            <p>
                Note: if you whitelist
                <code>xyz.com</code>
                , it will also whitelist
                <code>abcxyz.com</code>
                since they share the same suffix. To prevent this, whitelist
                <code>https://xyz.com</code>
                instead.
            </p>
            <p>
                Alternatively, if you wish to allow all subdomains of
                <code>xyz.com</code>
                , e.g.
                <code>image1.xyz.com</code>
                , then whitelist
                <code>.xyz.com</code>
                .
            </p>
        </div>
    </section>
@endsection
