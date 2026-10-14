<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo mensaje de contacto</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        {{-- Header --}}
        <div style="background-color: #0055ff; color: #ffffff; padding: 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px;">Nuevo mensaje de contacto</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">
                Hospital de Clínicas de Ceciamb
            </p>
        </div>

        {{-- Contenido --}}
        <div style="padding: 32px;">
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #666666; width: 120px;">Nombre:</td>
                    <td style="padding: 8px 0; color: #333333;">{{ $name }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #666666;">Email:</td>
                    <td style="padding: 8px 0; color: #333333;">
                        <a href="mailto:{{ $email }}" style="color: #0055ff; text-decoration: none;">{{ $email }}</a>
                    </td>
                </tr>
                @if($phone)
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #666666;">Teléfono:</td>
                    <td style="padding: 8px 0; color: #333333;">
                        <a href="tel:{{ $phone }}" style="color: #0055ff; text-decoration: none;">{{ $phone }}</a>
                    </td>
                </tr>
                @endif
                <tr>
                    <td style="padding: 8px 0; font-weight: bold; color: #666666;">Asunto:</td>
                    <td style="padding: 8px 0; color: #333333;">{{ $contactSubject  }}</td>
                </tr>
            </table>

            <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid #e5e7eb;">
                <p style="font-weight: bold; color: #666666; margin: 0 0 8px 0;">Mensaje:</p>
                <p style="color: #333333; line-height: 1.6; white-space: pre-line; margin: 0;">
                    {{ $messageBody }}
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div style="background-color: #f9fafb; padding: 16px; text-align: center; font-size: 12px; color: #6b7280;">
            Este mensaje fue enviado desde el formulario de contacto del portal público.
        </div>
    </div>
</body>
</html>