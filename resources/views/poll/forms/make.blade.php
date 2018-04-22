<form method="POST" action="/poll">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{ csrf_field() }}

    <div class="form-group">
        <label for="stitle">{{ trans('poll.title') }}:</label>
        <input type="text" name="title" class="form-control" value="" required>
    </div>

    <div class="form-group">
        <label for="option1">{{ trans('poll.option') }} 1:</label>
        <input type="text" name="options[]" class="form-control" value="">
    </div>

    <div class="form-group">
        <label for="option2">{{ trans('poll.option') }} 2:</label>
        <input type="text" name="options[]" class="form-control" value="">
    </div>

    <div class="more-options"></div>

    <div class="form-group">
        <button id="add" class="btn btn-primary">{{ trans('poll.add-option') }}</button>
        <button id="del" class="btn btn-primary">{{ trans('poll.delete-option') }}</button>
    </div>

    <hr>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="ip_checking" value="1">{{ trans('poll.ip-checking') }} <span
                    class="text-red">({{ strtoupper(trans('poll.ip-checking-warrning')) }})</span>
        </label>
    </div>

    <div class="checkbox">
        <label>
            <input type="checkbox" name="multiple_choice" value="1">{{ trans('poll.multiple-choice') }}
        </label>
    </div>

    <div class="form-group">
        <button type="submit" class="btn btn-primary">{{ trans('poll.create-poll') }}</button>
    </div>
</form>


@section('javascripts')
    <script type="text/javascript">
        var options = 2;

        $('#add').on('click', function (e) {
            e.preventDefault();
            options = options + 1;
            var optionHTML = '<div class="form-group extra-option"><label for="option' + options + '">{{ trans('poll.option') }} ' + options + ':</label><input type="text" name="options[]" class="form-control" value="" required></div>';
            $('.more-options').append(optionHTML);
        });

        $('#del').on('click', function (e) {
            e.preventDefault();
            options = (options > 2) ? options - 1 : 2;
            $('.extra-option').last().remove();
        });
    </script>
@endsection
