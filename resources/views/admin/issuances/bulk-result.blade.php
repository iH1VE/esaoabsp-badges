<x-app-layout>

<h2>Resultado da Importação</h2>

<table border="1" cellpadding="8">

<tr>
<th>Nome</th>
<th>Status</th>
</tr>

@foreach($results as $r)

<tr>

<td>{{ $r['name'] }}</td>

<td>
@if($r['status']=='ok')
✔ enviado
@else
❌ erro
@endif
</td>

</tr>

@endforeach

</table>

</x-app-layout>
