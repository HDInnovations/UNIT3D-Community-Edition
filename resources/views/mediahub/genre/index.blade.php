@extends('layout.default')

@section('title')
    <title>{{ __('common.genres') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="Genres">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('mediahub.index') }}" class="breadcrumb__link">
            {{ __('mediahub.title') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ __('mediahub.genres') }}
    </li>
@endsection

@section('content')
    <div class="container">
        <div class="block">
            <section class="header">
                <div class="gradient people">
                    <div class="inner_content">
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1414 300">
                                <path class="cls-1"
                                      d="M531.37,223.71a15.76,15.76,0,0,0,3.15-22.07h0a15.76,15.76,0,0,0-22.07-3.15l-38.92,29.2a15.74,15.74,0,0,1-18.67-24.88L541,138.15a15.76,15.76,0,0,0-18.92-25.21L272.84,300H440.4A15.77,15.77,0,0,1,445,288.6l-.34.2Z"></path>
                                <path class="cls-1"
                                      d="M801.29,216.67l-1.08.81-.83.68c-29.5,23.63-64.11,48.17-64.11,48.17v-.09A15.74,15.74,0,0,1,713.62,244l.12-.16.44-.57c.1-.12.19-.25.29-.38l.06,0a15.9,15.9,0,0,1,2.81-2.52c6-4.59,38.89-29.9,52-38.67l.08-.05a15.75,15.75,0,0,0-19.85-24.44L609.2,282.51c-6.76,3.69-16,2.2-20.74-4.14a15.74,15.74,0,0,1,.61-19.64L676.68,193a15.76,15.76,0,0,0-18.92-25.21L481.54,300H683.06l-.06.06h11.06l.12-.06h48.59l77.44-58.11a15.76,15.76,0,1,0-18.92-25.21Z"></path>
                                <path class="cls-1"
                                      d="M563.68,104.48a15.69,15.69,0,0,0,25.1-18.84h0a15.69,15.69,0,0,0-25.1,18.84Z"></path>
                                <path class="cls-1"
                                      d="M1091.43.46a15.77,15.77,0,0,1-4.58,11.4l.34-.2-86.74,65.09a15.76,15.76,0,0,0-3.15,22.07h0a15.76,15.76,0,0,0,22.07,3.15l38.92-29.2A15.74,15.74,0,0,1,1077,97.64l-86.17,64.66a15.76,15.76,0,0,0,18.92,25.21L1259,.46Z"></path>
                                <path class="cls-1"
                                      d="M848.78.46l.06-.06H837.77l-.12.06H789.06L711.62,58.57a15.76,15.76,0,1,0,18.92,25.21l1.08-.81.83-.68c29.5-23.62,64.11-48.17,64.11-48.17v.09a15.74,15.74,0,0,1,21.65,22.21l-.12.16-.44.57c-.1.12-.19.25-.29.38l-.06,0a15.9,15.9,0,0,1-2.81,2.52c-6,4.59-38.89,29.9-52,38.67l-.08.05a15.75,15.75,0,0,0,19.85,24.44L922.63,18c6.76-3.69,16-2.2,20.74,4.14a15.74,15.74,0,0,1-.61,19.64l-87.61,65.74a15.76,15.76,0,0,0,18.92,25.21L1050.29.46Z"></path>
                                <path class="cls-1"
                                      d="M968.15,196A15.69,15.69,0,0,0,943,214.83h0A15.69,15.69,0,0,0,968.15,196Z"></path>
                            </svg>
                        </div>
                        <a href="#"><h2>{{ __('common.genres') }}</h2></a>
                    </div>
                </div>
            </section>
            <div class="blocks">
                @foreach ($genres as $genre)
                    <a href="{{ route('mediahub.genres.show', ['id' => $genre->id]) }}" style="padding: 0 2px;">
                        <div class="people media_blocks" style="background-color: rgba(0, 0, 0, 0.33);">
                            <h2 class="text-bold"> {{ $genre->name }}</h2>
                            <span style="background-color: #317aaf;"></span>
                            <h2 style="font-size: 14px;">
                                <i class="{{ config('other.font-awesome') }} fa-tv-retro"></i> {{ $genre->tv->count() }} {{ __('mediahub.shows') }}
                                |
                                <i class="{{ config('other.font-awesome') }} fa-film"></i> {{ $genre->movie->count() }} {{ __('mediahub.movies') }}
                            </h2>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="text-center">
                {{ $genres->links() }}
            </div>
        </div>
    </div>
@endsection
