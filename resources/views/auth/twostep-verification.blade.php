@extends('layout.default')

@section('title')
    <title>{{ __('auth.title') }} - {{ config('other.title') }}</title>
@endsection

@section('meta')
    <meta name="description" content="{{ __('auth.title') }} - {{ config('other.title') }}">
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ mix('css/main/twostep.css') }}" crossorigin="anonymous">
@endsection

@section('breadcrumbs')
    <li class="breadcrumb--active">
        {{ __('auth.title') }}
    </li>
@endsection

@php
    switch ($remainingAttempts) {
    case 0:
    case 1:
    $remainingAttemptsClass = 'danger';
    break;

    case 2:
    $remainingAttemptsClass = 'warning';
    break;

    case 3:
    $remainingAttemptsClass = 'info';
    break;

    default:
    $remainingAttemptsClass = 'success';
    break;
    }
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel verification-form-panel">
                    <div class="panel__heading text-center" id="verification_status_title">
                        <h3>
                            {{ __('auth.title') }}
                        </h3>
                        <p class="text-center">
                            <em>
                                {{ __('auth.subtitle') }}
                            </em>
                        </p>
                    </div>
                    <div class="panel__body">
                        <form id="verification_form" class="form-horizontal" method="POST">
                            @csrf
                            <div class="form-group margin-bottom-1 code-inputs">
                                <div class="col-xs-3">
                                    <div class="{{ $errors->has('v_input_1') ? ' has-error' : '' }}">
                                        <label for="v_input_1" class="sr-only control-label">
                                            {{ __('auth.inputAlt1') }}
                                        </label>
                                        <input type="text" id="v_input_1" class="form-control text-center required"
                                               required
                                               name="v_input_1" value="" autofocus maxlength="1" minlength="1"
                                               tabindex="1"
                                               placeholder="•">
                                        @if ($errors->has('v_input_1'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('v_input_1') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="{{ $errors->has('v_input_2') ? ' has-error' : '' }}">
                                        <label for="v_input_2" class="sr-only control-label">
                                            {{ __('auth.inputAlt2') }}
                                        </label>
                                        <input type="text" id="v_input_2" class="form-control text-center required"
                                               required
                                               name="v_input_2" value="" maxlength="1" minlength="1" tabindex="2"
                                               placeholder="•">
                                        @if ($errors->has('v_input_2'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('v_input_2') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="{{ $errors->has('v_input_3') ? ' has-error' : '' }}">
                                        <label for="v_input_3" class="sr-only control-label">
                                            {{ __('auth.inputAlt3') }}
                                        </label>
                                        <input type="text" id="v_input_3" class="form-control text-center required"
                                               required
                                               name="v_input_3" value="" maxlength="1" minlength="1" tabindex="3"
                                               placeholder="•">
                                        @if ($errors->has('v_input_3'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('v_input_3') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-xs-3">
                                    <div class="{{ $errors->has('v_input_4') ? ' has-error' : '' }}">
                                        <label for="v_input_4" class="sr-only control-label">
                                            {{ __('auth.inputAlt4') }}
                                        </label>
                                        <input type="text" id="v_input_4"
                                               class="form-control text-center required last-input " required
                                               name="v_input_4"
                                               value="" maxlength="1" minlength="1" tabindex="4" placeholder="•">
                                        @if ($errors->has('v_input_4'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('v_input_4') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-xs-8 col-xs-offset-2 text-center submit-container">
                                        <button type="submit"
                                                class="form__button form__button--filled"
                                                id="submit_verification" tabindex="5">
                                            {{ __('auth.verifyButton') }}
                                        </button>
                                    </div>
                                    <div class="col-xs-12 text-center">
                                        <p class="text-{{ $remainingAttemptsClass }}">
                                            <small>
                                                <span id="remaining_attempts">{{ $remainingAttempts }}</span>
                                                {{ trans_choice('auth.attemptsRemaining', $remainingAttempts) }}
                                            </small>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="col-xs-12 text-center">
                            <a class="form__button form__button--filled" id="resend_code_trigger" href="#" tabindex="6">
                                {{ __('auth.missingCode') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @php
        $minutesToExpire = config('auth.TwoStepExceededCountdownMinutes');
        $hoursToExpire = $minutesToExpire / 60
    @endphp
@endsection

@section('javascripts')
    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce() }}" crossorigin="anonymous">
      document.addEventListener('DOMContentLoaded', function() {
        // Check for on keypress
        document.querySelectorAll('input').forEach(el => el.addEventListener('keyup', function (event) {
          // Keyboard Controls
          const controls = [8, 16, 18, 17, 20, 35, 36, 37, 38, 39, 40, 45, 46, 9, 91, 93, 224, 13, 127,
            27, 32
          ]
          // Special Chars
          const specialChars = [43, 61, 186, 187, 188, 189, 190, 191, 192, 219, 220, 221, 222]
          // Numbers
          const numbers = [48, 49, 50, 51, 52, 53, 54, 55, 56, 57]
          const preCombined = controls.concat(numbers)
          const combined = preCombined
          // Allow Letter
          for (let i = 65; i <= 90; i++) {
            combined.push(i)
          }
          // handle Input
          if (combined.indexOf(event.which) === -1) {
            event.preventDefault()
          }
          // Handle Autostepper
          if (controls.concat(specialChars).indexOf(event.which) === -1) {
            setTimeout(() => {
              if (this.classList.contains('last-input')) {
                document.getElementById('submit_verification').focus()
              } else {
                this.parentElement.parentElement.nextElementSibling.querySelector(':scope input').focus()
              }
            }, 1)
          }
        }))
        // Check for cop and paste
        document.querySelectorAll('input').forEach(el => el.addEventListener('input', function () {
          const regexp = /[^a-zA-Z0-9]/g
          if (this.value.match(regexp)) {
            this.value = this.value.replace(regexp, '')
          }
        }))
      })

    </script>

    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce() }}" crossorigin="anonymous">
      ['webkitAnimationEnd', 'oanimationend', 'msAnimationEnd', 'animationend'].forEach(function (e) {
          document.querySelectorAll('.code-inputs').forEach(el => el.addEventListener(e, function (e) {
              document.querySelectorAll('.code-inputs').forEach(el => el.delay(200).classList.remove('invalid-shake'))
          }))
      })
      document.getElementById('submit_verification').addEventListener('click', function (event) {
        event.preventDefault()
        const formData = new URLSearchParams(new FormData(document.getElementById('verification_form'))).toString()
        fetch('/twostep/verify', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
          .then(response => {
            if (response.ok) {
                response.text().then(data => {
                    console.log(data)
                    // window.location.href = data.nextUri
                })
            } else if (response.statusCode === 418) {
              response.text().then(data => {
                  console.log(data)
                const remainingAttempts = data.remainingAttempts
                const submitTrigger = document.getElementById('submit_verification')
                const varificationForm = document.getElementById('verification_form')
                document.querySelectorAll('.code-inputs').forEach(el => el.classList.add('invalid-shake'))
                varificationForm[0].reset()
                document.getElementById('remaining_attempts').value = remainingAttempts
                switch (remainingAttempts) {
                  case 0:
                    submitTrigger.classList.add('btn-danger')
                    Swal.fire(
                      "{{ __('auth.verificationLockedTitle') }}",
                      "{{ __('auth.verificationLockedMessage') }}",
                      'error'
                    )
                    break
                  case 1:
                    submitTrigger.classList.add('btn-danger')
                    Swal.fire(
                      "{{ __('auth.verificationWarningTitle') }}",
                      "{{ __('auth.verificationWarningMessage', ['hours' => $hoursToExpire, 'minutes' => $minutesToExpire]) }}",
                      'error'
                    )
                    break
                  case 2:
                    submitTrigger.classList.add('btn-warning')
                    break
                  case 3:
                    submitTrigger.classList.add('btn-info')
                    break
                  default:
                    submitTrigger.classList.add('btn-success')
                    break
                  }
                  if (remainingAttempts === 0) {
                    document.getElementById('verification_status_title').innerHTML = '<h3>{{ __('auth.titleFailed ') }}</h3>'
                    varificationForm.style.opacity = '0'
                    document.getElementById('failed_login_alert').style.display = ''
                    document.querySelector('body').style.opacity = '0';
                    location.reload()
                  }
              })
            }
          })
      })

    </script>

    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce() }}" crossorigin="anonymous">
      document.getElementById('resend_code_trigger').addEventListener('click', function (event) {
        event.preventDefault()
        const self = this
        self.classList.add('disabled')
        self.setAttribute('disabled', true)
        Swal.fire({
          text: 'Sending verification code ...',
          allowOutsideClick: false,
          grow: false,
          didOpen: () => {
            Swal.showLoading()
            fetch('/twostep/resend', {
              method: 'POST',
              headers: {
                'accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
            })
              .then(response => {
                if (response.ok) {
                  response.text().then(data => {
                      console.log(data)
                    swalCallback(data.title, data.message, response.status)
                  })
                } else {
                  swalCallback(response.statusText, response.statusText, response.statusCode)
                }
              })
          }
        })

        function swalCallback (title, message, status) {
          Swal.fire({
            text: title,
            text: message,
            icon: status,
            grow: false,
            animation: false,
            allowOutsideClick: false,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-lg btn-' + status,
            confirmButtonText: "{{ __('auth.verificationModalConfBtn') }}",
          })
          self.classList.remove('disabled')
          self.setAttribute('disabled', false)
        }
      })

    </script>
@endsection
