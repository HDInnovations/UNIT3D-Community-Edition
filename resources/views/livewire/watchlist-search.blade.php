<div>
    <div class="mb-10 form-inline pull-right">
        <div class="form-group">
            {{ __('common.quantity') }}
            <select wire:model="perPage" class="form-control">
                <option>25</option>
                <option>50</option>
                <option>100</option>
            </select>
        </div>

        <div class="form-group">
            <input type="text" wire:model="search" class="form-control" style="width: 275px;"
                   placeholder="Search by message"/>
        </div>
    </div>
    <div class="box-body no-padding">
        <table class="table vertical-align table-hover">
            <tbody>
            <tr>
                <th style="width: 15%;">
                    <div sortable wire:click="sortBy('user_id')"
                         :direction="$sortField === 'user_id' ? $sortDirection : null" role="button">
                        Watching
                        @include('livewire.includes._sort-icon', ['field' => 'user_id'])
                    </div>
                </th>
                <th style="width: 15%;">
                    <div sortable wire:click="sortBy('staff_id')"
                         :direction="$sortField === 'staff_id' ? $sortDirection : null" role="button">
                        Watched By
                        @include('livewire.includes._sort-icon', ['field' => 'staff_id'])
                    </div>
                </th>
                <th style="width: 40%;" class="hidden-sm hidden-xs">
                    <div sortable wire:click="sortBy('message')"
                         :direction="$sortField === 'message' ? $sortDirection : null"
                         role="button">
                        Message
                        @include('livewire.includes._sort-icon', ['field' => 'message'])
                    </div>
                </th>
                <th style="width: 15%;">
                    <div sortable wire:click="sortBy('created_at')"
                         :direction="$sortField === 'created_at' ? $sortDirection : null" role="button">
                        Created At
                        @include('livewire.includes._sort-icon', ['field' => 'created_at'])
                    </div>
                </th>
                <th style="width: 15%;">{{ __('common.action') }}</th>
            </tr>
            @foreach ($watchedUsers as $watching)
                <tr>
                    <td>
                        <a href="{{ route('users.show', ['username' => $watching->user->username]) }}">
							<span class="badge-user text-bold">
                                {{ $watching->user->username }}
                            </span>
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('users.show', ['username' => $watching->author->username]) }}">
							<span class="badge-user text-bold">
                                {{ $watching->author->username }}
                            </span>
                        </a>
                    </td>
                    <td class="hidden-sm hidden-xs">{{ $watching->message }}</td>
                    <td class="hidden-sm hidden-xs">{{ $watching->created_at }}</td>
                    <td>
                        <form action="{{ route('staff.watchlist.destroy', ['id' => $watching->id]) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-xs btn-info" type="submit">
                                <i class="{{ config('other.font-awesome') }} fa-eye-slash"></i> Unwatch
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if (! $watchedUsers->count())
            <div class="margin-10">
                {{ __('common.no-result') }}
            </div>
        @endif
        <br>
        <div class="text-center">
            {{ $watchedUsers->links() }}
        </div>
    </div>
</div>
