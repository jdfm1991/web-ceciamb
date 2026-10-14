<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva solicitud de cita</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <div style="background-color: #0055ff; color: #ffffff; padding: 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px;">Nueva solicitud de cita</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">
                Hospital de Clínicas de Ceciamb
            </p>
        </div>

        <div style="padding: 32px;">
            <p style="color: #333333; line-height: 1.6; margin: 0 0 16px 0;">
                Se ha recibido una nueva solicitud de cita en el portal digital. Por favor, revísala en el panel administrativo.
            </p>

            <div style="background-color: #f9fafb; border-radius: 8px; padding: 20px; margin: 24px 0;">
                <p style="color: #666666; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0;">
                    Referencia: {{ $reference }}
                </p>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #666666; width: 120px;">Paciente:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->patient_name }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #666666;">Email:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->patient_email }}</td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #666666;">Teléfono:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->patient_phone }}</td>
                    </tr>
                    @if($appointment->doctor)
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #666666;">Médico:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->doctor->name }}</td>
                    </tr>
                    @endif
                    @if($appointment->specialty)
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #666666;">Especialidad:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->specialty->name }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #666666;">Fecha solicitada:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($appointment->requested_date)->format('d/m/Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #666666;">Hora:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($appointment->requested_time)->format('H:i') }}
                        </td>
                    </tr>
                </table>

                @if($appointment->message)
                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e5e7eb;">
                    <p style="font-weight: bold; color: #666666; font-size: 12px; text-transform: uppercase; margin: 0 0 6px 0;">Mensaje del paciente:</p>
                    <p style="color: #333333; font-size: 14px; line-height: 1.6; margin: 0; white-space: pre-line;">{{ $appointment->message }}</p>
                </div>
                @endif
            </div>

            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ url('/admin/appointments') }}"
                   style="display: inline-block; background-color: #0055ff; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; font-size: 14px;">
                    Ver en el panel
                </a>
            </div>

            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px;">
                <p style="color: #92400e; font-size: 13px; margin: 0;">
                    <strong>Recordatorio:</strong> Esta solicitud debe ser gestionada antes de 24 horas. Si no se confirma ni cancela, se liberará automáticamente.
                </p>
            </div>
        </div>

        <div style="background-color: #f9fafb; padding: 16px; text-align: center; font-size: 12px; color: #6b7280;">
            Este es un correo automático del sistema. Por favor no responda directamente.
        </div>
    </div>
</body>
</html>