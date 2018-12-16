@if ($poll && $poll->voters->where('user_id', '=', auth()->user()->id)->isEmpty())
    <div class="col-md-10 col-sm-10 col-md-offset-1">
        <div class="clearfix visible-sm-block"></div>
        <div class="panel panel-chat shoutbox">
            <div class="panel-heading">
                <h4>Latest Poll ({{ $poll->title }}) (Vote Now!)</h4>
            </div>
            <div class="panel-body">
                <div class="forum-categories">
                    <div class="forum-category">
                        <div class="forum-category-title col-md-12">
                            <div class="forum-category-childs">
                                <form class="form-horizontal" method="POST" action="/poll/vote">
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

                                    {!! csrf_field() !!}

                                    @if ($poll->multiple_choice)
                                        @foreach ($poll->options as $option)
                                            <a class="forum-category-childs-forum col-md-4">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="option[]"
                                                               value="{{ $option->id }}">
                                                        <span class="badge-user">{{ $option->name }}</span>
                                                    </label>
                                                </div>
                                            </a>
                                        @endforeach
                                    @else
                                        @foreach ($poll->options as $option)
                                            <a class="forum-category-childs-forum col-md-4">
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="option[]" value="{{ $option->id }}"
                                                               required>
                                                        <span class="badge-user">{{ $option->name }}</span>
                                                    </label>
                                                </div>
                                            </a>
                                        @endforeach
                                    @endif

                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit"
                                                    class="btn btn-primary">@lang('poll.vote')</button>
                                        </div>
                                    </div>
                                </form>
                                @if ($poll->multiple_choice)
                                    <span class="badge-user text-bold text-red">@lang('poll.multiple-choice')</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

