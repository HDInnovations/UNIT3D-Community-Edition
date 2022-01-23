<form method="POST" action="{{ route('staff.polls.store') }}">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @csrf

    <div class="form-group">
        <label for="stitle">{{ __('common.title') }}:</label>
        <label>
            <input type="text" name="title" class="form-control" value="" required>
        </label>
    </div>

    <div class="form-group">
        <label for="option1">{{ __('poll.option') }} 1:</label>
        <label>
            <input type="text" name="options[]" class="form-control" value="">
        </label>
    </div>

    <div class="form-group">
        <label for="option2">{{ __('poll.option') }} 2:</label>
        <label>
            <input type="text" name="options[]" class="form-control" value="">
        </label>
    </div>

    <div class="more-options"></div>

    <div class="form-group">
        <button id="add" class="btn btn-primary">{{ __('poll.add-option') }}</button>
        <button id="del" class="btn btn-primary">{{ __('poll.delete-option') }}</button>
    </div>

    <hr>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="multiple_choice" value="1">{{ __('poll.multiple-choice') }}
        </label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ __('poll.create-poll') }}</button>
    </div>
</form>

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">
      let options = 2
      const langOption = "<?php echo __('poll.option') ?>"

      $('#add').on('click', function (e) {
        e.preventDefault()
        options += 1
        const optionHTML = '<div class="form-group extra-option"><label for="option' + options + '">' + langOption
          + options
          + ':</label><input type="text" name="options[]" class="form-control" value="" required></div>'
        $('.more-options').append(optionHTML)
      })

      $('#del').on('click', function (e) {
        e.preventDefault()
        options = (options > 2) ? options - 1 : 2
        $('.extra-option').last().remove()
      })

    </script>
@endsection
