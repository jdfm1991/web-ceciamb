### Deuda Técnica #001 — Validación de solapamiento de horarios no se dispara en creación

| Campo                                   | Detalle                                              |
|-----------------------------------------|------------------------------------------------------|
| **ID**                                  | DT-001                                               |
| **Sub-fase origen**                     | 3.4 — Resource de Horarios                           |
| **Descubierta en**                      | Fase 3, Sub-fase 3.4                                 |
| **Prioridad**                           | Media (afecta la integridad de datos, pero el personal puede evitarlo manualmente) |
| **Impacto**                             | Un recepcionista podría crear accidentalmente dos bloques horarios solapados para el mismo médico y día. Esto generaría franjas duplicadas en el portal público (Fase 5).                          |
| **Workaround actual**                   | El personal puede revisar manualmente el listado de horarios del médico antes de crear uno nuevo. |
| **Causa raíz**                          | La closure en ->rules([...]) usa $get() para construir manualmente el array de datos. En creación, $get('end_time') es null cuando se ejecuta la validación de start_time, porque el usuario aún no ha ingresado la hora de fin. |
| **Solución propuesta**                  | Refactorizar DoctorScheduleForm y NoScheduleOverlap para que la validación use DataAwareRule puro, sin setData() manual. Detalle completo en la conversación del 13-10-2026.                        |
| **Bloquea MVP**                         | No. El módulo funciona.                              |
| **Retomar en**                          | Fase 5 (Solicitudes de Citas) — antes de generar franjas disponibles, o en Fase 6 (Testing).|
| **Riesgo si no se corrige**             | Bajo para el MVP. La clínica tiene 2-5 médicos, y el personal puede revisar visualmente antes de guardar.|
| **Riesgo si se ignora indefinidamente** | Medio. En producción con más médicos y personal rotativo, los errores humanos aumentarían.|


### Deuda Técnica #002 — Envío de correo SMTP falla por credenciales no configuradas

| Campo                                   | Detalle                                              |
|-----------------------------------------|------------------------------------------------------|
| **ID**                                  | DT-002                                               |
| **Sub-fase origen**                     | 4.7 — Formulario de Contacto                          |
| **Descubierta en**                      | Fase 4, Sub-fase 4.7                               |
| **Prioridad**                           | Media (bloquea funcionalidad real, pero el desarrollo puede continuar) |
| **Impacto**                             | El formulario de contacto muestra el mensaje de error "No pudimos enviar tu mensaje" en lugar del mensaje de éxito. Los correos no se envían. |
| **Workaround actual**                   | Usar MAIL_MAILER=log en .env para que los correos se escriban en storage/logs/laravel.log en lugar de enviarse. |
| **Causa raíz**                          | Las credenciales SMTP del hosting no están configuradas correctamente en .env, o el hosting no ha habilitado el envío SMTP para el dominio. |
| **Solución propuesta**                  | 1. Obtener las credenciales SMTP correctas del panel del hosting (host, puerto, usuario, contraseña, encriptación). 2. Actualizar .env con los valores correctos. 3. Ejecutar php artisan config:clear. 4. Probar el envío real. 5. Verificar que los correos no lleguen a spam (configurar SPF/DKIM/DMARC en el DNS del dominio). |
| **Bloquea MVP**                         | No. El resto del portal funciona. Cuando el SMTP esté configurado, el formulario funciona sin cambios de código. |
| **Retomar en**                          | Fase 7 (Despliegue) — antes de publicar el MVP, o cuando el cliente provea las credenciales correctas.|
| **Riesgo si no se corrige**             | Alto para el MVP comercial. El formulario de contacto es un canal crítico. Sin embargo, el WhatsApp y el teléfono siguen funcionando como alternativas.|


### Deuda Técnica #003 —  Mostrar el nombre del médico seleccionado en el paso 4 del wizard

| Campo                                   | Detalle                                              |
|-----------------------------------------|------------------------------------------------------|
| **ID**                                  | MEJ-001                                              |
| **Sub-fase origen**                     | 4.8 — Wizard de Citas                                |
| **Descubierta en**                      | Fase 4, Sub-fase 4.8                                 |
| **Prioridad**                           | Baja (cosmético, no afecta funcionalidad)            |
| **Impacto**                             | En el paso 4 (confirmación), el campo "Médico" muestra "Cualquier médico disponible" incluso cuando el usuario seleccionó un médico específico. La información correcta aparece en la página de éxito y en el panel admin. |
| **Causa raíz**                          | En AppointmentWizard.jsx, el método getDoctorName() solo resuelve el nombre si el médico es el preseleccionado. Para médicos seleccionados en el paso 1, devuelve null.|
| **Solución propuesta**                  | Pasar los médicos cargados del paso 1 al estado del wizard, o exponer una función auxiliar que busque el médico por ID desde una lista global.|
| **Bloquea MVP**                         | No.                                                  |
| **Retomar en**                          | Sub-fase 4.11 (Optimización) o Fase 6 (Testing).     |
