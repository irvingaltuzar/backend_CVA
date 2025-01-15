<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="{{ public_path('css/printer_work_permit.css') }}">

</head>
<body>
    <div class="bordered-container">
        <!-- Header -->
        <div class="header">
            <table>
            <tr>
                <!-- Columna izquierda: Folio -->
                <td class="left-column">
                    <div class="folio">
                        <div class="folio-label">Folio: </div>
                        <div class="folio-number">{{ $data->id }}</div>
                    </div>
                </td>

                <!-- Columna central: unit-info -->
                <td class="unit-info">
                    <div class="unit-name">{{ $data->environment->description ?? '' }}</div>
                    <div class="format-text">FORMATO DE PERMISO</div>
                </td>

                <!-- Columna derecha: vacía -->
                <td class="right-column">
                @if(($data->high_risk))
                <img src="{{ public_path('img/caution-icon.svg') }}" alt="Warning Icon" width="75" height="75" style=";">
                <b>Alto Riesgo<b>
                @endif
                </td>
            </tr>
        </table>
    </div>

        <!-- Information -->
        <table class="info-container">
            <tr>
                <td class="left-column">
                    <p class="custom-style custom-style-large">
                        <span>Fecha de solicitud: <span class="value_color">{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y H:i:s') }}</span></span><br>
                        <span>Fecha Inicio: <span class="value_color">{{ \Carbon\Carbon::parse($data->start)->format('d-m-Y') }}</span></span>
                        <span>Fecha Fin: <span class="value_color">{{ \Carbon\Carbon::parse($data->end)->format('d-m-Y') }}</span></span><br>
                        <span>Horario autorizado: <span class="value_color">{{ \Carbon\Carbon::parse($data->start)->format('H:i:s') }} hrs a {{ \Carbon\Carbon::parse($data->end)->format('H:i:s') }} hrs</span></span>
                    </p>
                </td>
                <td class="right-column">
                    <p class="custom-style custom-style-large">
                        <span>Departamento: <span class="value_color">{{$data->type->description}}</span></span><br>
                        <span>Edificio: <span class="value_color">{{ $data->environment->description ?? '' }}</span></span><br>
                        @if(($data->high_risk))
                        <span>*Trabajo alto Riesgo: <span class="value_color">{{ $data->type_highrisk->description ?? '' }}</span></span>
                        @endif
                    </p>
                </td>
            </tr>
        </table>

        <!-- Responsible Data -->
        <p class="custom-style custom-style-solicitante">
            <b>*Datos del responsable:</b>
        </p><br>

        <table class="info-container">
            <tr>
                <td>
                    <p class="left-column custom-style">
                        <span>Nombre completo: <span class="value_color">{{$data->user->user_name}}</span></span><br>
                        <span>Empresa: <span class="value_color">{{ $data->user->brand->description }}</span></span>
                    </p>
                </td>
                <td>
                    <p class="unit-info custom-style">
                        <span>Teléfono: <span class="value_color">{{ (sizeof($data->user->phones) > 0) ? $data->user->phones[0]->phone : '' }}</span></span><br>
                        <span>Teléfono de emergencia: <span class="value_color">{{ ($data->warning_phone) != null ? $data->warning_phone : '' }}</span></span>
                    </p>
                </td>
                <td>
                    <p class="right-column custom-style ">
                        <span>Correo electrónico: </span><br>
                        <span class="value_color">{{ $data->user->mail->mail }}</span>
                    </p>
                </td>
            </tr>
        </table>

        <!-- Description -->
        <p class="custom-style custom-style-solicitante">
            <b>*Descripción del permiso y/o requerimientos:</b>
        </p>
          <!-- Information -->
          <table class="info-container">
            <tr>
                <td class="left-column">
                <p class="left-column custom-style">
                    <span>	{{ $data->description }}</span><br><br>
                    <b>*Areas de trabajo: </b><span>	{{ ($data->work_area) != null ? $data->work_area : '' }}</span><br>
                    <b>*Personal Involucrado: </b><span>	{{ ($data->involved_staff) != null ? $data->involved_staff : '' }}</span>
                            </p>
                        </td>
            </tr>
        </table>

        <div class="responsable-legend">
            <p>*El responsable acepta el compromiso ante los daños y perjuicios que pueda ocasionar al inmueble y a terceros ajenos a razón de los trabajos y/o actividades pactadas en la presente solicitud. No se validará sin firma, folio y código QR.</p>
        </div><br>

        <!-- Footer -->
        <div class="footer">
            <table>
                <tr>
                    <td class="footer-column logo-column">
                        <img src="{{ public_path("img/$logo") }}" alt="Logo" class="footer-logo">
                    </td>
                    <td class="footer-column signature-column">
                        <img src="{{ public_path($authorized_by_signature) }}" alt="Firma 1" class="footer-signature">
                        <div class="signature-info">
                            {{ $data->authorizedBy->user_name }}<br> <b>  {{$data->authorizedBy->position}}<b>
                        </div>
                    </td>
                    @if(!is_null($data->authorizedBySecurity))
                    <td class="footer-column signature-column">
                        <img src="{{ public_path($authorized_by_security_signature) }}" alt="Firma 2" class="footer-signature">
                        <div class="signature-info">
                            {{ $data->authorizedBySecurity->user_name }}<br> <b>  {{$data->authorizedBySecurity->position}}<b>
                        </div>
                    </td>
                    @endif
                    <td class="footer-column signature-column">
                        <img src="{{ public_path($owner_signature) }}" alt="Firma 3" class="footer-signature">
                        <div class="signature-info">
                            {{ $data->user->user_name }}<br> <b> {{ $data->user->position}}<b>
                        </div>
                    </td>
                    <td class="footer-column qr-column">
                        <img src="data:image/svg;base64, {!! base64_encode(QrCode::format('svg')->size(100)->generate("https://app-vinculacion.andares.com:11004/qr/show-info/$id")) !!} " alt="Código QR" class="footer-qr">
                    </td>
                </tr>
            </table>
        </div>
        <!-- Information -->
        <table class="info-container">
            <tr>
                <td class="left-column">
                <p class="left-column custom-style">
                    @if(!is_null($data->supervisor_notes))
                    <b>*Notas y/o comentarios de Supervisor: </b><span>	{{ ($data->supervisor_notes) != null ? $data->supervisor_notes : '' }}</span><br>
                    @endif

                    @if(!is_null($data->security_notes))
                    <b>*Notas y/o comentarios de Seguridad: </b><br>
                    <span>	{{ ($data->security_notes) != null ? $data->security_notes : '' }}</span>
                    @endif
                    </p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
