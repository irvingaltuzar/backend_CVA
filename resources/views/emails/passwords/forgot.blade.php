@component('mail::message', ['data' => $data])
#  Detectamos que deseas cambiar tu contraseña

La nueva contraseña debe ser de una longitud mínima de 8 caracteres,
para poder realizar el cambio te invitamos a dar click en el botón de abajo.

@component('mail::button', ['url' => env('APP_FRONT_URL') . '/auth/forgot-password/' . $data])
Cambia tu contraseña
@endcomponent

<br>

Este enlace expirará en 10 minutos a partir de la hora en que fue enviado. Si tu no hiciste este cambio, ignora este correo y continua con tu contraseña actual.<br>

Saludos.
{{ config('app.name') }}
@endcomponent
