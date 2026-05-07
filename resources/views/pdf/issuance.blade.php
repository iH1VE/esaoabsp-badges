<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<title>Certificado</title>

<style>

body{
font-family: DejaVu Sans, sans-serif;
text-align:center;
color:#111827;
}

.container{
padding:40px;
border:1px solid #e5e7eb;
border-radius:12px;
}

.title{
font-size:32px;
font-weight:bold;
margin-bottom:10px;
}

.subtitle{
color:#6b7280;
margin-bottom:30px;
}

.recipient{
font-size:26px;
font-weight:bold;
margin:10px 0;
}

.badge{
font-size:20px;
color:#3730a3;
margin-bottom:20px;
}

.meta{
margin-top:30px;
font-size:13px;
color:#374151;
}

.badge-img{
margin-top:20px;
}

.badge-img img{
width:120px;
height:120px;
border-radius:16px;
border:1px solid #e5e7eb;
}

</style>
</head>

<body>

<div class="container">

<div class="title">Certificado de Badge</div>

<div class="subtitle">
Escola Superior de Advocacia — OABSP
</div>

<div>Certificamos que</div>

<div class="recipient">
{{ $issuance->recipient_name }}
</div>

<div>recebeu a badge</div>

<div class="badge">
{{ $issuance->badge->title }}
</div>

@if($issuance->badge->image_path)
<div class="badge-img">
<img src="{{ public_path('storage/'.$issuance->badge->image_path) }}">
</div>
@endif

<div class="meta">

<p>
Data de emissão:
<strong>
{{ optional($issuance->issued_at)->format('d/m/Y') }}
</strong>
</p>

<p>
Carga horária:
<strong>
{{ $issuance->badge->hours }} horas
</strong>
</p>

<p>
ID público:
<br>
<strong>
{{ $issuance->public_id }}
</strong>
</p>

<p>
Validação:
<br>
{{ route('public.issuances.show',$issuance->public_id) }}
</p>

</div>

</div>

</body>
</html>
