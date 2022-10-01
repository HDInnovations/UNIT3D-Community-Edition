@extends('layout.default')

@section('breadcrumbs')
    <li class="breadcrumbV2">
        <a href="{{ route('pages.index') }}" class="breadcrumb__link">
            Pages
        </a>
    </li>
    <li class="breadcrumb--active">
        {{ $page->name }}
    </li>
@endsection

@section('page', 'page__page--show')

@section('main')
    <section class="panelV2">
        <h2 class="panel__heading">{{ $page->name }}</h2>
        <div class="panel__body">
            @joypixels($page->getContentHtml())
        </div>
    </section>
@endsection

@section('sidebar')
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.info') }}</h2>
        <dl class="key-value">
            <dt>{{ __('common.created_at') }}</dt>
            <dd>{{ $page->created_at }}</dd>
            <dt>{{ __('torrent.updated_at') }}</dt>
            <dd>{{ $page->updated_at }}</dd>
    </section>
@endsection

@section('javascripts')
    @if(request()->url() === config('other.rules_url') && auth()->user()->read_rules == 0)
        <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
          window.onscroll = function () {
            let scrollHeight, totalHeight
            scrollHeight = document.body.scrollHeight
            totalHeight = window.scrollY + window.innerHeight

            if (totalHeight >= scrollHeight) {
              Swal.fire({
                title: '<strong>Read The <u>Rules?</u></strong>',
                text: 'Do You Fully Understand Our Rules?',
                icon: 'question',
                confirmButtonText: '<i class="fa fa-thumbs-up"></i> I Do!',
              }).then(function () {
                $.ajax({
                  url: '/users/accept-rules',
                  type: 'post',
                  data: {
                    _token: '{{ csrf_token() }}'
                  },
                  success: function (response) {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000
                    })

                    Toast.fire({
                      icon: 'success',
                      title: 'Thanks For Accepting Our Rules!'
                    })
                  },
                  failure: function (response) {
                    const Toast = Swal.mixin({
                      toast: true,
                      position: 'top-end',
                      showConfirmButton: false,
                      timer: 3000
                    })

                    Toast.fire({
                      icon: 'error',
                      title: 'Something Went Wrong!'
                    })
                  }
                })
              })
            }
          }

        </script>
    @endif
@endsection
