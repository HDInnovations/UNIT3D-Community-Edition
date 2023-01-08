<div class="container" x-data="{ open: false }">
    <section class="panelV2">
        <h2 class="panel__heading">{{ __('common.search') }}</h2>
        <div class="panel__body" style="padding: 5px;">
            <div class="form__group--horizontal" style="display: flex; align-items: center;">
                <p class="form__group">
                    <input wire:model="name" class="form__text" placeholder="" autofocus>
                    <label class="form__label form__label--floating">{{ __('torrent.name') }}</label>
                </p>
                <button class="form__button form__button--outlined form__button--centered" style="white-space: nowrap; padding: 12px 12px;" @click="open = ! open"
                        x-text="open ? '{{ __('common.search-hide') }}' : '{{ __('common.search-advanced') }}'">
                </button>
            </div>
            <form class="form" x-cloak x-show="open">
                <div class="form__group--short-horizontal">
                    <p class="form__group">
                        <input wire:model="description" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">{{ __('torrent.description') }}</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="mediainfo" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">{{ __('torrent.media-info') }}</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="keywords" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">{{ __('torrent.keywords') }}</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="uploader" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">{{ __('torrent.uploader') }}</label>
                    </p>
                </div>
                <div class="form__group--short-horizontal">
                    <p class="form__group">
                        <input wire:model="startYear" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">{{ __('torrent.start-year') }}</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="endYear" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">{{ __('torrent.end-year') }}</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="playlistId" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">Playlist ID</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="collectionId" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">Collection ID</label>
                    </p>
                </div>
                <div class="form__group--short-horizontal">
                    <div class="row">
                        <div class="col-sm-6 col-xs-12 adv-search-region">
                            @php $regions = cache()->remember('regions', 3_600, fn () => App\Models\Region::all()->sortBy('position')) @endphp
                            <div id="regions" wire:ignore></div>
                        </div>
                        <div class="col-sm-6 col-xs-12 adv-search-distributor">
                            @php $distributors = cache()->remember('distributors', 3_600, fn () => App\Models\Distributor::all()->sortBy('position')) @endphp
                            <div id="distributors" wire:ignore></div>
                        </div>
                    </div>
                </div>
                <div class="form__group--short-horizontal">
                    <p class="form__group">
                        <input wire:model="tmdbId" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">TMDb ID</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="imdbId" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">IMDb ID</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="tvdbId" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">TVDb ID</label>
                    </p>
                    <p class="form__group">
                        <input wire:model="malId" class="form__text" placeholder="">
                        <label class="form__label form__label--floating">MAL ID</label>
                    </p>
                </div>
                <div class="form__group--short-horizontal">
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.category') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                @php $categories = cache()->remember('categories', 3_600, fn () => App\Models\Category::all()->sortBy('position')) @endphp
                                @foreach ($categories as $category)
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                    class="form__checkbox"
                                                    type="checkbox"
                                                    value="{{ $category->id }}"
                                                    wire:model="categories"
                                            >
                                            {{ $category->name }}
                                        </label>
                                    </p>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.type') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                @php $types = cache()->remember('types', 3_600, fn () => App\Models\Type::all()->sortBy('position')) @endphp
                                @foreach ($types as $type)
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                    class="form__checkbox"
                                                    type="checkbox"
                                                    value="{{ $type->id }}"
                                                    wire:model="types"
                                            >
                                            {{ $type->name }}
                                        </label>
                                    </p>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.resolution') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                @php $resolutions = cache()->remember('resolutions', 3_600, fn () => App\Models\Resolution::all()->sortBy('position')) @endphp
                                @foreach ($resolutions as $resolution)
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                    class="form__checkbox"
                                                    type="checkbox"
                                                    value="{{ $resolution->id }}"
                                                    wire:model="resolutions"
                                            >
                                            {{ $resolution->name }}
                                        </label>
                                    </p>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.genre') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                @php $genres = cache()->remember('genres', 3_600, fn () => App\Models\Genre::all()->sortBy('name')) @endphp
                                @foreach ($genres as $genre)
                                    <p class="form__group">
                                        <label class="form__label">
                                            <input
                                                    class="form__checkbox"
                                                    type="checkbox"
                                                    value="{{ $genre->id }}"
                                                    wire:model="genres"
                                            >
                                            {{ $genre->name }}
                                        </label>
                                    </p>
                                @endforeach
                            </div>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">Buff</legend>
                            <div class="form__fieldset-checkbox-container">
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="0"
                                                wire:model="free"
                                        >
                                        0% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="25"
                                                wire:model="free"
                                        >
                                        25% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="50"
                                                wire:model="free"
                                        >
                                        50% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="75"
                                                wire:model="free"
                                        >
                                        75% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="100"
                                                wire:model="free"
                                        >
                                        100% Freeleech
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="doubleup"
                                        >
                                        Double Upload
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="featured"
                                        >
                                        Featured
                                    </label>
                                </p>
                            </div>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">Tags</legend>
                            <div class="form__fieldset-checkbox-container">
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="internal"
                                        >
                                        {{ __('torrent.internal') }}
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="personalRelease"
                                        >
                                        {{ __('torrent.personal-release') }}
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="stream"
                                        >
                                        {{ __('torrent.stream-optimized') }}
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="sd"
                                        >
                                        {{ __('torrent.sd-content') }}
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="highspeed"
                                        >
                                        {{ __('common.high-speeds') }}
                                    </label>
                                </p>
                            </div>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.health') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="alive"
                                        >
                                        {{ __('torrent.alive') }}
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="dying"
                                        >
                                        Dying
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="dead"
                                        >
                                        Dead
                                    </label>
                                </p>
                            </div>
                        </fieldset>
                    </div>
                    <div class="form__group">
                        <fieldset class="form__fieldset">
                            <legend class="form__legend">{{ __('torrent.history') }}</legend>
                            <div class="form__fieldset-checkbox-container">
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="notDownloaded"
                                        >
                                        Not Downloaded
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="downloaded"
                                        >
                                        Downloaded
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="seeding"
                                        >
                                        Seeding
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="leeching"
                                        >
                                        Leeching
                                    </label>
                                </p>
                                <p class="form__group">
                                    <label class="form__label">
                                        <input
                                                class="form__checkbox"
                                                type="checkbox"
                                                value="1"
                                                wire:model="incomplete"
                                        >
                                        Incomplete
                                    </label>
                                </p>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script nonce="{{ HDVinnie\SecureHeaders\SecureHeaders::nonce('script') }}">
  document.addEventListener('livewire:load', function () {
    let myRegions = [
        @foreach($regions as $region)
        {
            label: "{{ $region->name }}", value: "{{ $region->id }}"
        },
        @endforeach
    ]
    VirtualSelect.init({
      ele: '#regions',
      options: myRegions,
      multiple: true,
      search: true,
      placeholder: "{{__('Select Regions')}}",
      noOptionsText: "{{__('No results found')}}",
    })

    let regions = document.querySelector('#regions')
    regions.addEventListener('change', () => {
      let data = regions.value
      @this.set('regions', data)
    })

    let myDistributors = [
        @foreach($distributors as $distributor)
        {
            label: "{{ $distributor->name }}", value: "{{ $distributor->id }}"
        },
        @endforeach
    ]
    VirtualSelect.init({
      ele: '#distributors',
      options: myDistributors,
      multiple: true,
      search: true,
      placeholder: "{{__('Select Distributor')}}",
      noOptionsText: "{{__('No results found')}}",
    })

    let distributors = document.querySelector('#distributors')
    distributors.addEventListener('change', () => {
      let data = distributors.value
      @this.set('distributors', data)
    })
  })
</script>