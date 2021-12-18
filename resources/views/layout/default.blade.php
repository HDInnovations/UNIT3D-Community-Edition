<!DOCTYPE html>
<html lang="{{ auth()->user()->locale }}">

<head>
    @include('partials.head')
</head>

@if (auth()->user()->nav == 0)

    <body hoe-navigation-type="vertical-compact" hoe-nav-placement="left" theme-layout="wide-layout">
    @else

        <body hoe-navigation-type="vertical" hoe-nav-placement="left" theme-layout="wide-layout">
        @endif
        <div id="hoeapp-wrapper" class="hoe-hide-lpanel" hoe-device-type="desktop">
            @include('partials.top_nav')
            <div id="hoeapp-container" hoe-color-type="lpanel-bg5" hoe-lpanel-effect="shrink">
                @include('partials.side_nav')
                <section id="main-content">
                    @include('partials.userbar')
                    @include('partials.breadcrumb')
                    @include('cookie-consent::index')
                    @include('partials.alerts')
                    @if (Session::has('achievement'))
                        @include('partials.achievement_modal')
                    @endif
                    @if ($errors->any())
                        <div id="ERROR_COPY" style="display: none;">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    @endif
                    @yield('content')
                    @include('partials.footer')
                </section>
            </div>
        </div>

        <script src="{{ mix('js/app.js') }}" crossorigin="anonymous"></script>
        <script src="{{ mix('js/unit3d.js') }}" crossorigin="anonymous"></script>
        <script src="{{ mix('js/alpine.js') }}" crossorigin="anonymous" defer></script>
        <script src="{{ mix('js/virtual-select.js') }}" crossorigin="anonymous"></script>

        @if (config('other.freeleech') == true || config('other.invite-only') == false || config('other.doubleup') == true)
            <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
              function timer () {
                return {
                  seconds: '00',
                  minutes: '00',
                  hours: '00',
                  days: '00',
                  distance: 0,
                  countdown: null,
                  promoTime: new Date('{{ config('other.freeleech_until') }}').getTime(),
                  now: new Date().getTime(),
                  start: function () {
                    this.countdown = setInterval(() => {
                      // Calculate time
                      this.now = new Date().getTime()
                      this.distance = this.promoTime - this.now
                      // Set Times
                      this.days = this.padNum(Math.floor(this.distance / (1000 * 60 * 60 * 24)))
                      this.hours = this.padNum(Math.floor((this.distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)))
                      this.minutes = this.padNum(Math.floor((this.distance % (1000 * 60 * 60)) / (1000 * 60)))
                      this.seconds = this.padNum(Math.floor((this.distance % (1000 * 60)) / 1000))
                      // Stop
                      if (this.distance < 0) {
                        clearInterval(this.countdown)
                        this.days = '00'
                        this.hours = '00'
                        this.minutes = '00'
                        this.seconds = '00'
                      }
                    }, 100)
                  },
                  padNum: function (num) {
                    var zero = ''
                    for (var i = 0; i < 2; i++) {
                      zero += '0'
                    }
                    return (zero + num).slice(-2)
                  }
                }
              }
            </script>
        @endif

        @if (Session::has('achievement'))
            <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
              $('#modal-achievement').modal('show')
            </script>
        @endif

        @foreach (['warning', 'success', 'info'] as $key)
            @if (Session::has($key))
                <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
                  const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  })

                  Toast.fire({
                    icon: '{{ $key }}',
                    title: '{{ Session::get($key) }}'
                  })

                </script>
            @endif
        @endforeach

        @if (Session::has('errors'))
            <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
              Swal.fire({
                title: '<strong style=" color: rgb(17,17,17);">Error</strong>',
                icon: 'error',
                html: jQuery('#ERROR_COPY').html(),
                showCloseButton: true,
                willOpen: function (el) {
                  $(el).find('textarea').remove()
                }
              })

            </script>
        @endif

        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
          window.addEventListener('success', event => {
            const Toast = Swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 3000
            })

            Toast.fire({
              icon: 'success',
              title: event.detail.message
            })
          })
        </script>

        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
          window.addEventListener('error', event => {
            Swal.fire({
              title: '<strong style=" color: rgb(17,17,17);">Error</strong>',
              icon: 'error',
              html: event.detail.message,
              showCloseButton: true,
            })
          })
        </script>

        @yield('javascripts')
        @yield('scripts')
        @livewireScripts(['nonce' => Bepsvpt\SecureHeaders\SecureHeaders::nonce()])

        <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
          Livewire.on('paginationChanged', () => {
            window.scrollTo({
              top: 15,
              left: 15,
              behaviour: 'smooth'
            })
          })
        </script>
        </body>
</html>
