	<html>
		<head>
			<link rel="stylesheet" href="{{ public_path('css/printer_work_permit.css') }}">
		</head>
		<body>
			<div class="container">
				<table cellspacing="5" cellpadding="2">
					<tr style="position: absolute;">
						<td class="col-1"><span class="attribute-3">Folio:</span> <span class="value-3">{{ $data->id }}</span></td>
						<td class="col-9 text-center"><strong><span class="title">SOLICITUD DE PERMISOS</span></strong></td>
					</tr>
					<tr>
						<td class="col-3 text-left subtext-1 text-center">
							<span class="attribute-2">Fecha de solicitud:</span>
							<span class="value-2">{{ $data->created_at }}</span>
						</td>
						<td class="col-9 text-2 text-center">
							Horario de atención de lunes a viernes de 9:00 a.m. a 2:00 p.m. y 3:00 p.m. a 6:00 p.m.
						</td>
						<td class="col-3 text-1 text-center">

						</td>
					</tr>
				</table>
				<br>
				<table style="z-index:1;">
					<tr class="text-right">
						<td colspan="2" class="col-4"></td>
						<td class="col-3" align="right">
							<span class="attribute-2">Departamento:</span>
							<span class="value-2">{{$data->type->description}}</span>
						</td>
						<td class="col-3" align="right">
							<span class="attribute-2">Edificio:</span>
							<span class="value-2">{{$data->user->brand_environment->environment->description}}</span>
						</td>
					</tr>
					<tr>
						<td colspan="1"></td>
						<td colspan="3" align="right">
							<span class="atrribute-1"><strong>Inicio</strong></span>
							<span class="attibute-1">Fecha:</span>
							<span class="value-1">{{ \Carbon\Carbon::parse($data->start)->format('d-m-Y') }}</span>
							<span class="attribute-1">&nbsp;/&nbsp;</span>
							<span class="atrribute-1"><strong>Fin</strong></span>
							<span class="attibute-1">Fecha:</span>
							<span class="value-1">{{ \Carbon\Carbon::parse($data->end)->format('d-m-Y') }}</span>

						</td>
					</tr>
					<tr>
						<td colspan="1"></td>
						<td colspan="3" align="right">
							<span class="atrribute-1"><strong>Horario autorizado para labores</strong></span>
							<span class="attibute-1">:</span>
							<span class="value-1">{{ \Carbon\Carbon::parse($data->start)->format('H:i:s') }}</span>
							<span class="attribute-1">&nbsp;/&nbsp;</span>
							<span class="value-1">{{ \Carbon\Carbon::parse($data->end)->format('H:i:s') }}</span>
						</td>
					</tr>
					<tr>
						<td style="padding-top:12px;" colspan="3"><strong class="attribute-2">Responsable *</strong></td>
					</tr>
					<tr>
						<td class="col">
							<span class="attribute-1">Nombre: </span>
							<span class="value-1">{{$data->user->user_name}}</span>
						</td>
						<td class="col">
							<span class="attribute-1">Empresa: </span>
							<span class="value-1">{{ $data->user->brand->description }}</span>
						</td>
						<td class="col">
							<span class="attribute-1">Teléfono: </span>
							<span class="value-1">{{ (sizeof($data->user->phones) > 0) ? $data->user->phones[0]->phone : '' }}</span>
						</td>
						<td class="col" >
							<span class="attribute-1">Correo: </span>
							<span class="value-1">{{ $data->user->mail->mail }}</span>
						</td>
					</tr>
					<tr>
						<td colspan="4" style="padding-left:10%" class="text-center">
							<span class="attribute-1">Teléfono de emergencia: </span>
							<span class="value-1">{{ ($data->warning_phone) != null ? $data->warning_phone : '' }}</span>
						</td>
					</tr>
					<tr>
						<td style="padding-top:12px;" colspan="4">
							<br>
							<span class="attribute-2"><strong>Descripción / Requerimientos</strong></span>
						</td>
					</tr>
					<tr>
						<td colspan="4">
							{{ $data->description }}
						</td>
					</tr>
					<tr>
						<td colspan="4">
							<br>
							<br>
							<br>
						</td>
					</tr>
					<tr>

						<td colspan="2" align="center" style="text-align:center;">
							<img src="{{ public_path($authorized_by_signature) }}" style="margin-lef:1rem;width:10%">
							<b><p style="text-align:center;">{{ $data->authorizedBy->user_name }}</p></b>
							<br>
							<p> Nombre y firma de Centro Comercial</p>
						</td>
						<td></td>
						<td colspan="2" align="center" style="text-align:center;">
							<img src="{{ public_path($owner_signature) }}" style="margin-lef:1rem;width:10%">
							<b><p style="text-align:center;">{{ $data->user->user_name }}</p></b>
							<br>
							<p> Nombre y firma de Responsable</p>
						</td>
					</tr>
					<tr>
						<td></td>
					</tr>
				</table>

			</div>
			<div style="right:-23%;position: absolute;bottom:0%
			z-index:2;">
				<img src="{{ public_path("img/$logo") }}"
					style="width:35%;">
			</div>
			<div style="left:0%;position: absolute;bottom:0%
			z-index:2;">
				<img src="data:image/svg;base64, {!! base64_encode(QrCode::format('svg')->size(100)->generate("https://app-vinculacion.andares.com:11004/qr/show-info/$id")) !!} ">
			</div>
			<footer>
				<span class="subtext-1">* El responsable acepta el compromiso ante los daños y perjuicios que pueda ocasionar al inmueble y a terceros ajenos a razón de los trabajos y/o actividades pactadas en la presente solicitud. No se validará el permiso sin firma, folio y código QR.</span>
			</footer>
			<div style="page-break-after:always"></div>
			<table cellspacing="5" cellpadding="3">
				<tr style="">
					@foreach ($files as $file)
						<td class="col-2 text-center">
							<img src="{{ public_path("storage/WorkPermits/$file->work_permit_id/$file->file") }}" style="width:35%;">
						</td>
					@endforeach
					</tr>
				</table>
		</body>
	</html>
