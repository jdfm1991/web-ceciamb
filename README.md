## Configuración Pendiente para Producción

### Correo SMTP (DT-002)

El formulario de contacto requiere credenciales SMTP válidas para enviar correos reales.

**Estado actual:** Configurado con `MAIL_MAILER=log` para desarrollo.

**Antes de desplegar a producción:**
1. Obtener las credenciales SMTP del hosting (host, puerto, usuario, contraseña, encriptación).
2. Actualizar el archivo `.env` en el servidor de producción: