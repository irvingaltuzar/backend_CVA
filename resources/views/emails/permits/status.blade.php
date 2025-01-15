@component('mail::message', ['data' => $data, 'permit' => $permit, 'start' => $start, 'end' => $end])

@if ($data === 1)
# Permiso autorizado

Buen día, te informamos que la solicitud de tu permiso con <b>Folio: {{$permit['id']}}</b> ha sido aprobado, con el siguiente horario: <br>

<b>{{ $start  }} al {{ $end  }} </b>.

<br>

Es importante ingresar a la plataforma para poder descargar el formato de permiso.

@elseif ($data === 2)

# Permiso rechazado

Buen día, te informamos que la solicitud de tu permiso con <b>Folio: {{$permit['id']}}</b> ha sido rechazada <br>

Motivo: {{ $permit['comments'] }}

@else 

# Permiso Reasignado

Buen día, te informamos que la solicitud de tu permiso con <b>folio: {{$permit['id']}} </b> ha sido reasignado al area correspondiente de autorización <br>

<b>Area: {{ $permit['type']['description'] }}</b>

@endif

@component('mail::button', ['url' => env('APP_FRONT_URL')])
Ingresar
@endcomponent

Saludos,<br>
{{ config('app.name') }}
@endcomponent
