<div class="panel panel-chat shoutbox torrent-mediainfo" x-data="{ show: false }">
    <div class="panel-heading">
        <h4 style="cursor: pointer;" @click="show = !show">
            <i class="{{ config("other.font-awesome") }} fa-info-square"></i> MediaInfo
            <i class="{{ config("other.font-awesome") }} fa-plus-circle fa-pull-right" x-show="!show"></i>
            <i class="{{ config("other.font-awesome") }} fa-minus-circle fa-pull-right" x-show="show"></i>
        </h4>
    </div>
    <div class="table-responsive tabla-mediainfo">
        <table class="table table-condensed table-bordered table-striped">
            <tbody>
            <tr>
                <td>
                    <div class="panel-body">
                        <div class="torrent-mediainfo-dump" style="opacity: 1; display: none;" x-show="show">
                            <div>
                                <span class="text-center text-bold">Full MediaInfo Dump</span>
                                <pre class="decoda-code"><code>{{ $torrent->mediainfo }}</code></pre>
                            </div>
                        </div>
                        <div class="slidingDiv2">
                            <div class="text-left text-main mediainfo-filename"
                                 style="border-bottom: 1px solid #444444; padding-bottom: 5px; margin-bottom: 5px;">
                                @if ($mediaInfo !== null && isset($mediaInfo['general']['file_name']))
                                    <span class="text-bold text-main">{{ $mediaInfo['general']['file_name'] ?? __('common.unknown') }}</span>
                                @endif
                            </div>
                            <div class="mediainfo-main" style="width: 100%; display:table;">
                                <div class="mediainfo-general"
                                     style="width: 20%; display:table-cell; text-align: left;">
                                    <div class="text-bold">@joypixels(':information_source:') General:</div>
                                    <div>
                                        <u style="font-weight: bold;">Format:</u> {{ $mediaInfo['general']['format'] ?? __('common.unknown') }}
                                    </div>
                                    <div>
                                        <u style="font-weight: bold;">Duration:</u> {{ $mediaInfo['general']['duration'] ?? __('common.unknown') }}
                                    </div>
                                    <div><u style="font-weight: bold;">Global Bit
                                            Rate:</u> {{ $mediaInfo['general']['bit_rate'] ?? __('common.unknown') }}
                                    </div>
                                    <div><u style="font-weight: bold;">Overall
                                            Size:</u> {{ App\Helpers\StringHelper::formatBytes($mediaInfo['general']['file_size'] ?? 0, 2) }}
                                    </div>
                                </div>
                                <div class="mediainfo-video" style="width: 30%; display:table-cell; text-align: left;">
                                    <div class="text-bold">@joypixels(':projector:') Video Tracks:</div>
                                    @if ($mediaInfo !== null && isset($mediaInfo['video']))
                                        @foreach ($mediaInfo['video'] as $key => $videoElement)
                                            <div>Track {{ ++$key }}:</div>
                                            <div>
                                                <u style="font-weight: bold;">Format:</u> {{ $videoElement['format'] ?? __('common.unknown') }}
                                                ({{ $videoElement['bit_depth'] ?? __('common.unknown') }})
                                            </div>
                                            <div>
                                                <u style="font-weight: bold;">Resolution:</u> {{ $videoElement['width'] ?? __('common.unknown') }}
                                                x {{ $videoElement['height'] ?? __('common.unknown') }}</div>
                                            <div><u style="font-weight: bold;">Aspect
                                                    Ratio:</u> {{ $videoElement['aspect_ratio'] ?? __('common.unknown') }}
                                            </div>
                                            <div><u style="font-weight: bold;">Frame
                                                    Rate:</u> @if((isset($videoElement['framerate_mode'])) && $videoElement['framerate_mode'] === 'Variable')
                                                    VFR @else{{ $videoElement['frame_rate'] ?? __('common.unknown') }}@endif
                                            </div>
                                            <div><u style="font-weight: bold;">Bit
                                                    Rate:</u> {{ $videoElement['bit_rate'] ?? __('common.unknown') }}
                                            </div>
                                            @if(isset($videoElement['format']) && $videoElement['format'] === 'HEVC')
                                                <div><u style="font-weight: bold;">HDR
                                                        Format:</u> {{ $videoElement['hdr_format'] ?? __('common.unknown') }}
                                                </div>
                                                <div><u style="font-weight: bold;">Color
                                                        Primaries:</u> {{ $videoElement['color_primaries'] ?? __('common.unknown') }}
                                                </div>
                                                <div><u style="font-weight: bold;">Transfer
                                                        Characteristics:</u> {{ $videoElement['transfer_characteristics'] ?? __('common.unknown') }}
                                                </div>
                                            @endif
                                            @if (! $loop->last)
                                                <div style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px; width: 75%;"></div> @endif
                                        @endforeach
                                    @endif
                                </div>
                                <div class="mediainfo-audio" style="width: 50%; display:table-cell; text-align: left;">
                                    <div class="text-bold">@joypixels(':loud_sound:') Audio Tracks:</div>
                                    @if ($mediaInfo !== null && isset($mediaInfo['audio']))
                                        @foreach ($mediaInfo['audio'] as $key => $audioElement)
                                            <div>Track {{ ++$key }}:</div>
                                            <div>{{ $audioElement['language'] ?? __('common.unknown') }}
                                                | {{ $audioElement['format'] ?? __('common.unknown') }}
                                                | {{ $audioElement['channels'] ?? __('common.unknown') }}
                                                | {{ $audioElement['bit_rate'] ?? __('common.unknown') }}
                                                | {{ $audioElement['title'] ?? __('common.unknown') }}</div>
                                            @if (! $loop->last)
                                                <div style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px; width: 75%;"></div> @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            <div class="text-left text-main mediainfo-subtitles"
                                 style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px;">
                                <span class="text-bold">@joypixels(':speech_balloon:') Subtitles:</span>
                                @if ($mediaInfo !== null && isset($mediaInfo['text']))
                                    @foreach ($mediaInfo['text'] as $key => $textElement)
                                        <span><img src="{{ language_flag($textElement['language'] ?? __('common.unknown')) }}"
                                                   alt="{{ $textElement['language'] ?? __('common.unknown') }}"
                                                   width="20" height="13" data-toggle="tooltip"
                                                   data-original-title="{{ $textElement['language'] ?? __('common.unknown') }} | {{ $textElement['format'] ?? __('common.unknown') }} | {{ $textElement['title'] ?? __('common.unknown') }}">&nbsp;</span>
                                    @endforeach
                                @endif
                            </div>

                            @if ($mediaInfo !== null && isset($mediaInfo['video']))
                                @foreach ($mediaInfo['video'] as $key => $videoElement)
                                    @if ($mediaInfo !== null && isset($videoElement['encoding_settings']))
                                        <div class="text-left text-main mediainfo-encode-settings"
                                             style="border-top: 1px solid #444444; padding-top: 5px; margin-top: 5px;">
                                            <span class="text-bold">@joypixels(':information_source:') Encode Settings:</span>
                                            <br>
                                            <pre class="decoda-code"><code>{{ $videoElement['encoding_settings'] ?? __('common.unknown') }}</code></pre>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>