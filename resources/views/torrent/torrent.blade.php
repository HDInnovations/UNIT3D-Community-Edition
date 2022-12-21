@extends('layout.default')

@section('title')
    <title>{{ $torrent->name }} - {{ __('torrent.torrents') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('torrent.meta-desc', ['name' => $torrent->name]) }}!">
@endsection

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('torrents') }}" class="breadcrumb__link">
            {{ __('torrent.torrents') }}
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $torrent->name }}
    </li>
@endsection

@section('content')
    <div id="torrent-page">
        <div class="meta-wrapper box container" id="meta-info">
            {{-- Movie Meta Block --}}
            @if ($torrent->category->movie_meta)
                @include('torrent.partials.movie_meta')
            @endif

            {{-- TV Meta Block --}}
            @if ($torrent->category->tv_meta)
                @include('torrent.partials.tv_meta')
            @endif

            {{-- Game Meta Block --}}
            @if ($torrent->category->game_meta)
                @include('torrent.partials.game_meta')
            @endif

            {{-- No Meta Block --}}
            @if ($torrent->category->no_meta)
                @include('torrent.partials.no_meta')
            @endif

            <div style="padding: 10px; position: relative;">
                <div class="vibrant-overlay"></div>
                <div class="button-overlay"></div>
            </div>
            <h1 class="text-center" style="font-size: 22px; margin: 12px 0 0 0;">
                {{ $torrent->name }}
            </h1>
            <div class="torrent-buttons">
                @include('torrent.partials.buttons')
            </div>
        </div>

        <div class="meta-general box container">
            {{-- General Info Block --}}
            @include('torrent.partials.general')

            {{-- Tools Block --}}
            @if (auth()->user()->group->is_modo || auth()->user()->id === $torrent->user->id || auth()->user()->group->is_internal)
                @include('torrent.partials.tools')
            @endif

            {{-- Audits Block --}}
            @if (auth()->user()->group->is_modo)
                @include('torrent.partials.audits')
                @include('torrent.partials.downloads')
            @endif

            {{-- MediaInfo Block --}}
            @if ($torrent->mediainfo !== null)
                @include('torrent.partials.mediainfo')
            @endif

            {{-- BDInfo Block --}}
            @if ($torrent->bdinfo !== null)
                @include('torrent.partials.bdinfo')
            @endif

            {{-- Description Block --}}
            @include('torrent.partials.description')

            {{-- Subtitles Block --}}
            @if($torrent->category->movie_meta || $torrent->category->tv_meta)
                @include('torrent.partials.subtitles')
            @endif

            {{-- TipJar Block --}}
            @include('torrent.partials.tipjar')

            {{-- Extra Meta Block --}}
            @include('torrent.partials.extra_meta')
        </div>

        <div class="torrent box container" id="comments">
            {{-- Commments Block --}}
            @include('torrent.partials.comments')
        </div>

        {{-- Modals Block --}}
        @include('torrent.torrent_modals', ['user' => $user, 'torrent' => $torrent])
    </div>
@endsection

@section('javascripts')
    @if (isset($trailer))
        <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce() }}">
          $('.show-trailer').each(function () {
            $(this).off('click')
            $(this).on('click', function (e) {
              e.preventDefault()
              Swal.fire({
                showConfirmButton: false,
                showCloseButton: true,
                background: 'rgb(35,35,35)',
                width: 970,
                html: '<iframe width="930" height="523" src="{{ $trailer }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>',
                title: '<i style="color: #a5a5a5;">Trailer</i>',
                text: ''
              })
            })
          })
        </script>
    @endif

    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce() }}">
      $('.torrent-freeleech-token').on('click', function (event) {
        event.preventDefault();
        let form = $(this).parents('form');
        Swal.fire({
          title: 'Are you sure?',
          text: 'This will use one of your Freeleech Tokens!',
          icon: 'warning',
          showConfirmButton: true,
          showCloseButton: true,
        }).then((result) => {
          if (result.isConfirmed && {{ $torrent->seeders }} == 0) {
            Swal.fire({
              title: 'Are you sure?',
              text: 'This torrent has 0 seeders!',
              icon: 'warning',
              showConfirmButton: true,
              showCancelButton: true,
            }).then((result) => {
              if (result.isConfirmed) {
                form.submit();
              }
            });
          } else if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    </script>
@endsection
