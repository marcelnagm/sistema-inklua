<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'inklua')
<img src="https://api.inklua.com.br/img/inklua-logo.svg" class="logo" alt="inklua Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
