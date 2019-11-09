<table class="text-center">
    <tr>
        <td class="text-center">
            <table>
                <tr>
                    <td class="text-center">
                        <table>
                            <tr>
                                <td>
                                    <a href="{{ $url }}" class="button button-{{ $color ?? 'blue' }}"
                                        target="_blank">{{ $slot }}</a>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
