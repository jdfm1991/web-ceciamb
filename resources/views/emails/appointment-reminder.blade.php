<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recordatorio de cita</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <div style="background-color: #0055ff; color: #ffffff; padding: 24px; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 8px;">📅</div>
            <h1 style="margin: 0; font-size: 22px;">Recordatorio de tu cita</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">
                Hospital de Clínicas de Ceciamb
            </p>
        </div>

        <div style="padding: 32px;">
            <p style="color: #333333; line-height: 1.6; margin: 0 0 16px 0;">
                Hola <strong>{{ $appointment->patient_name }}</strong>,
            </p>

            <p style="color: #333333; line-height: 1.6; margin: 0 0 24px 0;">
                Te recordamos que tienes una cita programada para mañana. Aquí están los detalles:
            </p>

            <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px; margin: 24px 0;">
                <p style="color: #1e40af; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0;">
                    Referencia: {{ $reference }}
                </p>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #1e40af; width: 120px;">Fecha:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($appointment->requested_date)->translatedFormat('l, d \d\e F \d\e Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #1e40af;">Hora:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($appointment->requested_time)->format('H:i') }}
                        </td>
                    </tr>
                    @if($appointment->doctor)
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #1e40af;">Médico:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->doctor->name }}</td>
                    </tr>
                    @endif
                    @if($appointment->specialty)
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #1e40af;">Especialidad:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->specialty->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px; margin: 24px 0;">
                <p style="color: #92400e; font-size: 13px; margin: 0;">
                    <strong>Importante:</strong> Por favor, llega 15 minutos antes de tu cita. Si no puedes asistir, contáctanos con anticipación para liberar tu espacio.
                </p>
            </div>

            <div style="background-color: #f9fafb; border-radius: 8px; padding: 16px; margin: 24px 0;">
                <p style="color: #666666; font-size: 13px; margin: 0 0 8px 0; font-weight: bold;">¿Necesitas reagendar o cancelar?</p>
                <p style="color: #666666; font-size: 13px; margin: 0;">
                    Llámanos al <a href="tel:{{ \App\Models\ClinicInfo::getValue('phone') }}" style="color: #0055ff; text-decoration: none; font-weight: bold;">{{ \App\Models\ClinicInfo::getValue('phone') }}</a>
                    o escríbenos a <a href="mailto:{{ \App\Models\ClinicInfo::getValue('email') }}" style="color: #0055ff; text-decoration: none; font-weight: bold;">{{ \App\Models\ClinicInfo::getValue('email') }}</a>.
                </p>
            </div>

            <div style="text-align: center; margin: 24px 0;">
                <a href="{{ url('/mi-cuenta/citas/' . $appointment->id) }}"
                   style="display: inline-block; background-color: #0055ff; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-weight: bold; font-size: 14px;">
                    Ver mi cita
                </a>
            </div>
        </div>

        <div style="background-color: #f9fafb; padding: 16px; text-align: center; font-size: 12px; color: #6b7280;">
            Hospital de Clínicas de Ceciamb - {{ \App\Models\ClinicInfo::getValue('slogan') }}
        </div>
    </div>
</body>
</html>