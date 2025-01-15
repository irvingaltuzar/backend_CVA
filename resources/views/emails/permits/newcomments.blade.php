@component('mail::message',  ['data' => $data])
<b>Tienes un nuevo Comentario en Permiso.<b>

Buen día, te informamos que se ha agregado un nuevo comentario en permiso con <b>Folio: {{$data->work_permit_id}}</b> por parte de
<b> {{ $data->user->user_name }} </b>

Comentario: <b> {{ $data->message }}</b><br>

Para gestionar dicho permiso es necesario ingresar la plataforma.

@component('mail::button', ['url' => env('APP_FRONT_URL')])
Ingresar
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent


