@props(['colspan'])

<tr>
    <td colspan="{{ $colspan }}" class="px-4 py-14 text-center text-sm text-slate-400">
        {{ $slot }}
    </td>
</tr>
