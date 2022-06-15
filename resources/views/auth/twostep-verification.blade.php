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
                    <div class="panel-heading text-center" id="verification_status_title">
                        <h3>
                            {{ __('auth.title') }}
                        </h3>
                        <p class="text-center">
                            <em>
                                {{ __('auth.subtitle') }}
                            </em>
                        </p>
                    </div>
                    <div class="panel-body">
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
                                                class="btn btn-lg btn-{{ $remainingAttemptsClass }} btn-block"
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
                            <a class="btn btn-link" id="resend_code_trigger" href="#" tabindex="6">
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
      $(function () {
        // Check for on keypress
        $('input').on('keyup', function (event) {
          const self = $(this)
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
          if ($.inArray(event.which, combined) === -1) {
            event.preventDefault()
          }
          // Handle Autostepper
          if ($.inArray(event.which, controls.concat(specialChars)) === -1) {
            setTimeout(function () {
              if (self.hasClass('last-input')) {
                $('#submit_verification').focus()
              } else {
                self.parent().parent().next().find('input').focus()
              }
            }, 1)
          }
        })
        // Check for cop and paste
        $('input').on('input', function () {
          const regexp = /[^a-zA-Z0-9]/g
          if ($(this).val().match(regexp)) {
            $(this).val($(this).val().replace(regexp, ''))
          }
        })
      })

    </script>

    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce() }}" crossorigin="anonymous">
      $('.code-inputs').on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function (e) {
        $('.code-inputs').delay(200).removeClass('invalid-shake')
      })
      $('#submit_verification').click(function (event) {
        event.preventDefault()
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })
        const formData = $('#verification_form').serialize()
        $.ajax({
          url: '/twostep/verify',
          type: 'post',
          dataType: 'json',
          data: formData,
          success: function (response, status, data) {
            window.location.href = data.responseJSON.nextUri
          },
          error: function (response, status, error) {
            if (response.status === 418) {
              const remainingAttempts = response.responseJSON.remainingAttempts
              const submitTrigger = $('#submit_verification')
              const varificationForm = $('#verification_form')
              $('.code-inputs').addClass('invalid-shake')
              varificationForm[0].reset()
              $('#remaining_attempts').text(remainingAttempts)
              switch (remainingAttempts) {
                case 0:
                  submitTrigger.addClass('btn-danger')
                  Swal.fire(
                    "{{ __('auth.verificationLockedTitle') }}",
                    "{{ __('auth.verificationLockedMessage') }}",
                    'error'
                  )
                  break
                case 1:
                  submitTrigger.addClass('btn-danger')
                  Swal.fire(
                    "{{ __('auth.verificationWarningTitle') }}",
                    "{{ __('auth.verificationWarningMessage', ['hours' => $hoursToExpire, 'minutes' => $minutesToExpire]) }}",
                    'error'
                  )
                  break
                case 2:
                  submitTrigger.addClass('btn-warning')
                  break
                case 3:
                  submitTrigger.addClass('btn-info')
                  break
                default:
                  submitTrigger.addClass('btn-success')
                  break
              }
              if (remainingAttempts === 0) {
                $('#verification_status_title').html('<h3>{{ __('auth.titleFailed ') }}</h3>')
                varificationForm.fadeOut(100, function () {
                  $('#failed_login_alert').show()
                  setTimeout(function () {
                    $('body').fadeOut(100, function () {
                      location.reload()
                    })
                  }, 2000)
                })
              }

            }

          }
        })
      })
      $.ajaxSetup({
        headers: {
          'X-CSRF-Token': $('meta[name=_token]').attr('content')
        }
      })

    </script>

    <script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce() }}" crossorigin="anonymous">
      $('#resend_code_trigger').click(function (event) {
        event.preventDefault()
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        })
        const self = $(this)
        let resultStatus
        let resultData
        const endpoint = '/twostep/resend'
        self.addClass('disabled')
          .attr('disabled', true)
        Swal.fire({
          text: 'Sending verification code ...',
          allowOutsideClick: false,
          grow: false,
          animation: false,
          onOpen: () => {
            Swal.showLoading()
            $.ajax({
              type: 'post',
              url: endpoint,
              success: function (response, status, data) {
                swalCallback(response.title, response.message, status)
              },
              error: function (response, status, error) {
                swalCallback(error, error, status)
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
          self.removeClass('disabled').attr('disabled', false)
        }
      })

    </script>
@endsection
