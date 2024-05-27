<ul class="torrent__tags">
    <li class="torrent__category">
        <a
            class="torrent__category-link"
            href="{{ route('torrents.index', ['categories' => [$torrent->category->id]]) }}"
        >
            {{ $torrent->category->name }}
        </a>
    </li>
    @if ($torrent->resolution)
        <li class="torrent__resolution">
            <a
                class="torrent__resolution-link"
                href="{{ route('torrents.index', ['resolutions' => [$torrent->category->id]]) }}"
            >
                {{ $torrent->resolution->name }}
            </a>
        </li>
    @endif

    @isset($torrent->region)
        <li class="torrent__region">
            <a
                class="torrent__region-link"
                href="{{ route('torrents.index', ['regions' => [$torrent->region->id]]) }}"
            >
                {{ $torrent->region->name }}
            </a>
        </li>
    @endisset

    @isset($torrent->type)
        <li class="torrent__type">
            <a
                class="torrent__type-link"
                href="{{ route('torrents.index', ['types' => [$torrent->type->id]]) }}"
            >
                {{ $torrent->type->name }}
            </a>
        </li>
    @endisset

    @isset($torrent->distributor)
        <li class="torrent__distributor">
            <a
                class="torrent__distributor-link"
                href="{{ route('torrents.index', ['distributors' => [$torrent->distributor->id]]) }}"
            >
                {{ $torrent->distributor->name }}
            </a>
        </li>
    @endisset

    <li class="torrent__size">
        <span class="torrent__size-link" title="{{ $torrent->size }}&#x202F;B">
            {{ $torrent->getSize() }}
        </span>
    </li>
    <li class="torrent__seeders">
        <a
            class="torrent__seeders-link text-green"
            href="{{ route('peers', ['id' => $torrent->id]) }}"
            title="{{ $torrent->seeds_count }} {{ __('torrent.seeders') }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-arrow-up"></i>
            {{ $torrent->seeds_count }}
        </a>
    </li>
    <li class="torrent__leechers">
        <a
            class="torrent__leechers-link text-red"
            href="{{ route('peers', ['id' => $torrent->id]) }}"
            title="{{ $torrent->leeches_count }} {{ __('torrent.leechers') }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-arrow-down"></i>
            {{ $torrent->leeches_count }}
        </a>
    </li>
    <li class="torrent__completed">
        <a
            class="torrent__completed-link text-info"
            href="{{ route('history', ['id' => $torrent->id]) }}"
            title="{{ $torrent->times_completed }} {{ __('torrent.times') }}"
        >
            <i class="{{ config('other.font-awesome') }} fa-check"></i>
            {{ $torrent->times_completed }}
        </a>
    </li>
    <li class="torrent__uploader">
        <x-user_tag :user="$torrent->user" :anon="$torrent->anon" />
    </li>
    <li class="torrent__uploaded-at">
        <time datetime="{{ $torrent->created_at }}" title="{{ $torrent->created_at }}">
            {{ $torrent->created_at->diffForHumans() }}
        </time>
    </li>
    @if ($torrent->seeders === 0)
        <li class="torrent__activity">
            <span class="torrent__activity-link">
                {{ __('torrent.last-seed-activity') }}:
                {{ $last_seed_activity->updated_at ?? __('common.unknown') }}
            </span>
        </li>
    @endif

    <li>
        @include('components.partials._torrent-icons', ['personalFreeleech' => $personal_freeleech])
    </li>
</ul>
