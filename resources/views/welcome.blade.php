<html>
	<head>
		<link rel="stylesheet" href="{{ public_path('css/printer_work_permit.css') }}">
	</head>
	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="10">
						<table>
							<tr>
								<!-- td -->
								<td style="color:red">
									Folio #: {{ $data->id }}
								</td>
								<td class="text-center" style="text-align: center;justify-content:center;">
									<img src="{{ public_path('img/logo-mini.svg') }}" style="margin-lef:1rem;width:25%">
								</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</table>
					</td>
				</tr>
				<!-- tr -->
				<tr class="information">
					<td colspan="10" style=" text-align:center">
									<br>
									<h1 style="">Solicitud de permisos</h1>
									<br>
									<p class="font-xl text-center">Áreas Interiores y Exteriores del Centro Comercial</p>
									<br>
									<p class="text-center">Hora de atención de Lunes a Viernes de 9:00 a.m. a 2:00 p.m. y 3:00 p.m. a 6:00 p.m</p>
					</td>
				</tr>
				<!-- tr -->
				<tr class="information">
					<td colspan="4">
						<table>
							<tr>
								<td>
									<br>
									<br>

									<h3> Departamento:</h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="heading">
					<td colspan="10">
						<table>
							<tr class="show-border">
								<td>
									Eventos
								</td>
								<td>
									@if ($data->type->description === "Eventos")
										<p style="color: green">Si</p>
									@endif
								</td>
								<td>
									Mercadotecnia
								</td>
								<td>
									@if ($data->type->description === "Mercadotecnia")
										<p style="color: green">Si</p>
									@endif
								</td>
								<td>
									Locatarios
								</td>
								<td>
									@if ($data->type->description === "Locatarios")
										<p style="color: green">Si</p>
									@endif
								</td>
								<td>
									Mantenimiento
								</td>
								<td>
									@if ($data->type->description === "Mantenimiento")
										<p style="color: green">Si</p>
									@endif
								</td>
								<td>
									Espacio para eventos
								</td>
								<td>
									@if ($data->type->description === "Espacio para eventos")
										<p style="color: green">Si</p>
									@endif
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="information">
					<td colspan="10">
						<table>
							<tr>
								<td>
									<br>
									<br>

									<h3> Inicio:</h3>
								</td>
								<td></td>
								<td></td>
								<td>

								</td>
								<td>
									<br>

									<h3> Fin:</h3>
								</td>
								<td>

								</td>
								<td>

								</td>
								<td></td>
								<td></td>
								<td></td>
							</tr>
						</table>
					</td>
				</tr>
				<!-- tr -->
				<tr class="item">
					<td colspan="6">
						Fecha: {{ $data->start->translatedFormat('l j F Y H:i:s') }}
					</td>

					<td colspan="5">
						Fecha: {{ $data->end->translatedFormat('l j F Y H:i:s') }}
					</td>
				</tr>
				<!-- tr -->
				<tr class="information">
					<td colspan="10">
						<table>
							<tr>
								<td>
									<br>
									<h3> Responsable:</h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				{{-- tr --}}
				<tr class="item">
					<td colspan="5">
						Nombre: {{ $data->user->brand->description }}
					</td>
					<td>
						Telefono: {{ (sizeof($data->user->phones) > 0) ? $data->user->phones[0]->phone : '' }}
					</td>
					<td>Correo: {{ $data->user->mail->mail }}</td>
				</tr>
				<!-- tr -->
				<tr class="information">
					<td colspan="10">
						<table>
							<tr>
								<td>
									<br>
									<h3> Descripción / Requerimientos:</h3>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				{{-- tr --}}
				<tr class="item">
					<td colspan="9">
						<p>
							{{ $data->description }}
						</p>
					</td>
					<td colspan="1">
					</td>
				</tr>
				<!-- tr -->
				<tr class="sign">
					<td colspan="10">
						<table>
							<tr>
								<td align="center" style="text-align:center;">
									<img src="{{ public_path($authorized_by_signature) }}" style="margin-lef:1rem;width:25%">
									<b><p style="text-align:center;">{{ $data->authorizedBy->user_name }}</p></b>
									<br>
									<p> Nombre y firma de Centro Comercial</p>
								</td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								<td align="center" style="text-align:center;">
									<img src="{{ public_path($owner_signature) }}" style="margin-lef:1rem;width:25%">
									<b><p style="text-align:center;">{{ $data->user->user_name }}</p></b>
									<br>
									<p> Nombre y firma de Responsable</p>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
