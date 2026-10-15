# Manual del Administrador

Guía para el personal administrativo del Hospital de Clínicas de Ceciamb.

## Acceso al Panel

### Iniciar sesión

1. Abre el navegador y ve a: `https://ceciamb.jumpdaedalus.com/admin`
2. Introduce tu correo electrónico y contraseña.
3. Haz clic en "Iniciar sesión".

### Recuperar contraseña

Si olvidaste tu contraseña, contacta al administrador del sistema.

## Dashboard

Al iniciar sesión, verás el **Dashboard** con:

- **Estadísticas rápidas:** Médicos activos, especialidades, citas pendientes, citas de hoy.
- **Gráfico de citas:** Distribución por estado.
- **Próximas citas:** Las 5 más cercanas.

## Gestión de Citas

### Ver todas las citas

1. En el menú lateral, haz clic en **Citas**.
2. Verás un listado con filtros por estado, médico, especialidad y rango de fechas.

### Filtrar citas

- **Por estado:** Selecciona "Pendiente", "Confirmada", etc.
- **Por médico:** Selecciona el médico.
- **Por fecha:** Usa el filtro de rango de fechas.

### Confirmar una cita

1. Busca una cita con estado **Pendiente**.
2. Haz clic en el botón **Confirmar** (icono de check verde).
3. Confirma la acción.

El paciente recibirá un correo automático.

### Cancelar una cita

1. Busca la cita.
2. Haz clic en **Cancelar** (icono X rojo).
3. Confirma la acción.

El paciente recibirá un correo automático.

### Marcar como completada

1. Busca una cita **Confirmada**.
2. Haz clic en **Completar**.

### Marcar como "No asistió"

1. Busca una cita **Confirmada**.
2. Haz clic en **No asistió** si el paciente no se presentó.

### Reagendar una cita

1. Busca una cita **Cancelada** o **No asistió**.
2. Haz clic en **Reagendar**.
3. Selecciona:
   - Nueva especialidad (opcional).
   - Nuevo médico.
   - Nueva fecha.
   - Nueva hora.
4. Confirma.

Se creará una nueva solicitud vinculada a la original. El paciente recibirá un correo.

**Nota:** El sistema verifica que la nueva franja esté disponible.

### Exportar citas

1. Aplica los filtros que necesites.
2. Haz clic en **Exportar a CSV** (arriba a la derecha).
3. Se descargará un archivo Excel/CSV con las citas filtradas.

## Gestión de Médicos

### Ver médicos

1. En el menú lateral, haz clic en **Médicos**.
2. Verás el listado con foto, nombre, especialidades y horarios.

### Crear un médico

1. Haz clic en **Crear Médico**.
2. Rellena:
   - **Nombre:** "Dr. Juan Pérez"
   - **Slug:** Se genera automáticamente.
   - **Foto:** Sube una imagen cuadrada (máx. 2 MB).
   - **Biografía:** Describe su formación y experiencia.
   - **Especialidades:** Selecciona una o más.
   - **Activo:** Sí.
3. Guarda.

### Editar un médico

1. Haz clic en el icono de editar (lápiz) junto al médico.
2. Modifica los campos necesarios.
3. Guarda.

### Desactivar un médico

En lugar de eliminar, cambia **Activo** a "No". El médico no aparecerá en el portal público, pero su historial se conserva.

## Gestión de Horarios

### Ver horarios

1. En el menú lateral, haz clic en **Horarios**.
2. Verás todos los bloques horarios por médico y día.

### Crear un horario

1. Haz clic en **Crear Horario**.
2. Rellena:
   - **Médico:** Selecciona.
   - **Día de la semana:** Lunes a Domingo.
   - **Hora de inicio:** Ej. 08:00.
   - **Hora de fin:** Ej. 12:00.
   - **Duración de cita:** 15, 20, 30, 45 o 60 minutos.
   - **Activo:** Sí.
3. Guarda.

**Importante:** El sistema **no permite** crear horarios que se solapen con otros del mismo médico y día.

### Ejemplo de horario típico

- Lunes a Viernes: 08:00-12:00 y 14:00-18:00.
- Sábados: 08:00-12:00 (si aplica).

## Gestión de Especialidades

### Ver especialidades

1. En el menú lateral, haz clic en **Especialidades**.
2. Verás las 55 especialidades con su categoría.

### Crear una especialidad

1. Haz clic en **Crear Especialidad**.
2. Rellena:
   - **Nombre:** "Cardiología"
   - **Slug:** Se genera automáticamente.
   - **Categoría:** Médica, Subespecialidad, Profesión de la Salud, Técnica.
   - **Descripción:** Opcional.
   - **Activa:** Sí.
3. Guarda.

## Gestión de Servicios

### Ver servicios

1. En el menú lateral, haz clic en **Servicios**.

### Crear un servicio

1. Haz clic en **Crear Servicio**.
2. Rellena:
   - **Nombre:** "Laboratorio Clínico"
   - **Imagen:** Sube una imagen horizontal.
   - **Descripción:** Texto que se mostrará en el portal.
   - **Activo:** Sí.
3. Guarda.

## Gestión de Usuarios

### Ver usuarios

1. En el menú lateral, haz clic en **Usuarios**.
2. Verás todos los usuarios (admin, recepcionista, paciente).

### Crear un usuario interno

1. Haz clic en **Crear Usuario**.
2. Rellena:
   - **Nombre:** Nombre completo.
   - **Email:** Correo único.
   - **Rol:** Admin o Recepcionista.
   - **Contraseña:** Mínimo 8 caracteres.
3. Guarda.

**Nota:** Los pacientes se registran desde el portal público, no desde el panel.

### Restablecer contraseña

1. Edita el usuario.
2. Introduce una nueva contraseña.
3. Guarda.

## Configuración Institucional

### Editar información del hospital

1. En el menú lateral, haz clic en **Información institucional**.
2. Edita los campos:
   - Nombre legal, RIF, slogan.
   - Dirección, teléfono, email.
   - Redes sociales.
   - Logo.
3. Guarda.

Los cambios se reflejan inmediatamente en el portal público.

## Cerrar sesión

Haz clic en tu nombre (arriba a la derecha) y selecciona **Cerrar sesión**.

## Preguntas Frecuentes

### ¿Por qué no puedo eliminar una cita?

Solo las citas **pendientes** se pueden eliminar. Las confirmadas, canceladas, completadas y no asistidas se conservan como historial.

### ¿Por qué no puedo crear un horario solapado?

Porque el sistema verifica que no haya solapamiento con otros horarios del mismo médico y día. Esto evita conflictos de agenda.

### ¿Cómo elimino una cuenta de paciente?

Las cuentas de pacientes no se eliminan desde el panel. Si un paciente quiere eliminar su cuenta, debe contactar al administrador.

### ¿Qué pasa si un paciente no asiste?

1. Marca la cita como **No asistió**.
2. Si el paciente llama para reagendar, usa la acción **Reagendar**.

### ¿Cómo contacto con soporte técnico?

Contacta a Jump Daedalus: https://jumpdaedalus.com/