<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Activa tu cuenta</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f7; padding: 20px; margin: 0;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">

        <div style="background-color: #0055ff; color: #ffffff; padding: 24px; text-align: center;">
            <h1 style="margin: 0; font-size: 22px;">¡Bienvenido a Ceciamb!</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; opacity: 0.9;">
                Hospital de Clínicas de Ceciamb
            </p>
        </div>

        <div style="padding: 32px;">
            <p style="color: #333333; line-height: 1.6; margin: 0 0 16px 0;">
                Hola <strong>{{ $userName }}</strong>,
            </p>

            <p style="color: #333333; line-height: 1.6; margin: 0 0 16px 0;">
                Gracias por registrarte en el portal digital del Hospital de Clínicas de Ceciamb. Para activar tu cuenta, haz clic en el siguiente botón:
            </p>

            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ $activationUrl }}"
                   style="display: inline-block; background-color: #0055ff; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: bold; font-size: 16px;">
                    Activar mi cuenta
                </a>
            </div>

            <p style="color: #666666; line-height: 1.6; font-size: 14px; margin: 0 0 8px 0;">
                O copia y pega este enlace en tu navegador:
            </p>
            <p style="color: #0055ff; font-size: 13px; word-break: break-all; margin: 0 0 24px 0;">
                {{ $activationUrl }}
            </p>

            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px 16px; border-radius: 4px;">
                <p style="color: #92400e; font-size: 13px; margin: 0;">
                    <strong>Importante:</strong> Este enlace expira en {{ $expiresInHours }} horas. Si no activas tu cuenta en ese plazo, deberás registrarte nuevamente.
                </p>
            </div>
        </div>

        <div style="background-color: #f9fafb; padding: 16px; text-align: center; font-size: 12px; color: #6b7280;">
            Si no solicitaste esta cuenta, puedes ignorar este correo.
        </div>
    </div>
</body>
</html>