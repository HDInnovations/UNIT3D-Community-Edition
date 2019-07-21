// Auto
$(function() {
    // Check for on keypress
    $('input').on('keyup', function(event) {
        let self = $(this);

        // Keyboard Controls
        let controls = [8, 16, 18, 17, 20, 35, 36, 37, 38, 39, 40, 45, 46, 9, 91, 93, 224, 13, 127, 27, 32];

        // Special Chars
        let specialChars = [43, 61, 186, 187, 188, 189, 190, 191, 192, 219, 220, 221, 222];

        // Numbers
        let numbers = [48, 49, 50, 51, 52, 53, 54, 55, 56, 57];

        let preCombined = controls.concat(numbers);
        let combined = preCombined;

        // Allow Letter
        for (let i = 65; i <= 90; i++) {
            combined.push(i);
        }

        // handle Input
        if ($.inArray(event.which, combined) === -1) {
            event.preventDefault();
        }

        // Handle Autostepper
        if ($.inArray(event.which, controls.concat(specialChars)) === -1) {
            setTimeout(function() {
                if (self.hasClass('last-input')) {
                    $('#submit_verification').focus();
                } else {
                    self.parent()
                        .parent()
                        .next()
                        .find('input')
                        .focus();
                }
            }, 1);
        }
    });
    // Check for copy and paste
    $('input').on('input', function() {
        let regexp = /[^a-zA-Z0-9]/g;
        if (
            $(this)
                .val()
                .match(regexp)
        ) {
            $(this).val(
                $(this)
                    .val()
                    .replace(regexp, '')
            );
        }
    });
});

// Verify
$.ajaxSetup({
    beforeSend: function(xhr, type) {
        if (!type.crossDomain) {
            xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
        }
    },
});

$('.code-inputs').on('webkitAnimationEnd oanimationend msAnimationEnd animationend', function(e) {
    $('.code-inputs')
        .delay(200)
        .removeClass('invalid-shake');
});

$('#submit_verification').click(function(event) {
    event.preventDefault();

    let formData = $('#verification_form').serialize();

    $.ajax({
        url: 'verify',
        type: 'post',
        dataType: 'json',
        data: formData,
        success: function(response, status, data) {
            window.location.href = data.responseJSON.nextUri;
        },
        error: function(response, status, error) {
            if (response.status === 418) {
                let remainingAttempts = response.responseJSON.remainingAttempts;
                let submitTrigger = $('#submit_verification');
                let varificationForm = $('#verification_form');

                $('.code-inputs').addClass('invalid-shake');
                varificationForm[0].reset();
                $('#remaining_attempts').text(remainingAttempts);

                switch (remainingAttempts) {
                    case 0:
                        submitTrigger.addClass('btn-danger');
                        Swal.fire(
                            "{{ trans('auth.verificationLockedTitle') }}",
                            "{{ trans('auth.verificationLockedMessage') }}",
                            'error'
                        );
                        break;

                    case 1:
                        submitTrigger.addClass('btn-danger');
                        Swal.fire(
                            "{{ trans('auth.verificationWarningTitle') }}",
                            "{{ trans('auth.verificationWarningMessage', ['hours' => $hoursToExpire, 'minutes' => $minutesToExpire,]) }}",
                            'error'
                        );
                        break;

                    case 2:
                        submitTrigger.addClass('btn-warning');
                        break;

                    case 3:
                        submitTrigger.addClass('btn-info');
                        break;

                    default:
                        submitTrigger.addClass('btn-success');
                        break;
                }

                if (remainingAttempts === 0) {
                    $('#verification_status_title').html(`<h3>{{ trans('auth.titleFailed') }}</h3>`);

                    varificationForm.fadeOut(100, function() {
                        $('#failed_login_alert').show();

                        setTimeout(function() {
                            $('body').fadeOut(100, function() {
                                location.reload();
                            });
                        }, 2000);
                    });
                }
            }
        },
    });
});

// Resend
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    },
});

$('#resend_code_trigger').click(function(event) {
    event.preventDefault();

    let self = $(this);
    let resultStatus;
    let resultData;
    let endpoint = "{{ route('resend') }}";

    self.addClass('disabled').attr('disabled', true);

    Swal.fire({
        text: 'Sending verification code ...',
        allowOutsideClick: false,
        grow: false,
        animation: false,
        onOpen: () => {
            Swal.showLoading();
            $.ajax({
                type: 'POST',
                url: endpoint,
                success: function(response, status, data) {
                    swalCallback(response.title, response.message, status);
                },
                error: function(response, status, error) {
                    swalCallback(error, error, status);
                },
            });
        },
    });

    function swalCallback(title, message, status) {
        Swal.fire({
            text: title,
            text: message,
            type: status,
            grow: false,
            animation: false,
            allowOutsideClick: false,
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-lg btn-' + status,
            confirmButtonText: "{{ trans('auth.verificationModalConfBtn') }}",
        });
        self.removeClass('disabled').attr('disabled', false);
    }
});
