@component('mail::message', ['data' => $data])
# Se han recibido tus comentarios en la plataforma.

Buen día, te informamos que tus comentarios han sido recibidos correctamente el día:
<b> {{ \Carbon\Carbon::now()->translatedFormat('l j F Y H:i:s')  }} </b>

Comentarios: <b> {{ $data }}</b>

Si deseas darle seguimiento contactar a:
<br>


Alejandra Torres
<hr>
alejandra.torres@andares.com


Gracias,<br>
{{ config('app.name') }}
@endcomponent
