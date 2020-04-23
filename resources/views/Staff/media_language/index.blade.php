@extends('layout.default')

@section('breadcrumb')
    <li>
        <a href="{{ route('staff.dashboard.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">@lang('staff.staff-dashboard')</span>
        </a>
    </li>
    <li class="active">
        <a href="{{ route('staff.media_languages.index') }}" itemprop="url" class="l-breadcrumb-item-link">
            <span itemprop="title" class="l-breadcrumb-item-link-title">
                Media Languages
            </span>
        </a>
    </li>
@endsection

@section('content')
    <div class="container box">
        <h2>@lang('common.media-languages')</h2> <p>@lang('staff.media-languages-desc')</p>

        <a href="{{ route('staff.media_languages.create') }}" class="btn btn-primary">
            @lang('common.add')
            @lang(trans_choice('common.a-an-art',false))
            @lang('common.media-language')
        </a>
    
        <div class="table-responsive">
            <table class="table table-condensed table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>@lang('common.name')</th>
                        <th>@lang('common.code')</th>
                        <th>@lang('common.action')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($media_languages as $media_language)
                        <tr>
                            <td>
                                <a href="{{ route('staff.media_languages.edit', ['id' => $media_language->id]) }}">
                                    {{ $media_language->name }}
                                </a>
                            </td>
                            <td>
                                <span>{{ $media_language->code }}</span>
                            </td>
                            <td>
                                <a href="{{ route('staff.media_languages.edit', ['id' => $media_language->id]) }}" class="btn btn-warning">
                                    @lang('common.edit')
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
