@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: block;">
            <img src="https://i.imghippo.com/files/QPRUW1726096655.png" class="logo" alt="Infancia Logo">
        </a>
        <header style="font-size: large; font-weight: bold;">{{ $slot }}</header>
    </td>
</tr>