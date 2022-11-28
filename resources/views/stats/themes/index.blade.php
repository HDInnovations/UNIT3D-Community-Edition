@extends('layout.default')

@section('title')
    <title>{{ __('stat.stats') }} - {{ config('other.title') }}</title>
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('stats') }}" class="breadcrumb__link">
            {{ __('stat.stats') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        Themes
    </li>
@endsection

@section('page', 'page__stats--themes')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">Site Stylesheets</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                @forelse ($siteThemes as $siteTheme)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @switch($siteTheme->style)
                                @case ('0')
                                Light Theme
                                @break
                                @case ('1')
                                Galactic Theme
                                @break
                                @case ('2')
                                Dark Blue Theme
                                @break
                                @case ('3')
                                Dark Green Theme
                                @break
                                @case ('4')
                                Dark Pink Theme
                                @break
                                @case ('5')
                                Dark Purple Theme
                                @break
                                @case ('6')
                                Dark Red Theme
                                @break
                                @case ('7')
                                Dark Teal Theme
                                @break
                                @case ('8')
                                Dark Yellow Theme
                                @break
                                @case ('9')
                                Cosmic Void Theme
                                @break
                            @endswitch
                        </td>
                        <td>Used By {{ $siteTheme->value }} Users</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">None Used</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </section>

    <section class="panelV2">
        <h2 class="panel__heading">External CSS Stylesheets (Stacks on top of above site theme)</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                @forelse ($customThemes as $customTheme)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $customTheme->custom_css }}</td>
                        <td>Used By {{ $customTheme->value }} Users</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">None Used</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </section>

    <section class="panelV2">
        <h2 class="panel__heading">Standalone CSS Stylesheets (No site theme used)</h2>
        <div class="data-table-wrapper">
            <table class="data-table">
                @forelse ($standaloneThemes as $standaloneTheme)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $standaloneTheme->standalone_css }}</td>
                        <td>Used By {{ $standaloneTheme->value }} Users</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">None Used</td>
                    </tr>
                @endforelse
            </table>
        </div>
    </section>
@endsection