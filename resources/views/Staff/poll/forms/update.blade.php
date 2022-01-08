<form method="POST" action="{{ route('staff.polls.update', ['id' => $poll->id]) }}">
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
        <label for="stitle">{{ __('poll.title') }}:</label>
        <label>
            <input readonly type="number" name="poll-id" style="visibility: hidden;" value="{{ $poll->id }}">
            <input type="text" name="title" class="form-control" value="{{ $poll->title }}" required>
        </label>
    </div>

    @foreach($poll->options as $key => $option)
        <div class="form-group <?php echo(++$key) >= 3 ? 'extra-option' : '' ?>">
            <label for="{{ 'option' . $key }}">{{ __('poll.option') }} {{ $key }}:</label>
            <label>
                <input readonly type="number" name="option-id[]" style="visibility: hidden;" value="{{ $option->id }}">
                <input type="text" name="option-content[]" class="form-control" value="{{ $option->name }}">
            </label>
        </div>

    @endforeach

    <div class="more-options"></div>

    <div class="form-group">
        <button id="add" class="btn btn-primary">{{ __('poll.add-option') }}</button>
        <button id="del" class="btn btn-primary">{{ __('poll.delete-option') }}</button>
    </div>

    <hr>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="multiple_choice" @if ($poll->multiple_choice) checked @endif >
            {{ __('poll.multiple-choice') }}
        </label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ __('poll.edit-poll') }}</button>
    </div>
</form>

@section('javascripts')
    <script nonce="{{ Bepsvpt\SecureHeaders\SecureHeaders::nonce('script') }}">

      let options = parseInt({{ $poll->options->count() }}) // Get the size of options passing in
      const langOption = "{{ __('poll.option') }} "

      $('#add').on('click', function (e) {
        e.preventDefault()
        options += 1
        const optionHTML = `<div class="form-group extra-option"><label for="option${options}">${langOption}${options}:</label><input type="text" name="new-option-content[]" class="form-control" value="" required></div>`
        $('.more-options').append(optionHTML)
      })

      $('#del').on('click', function (e) {
        e.preventDefault()
        options = (options > 2) ? options - 1 : 2
        $('.extra-option').last().remove()
      })

    </script>
@endsection
