<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cita cancelada</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <div style="background-color: #ef4444; color: #ffffff; padding: 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px;">Tu cita ha sido cancelada</h1>
        </div>

        <div style="padding: 32px;">
            <p style="color: #333333; line-height: 1.6; margin: 0 0 16px 0;">
                Hola <strong>{{ $appointment->patient_name }}</strong>,
            </p>

            @if($cancelledBy === 'patient')
                <p style="color: #333333; line-height: 1.6; margin: 0 0 24px 0;">
                    Confirmamos la cancelación de tu cita según tu solicitud. Aquí están los detalles:
                </p>
            @else
                <p style="color: #333333; line-height: 1.6; margin: 0 0 24px 0;">
                    Lamentamos informarte que tu cita ha sido cancelada. Si deseas reagendar, puedes enviar una nueva solicitud desde nuestro portal.
                </p>
            @endif

            <div style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 20px; margin: 24px 0;">
                <p style="color: #991b1b; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0;">
                    Referencia: {{ $reference }}
                </p>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #991b1b; width: 120px;">Fecha:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($appointment->requested_date)->translatedFormat('l, d \d\e F \d\e Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #991b1b;">Hora:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($appointment->requested_time)->format('H:i') }}
                        </td>
                    </tr>
                    @if($appointment->doctor)
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #991b1b;">Médico:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->doctor->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ url('/citas') }}"
                   style="display: inline-block; background-color: #0055ff; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; font-size: 14px;">
                    Solicitar nueva cita
                </a>
            </div>

            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 12px 16px; border-radius: 4px;">
                <p style="color: #1e40af; font-size: 13px; margin: 0;">
                    Si tienes preguntas, contáctanos al {{ \App\Models\ClinicInfo::getValue('phone') }}.
                </p>
            </div>
        </div>

        <div style="background-color: #f9fafb; padding: 16px; text-align: center; font-size: 12px; color: #6b7280;">
            Hospital de Clínicas de Ceciamb
        </div>
    </div>
</body>
</html>