# Notas de Rendimiento

## Optimizaciones aplicadas

### 1. Eager Loading
- `DoctorController@index`: `->with('specialties')`
- `DoctorController@show`: `->load('specialties', 'schedules')`
- `AppointmentController@index`: `->with('doctor', 'specialty')`

### 2. Cacheo
- `clinic_info`: 1 hora (invalidado al guardar)
- `clinic_stats_widget`: 5 minutos

### 3. Lazy loading
- Imágenes en `ServiceCard`, `DoctorCard`: `loading="lazy"`

### 4. Índices de BD
- `appointments`: `status`, `requested_date`, `(status, expires_at)`
- `specialties`: `category`, `is_active`
- `doctor_schedules`: `(doctor_id, day_of_week, is_active)`

### 5. Compresión (pendiente en producción)
- Apache `mod_deflate` para HTML, CSS, JS, JSON
- Apache `mod_expires` para caché de assets

## Métricas

| Página | Consultas | Tiempo (ms) | Tamaño JS |
|---|---|---|---|
| Home | ~10 | ~200 | ~350 KB |
| Especialidades | ~5 | ~150 | ~350 KB |
| Médicos | ~6 | ~180 | ~350 KB |
| Panel admin | ~15 | ~500 | ~600 KB |

## Recomendaciones futuras
- Implementar paginación si los listados crecen > 100 registros.
- Considerar caché de consultas frecuentes si el tráfico crece.
- Evaluar CDN si el tráfico es internacional.