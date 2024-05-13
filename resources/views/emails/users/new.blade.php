@component('mail::message', ['data' => $data, 'pwd' => $pwd])
# Bienvenido al centro de vinculación Andares

En esta plataforma podrás tramitar tus permisos y solicitar
apoyo para:

<br>

<p style="text-align: justify;">
	<b>Comercialización y Publicidad: </b> Instalación o desinstalación de activaciones de marcas externas.
	<br>
	<b>Mercadotecnia: </b> Cambio de escaparates y horarios de operación, toma de fotografías, inventarios, entrega y salida de mercancía, activaciones.
	<br>
	<b>Mantenimiento: </b> Fumigaciones, mantenimiento, limpieza.
	<br>
	<b>TI:  </b> Instalación de línea telefónica, fibra óptica, circuito de cámaras. Acceso a servicio de telefonía.
	<br>
	<b>Instalaciones: </b> Activaciones y materiales.
	<br>
	<b>Estacionamiento: </b> Mantenimiento a equipos - Proveedores independientes.
	<br>
	<b>Ingenierías y Construcción: </b>Remodelaciones y/o ajustes al local, trabajos de obra.

</p>

<br>

Si tienes duda a quién dirigir tu solicitud con gusto contáctanos a tráves de
nuestro servicio de whatsapp: <a href="https://wa.me/1523336482282">33 3648 2282</a>

<br>

Para poder ingresar a la plataforma, deberás ingresar los siguientes datos:
<br>
<b>
	Usuario: {{ $data['usuario'] }}
</b>
<br>
<b>
Contraseña: {{ $pwd }}
</b>


<br>

Cuando ingreses a la plataforma deberás generar tu firma electrónica y cambiar
tu contraseña para mayor seguridad.

<br>

¡Saludos!

@component('mail::button', ['url' => env('APP_FRONT_URL')])
Ingresar
@endcomponent

Gracias,<br>
{{ config('app.name') }}
<div style="right:-23%;position: absolute;bottom:0% z-index:2;">
	<img src="https://app-vinculacion.andares.com:11004/img/logo-mini.svg" style="width:35%;">
</div>
@endcomponent
