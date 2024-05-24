@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === '2 Point Delivery')
                <img src="{{ config('website_logo') ? asset('images/logo/' . config('website_logo')) : asset('images/logo/icon.png') }}"
                    class="logo" alt="2 Point Logo">
            @else
                {{ $slot }}
            @endif
        </a>
    </td>
</tr>
