<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cita confirmada</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <div style="background-color: #10b981; color: #ffffff; padding: 24px; text-align: center;">
            <div style="font-size: 48px; margin-bottom: 8px;">✓</div>
            <h1 style="margin: 0; font-size: 22px;">Tu cita ha sido confirmada</h1>
        </div>

        <div style="padding: 32px;">
            <p style="color: #333333; line-height: 1.6; margin: 0 0 16px 0;">
                Hola <strong>{{ $appointment->patient_name }}</strong>,
            </p>

            <p style="color: #333333; line-height: 1.6; margin: 0 0 24px 0;">
                Nos complace informarte que tu cita ha sido confirmada. Aquí están los detalles:
            </p>

            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px; margin: 24px 0;">
                <p style="color: #166534; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0;">
                    Referencia: {{ $reference }}
                </p>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #166534; width: 120px;">Fecha:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($appointment->requested_date)->translatedFormat('l, d \d\e F \d\e Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #166534;">Hora:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($appointment->requested_time)->format('H:i') }}
                        </td>
                    </tr>
                    @if($appointment->doctor)
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #166534;">Médico:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->doctor->name }}</td>
                    </tr>
                    @endif
                    @if($appointment->specialty)
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #166534;">Especialidad:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $appointment->specialty->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            <p style="color: #333333; line-height: 1.6; margin: 0 0 16px 0;">
                Por favor, llega 15 minutos antes de tu cita. Si necesitas cancelar o reagendar, contáctanos con anticipación.
            </p>

            <div style="background-color: #eff6ff; border-left: 4px solid #3b82f6; padding: 12px 16px; border-radius: 4px;">
                <p style="color: #1e40af; font-size: 13px; margin: 0;">
                    <strong>¿Necesitas ayuda?</strong> Llámanos al {{ \App\Models\ClinicInfo::getValue('phone') }} o escríbenos a {{ \App\Models\ClinicInfo::getValue('email') }}.
                </p>
            </div>
        </div>

        <div style="background-color: #f9fafb; padding: 16px; text-align: center; font-size: 12px; color: #6b7280;">
            Hospital de Clínicas de Ceciamb - {{ \App\Models\ClinicInfo::getValue('slogan') }}
        </div>
    </div>
</body>
</html>