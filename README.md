## Configuración Pendiente para Producción

### Correo SMTP (DT-002)

El formulario de contacto requiere credenciales SMTP válidas para enviar correos reales.

**Estado actual:** Configurado con `MAIL_MAILER=log` para desarrollo.

**Antes de desplegar a producción:**
1. Obtener las credenciales SMTP del hosting (host, puerto, usuario, contraseña, encriptación).
2. Actualizar el archivo `.env` en el servidor de producción:

## Configuración de Correo SMTP

### Requisitos previos

1. **Archivo `cacert.pem`:** Descargar desde https://curl.se/ca/cacert.pem y guardarlo en `C:\php\extras\ssl\cacert.pem` (o la ruta que prefieras).

2. **Archivo `php.ini`:** Configurar las siguientes líneas:
   ```ini
   curl.cainfo = "C:\php\extras\ssl\cacert.pem"
   openssl.cafile = "C:\php\extras\ssl\cacert.pem"