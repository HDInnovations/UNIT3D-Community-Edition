<form class="form-horizontal" method="POST" action="/polls/vote">
    @csrf
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if ($poll->multiple_choice)
        @foreach ($poll->options as $option)
            <p class="form__group">
                <input
                    id="option{{ $option->id }}"
                    class="form__checkbox"
                    type="checkbox"
                    name="option[]"
                    value="{{ $option->id }}"
                >
                <label class="form__label" for="option{{ $option->id }}">
                    {{ $option->name }}
                </span>
            </p>
        @endforeach
    @else
        @foreach ($poll->options as $option)
            <p class="form__group">
                <input
                    id="option{{ $option->id }}"
                    class="form__radio"
                    type="radio"
                    name="option[]"
                    value="{{ $option->id }}"
                    required
                >
                <label class="form__label" for="option{{ $option->id }}">
                    {{ $option->name }}
                </span>
            </p>
        @endforeach
    @endif
    <p class="form__group">
        <button class="form__button form__button--filled">
            {{ __('poll.vote') }}
        </button>
        <a
            class="form__button form__button--outlined"
            href="{{ route('poll_results', ['id' => $poll->id]) }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-chart-bar"></i>
            {{ __('poll.results') }}
        </a>
    </p>
</form>
