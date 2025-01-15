@component('mail::message',  ['data' => $data])
# Se ha solicitado un nuevo permiso en la plataforma.

Buen día, te informamos que se ha solicitado un nuevo permiso con <b>Folio: {{$data->id}}</b> por parte de
<b> {{ $data->user->user_name }} </b> para <b> {{ $data->brand->description }} </b>
con fecha <br>
<b>{{ \Carbon\Carbon::parse($data->start)->format('d-m-Y') }} al {{ \Carbon\Carbon::parse($data->end)->format('d-m-Y') }} </b>.

Motivo de la solicitud: <b> {{ $data->description }}</b>

Ubicación: <b> {{ $data->environment->description }}</b>

Para gestionar dicho permiso es necesario ingresar la plataforma.

@component('mail::button', ['url' => env('APP_FRONT_URL')])
Ingresar
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
