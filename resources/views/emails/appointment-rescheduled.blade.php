<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cita reagendada</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <div style="background-color: #0055ff; color: #ffffff; padding: 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px;">Tu cita ha sido reagendada</h1>
        </div>

        <div style="padding: 32px;">
            <p style="color: #333333; line-height: 1.6; margin: 0 0 16px 0;">
                Hola <strong>{{ $newAppointment->patient_name }}</strong>,
            </p>

            <p style="color: #333333; line-height: 1.6; margin: 0 0 24px 0;">
                Tu cita ha sido reagendada. Aquí están los nuevos detalles:
            </p>

            {{-- Nueva cita --}}
            <div style="background-color: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px; margin: 24px 0;">
                <p style="color: #1e40af; font-size: 12px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 0 0 12px 0;">
                    Nueva cita: {{ $newReference }}
                </p>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #1e40af; width: 120px;">Fecha:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($newAppointment->requested_date)->translatedFormat('l, d \d\e F \d\e Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #1e40af;">Hora:</td>
                        <td style="padding: 6px 0; color: #333333;">
                            {{ \Carbon\Carbon::parse($newAppointment->requested_time)->format('H:i') }}
                        </td>
                    </tr>
                    @if($newAppointment->doctor)
                    <tr>
                        <td style="padding: 6px 0; font-weight: bold; color: #1e40af;">Médico:</td>
                        <td style="padding: 6px 0; color: #333333;">{{ $newAppointment->doctor->name }}</td>
                    </tr>
                    @endif
                </table>
            </div>

            {{-- Cita anterior --}}
            <div style="background-color: #f9fafb; border-radius: 8px; padding: 16px; margin: 16px 0;">
                <p style="color: #666666; font-size: 12px; font-weight: bold; text-transform: uppercase; margin: 0 0 8px 0;">
                    Cita anterior: {{ $previousReference }}
                </p>
                <p style="color: #666666; font-size: 13px; margin: 0;">
                    {{ \Carbon\Carbon::parse($previousAppointment->requested_date)->format('d/m/Y') }}
                    a las {{ \Carbon\Carbon::parse($previousAppointment->requested_time)->format('H:i') }}
                </p>
            </div>

            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px; margin-top: 24px;">
                <p style="color: #92400e; font-size: 13px; margin: 0;">
                    <strong>Nota:</strong> Tu nueva cita está pendiente de confirmación. Recibirás otro correo cuando sea confirmada.
                </p>
            </div>
        </div>

        <div style="background-color: #f9fafb; padding: 16px; text-align: center; font-size: 12px; color: #6b7280;">
            Hospital de Clínicas de Ceciamb
        </div>
    </div>
</body>
</html>