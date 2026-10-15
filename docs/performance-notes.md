# Notas de Rendimiento

## Optimizaciones aplicadas

### 1. Eager Loading
- `DoctorController@index`: `->with('specialties')`
- `DoctorController@show`: `->load('specialties', 'schedules')`
- `AppointmentController@index`: `->with('doctor', 'specialty')`
- `DashboardController@index`: `->with(['doctor', 'specialty'])`

### 2. Cacheo
- `clinic_info`: 1 hora (invalidado al guardar desde Filament)
- (Opcional) `clinic_stats_widget`: 5 minutos

### 3. Lazy loading de imágenes
- `ServiceCard.jsx`: `loading="lazy"`
- `DoctorCard.jsx`: `loading="lazy"`

### 4. Índices de BD
- `appointments`: `status`, `requested_date`, `(status, expires_at)`
- `specialties`: `category`, `is_active`
- `doctor_schedules`: `(doctor_id, day_of_week, is_active)`

### 5. Code splitting (Vite)
- Bundle principal: 355 KB (React + Inertia + dependencias)
- Páginas individuales: 1-22 KB cada una (lazy loaded)

### 6. Compresión en Apache (producción)
- `mod_deflate` para HTML, CSS, JS, JSON
- `mod_expires` para caché de assets estáticos

### 7. HTTPS
- Activo en producción
- Certificado SSL válido

## Métricas de rendimiento

### Tamaño de assets (compilados)

| Asset | Tamaño |
|---|---|
| `app.js` (bundle principal) | 355 KB |
| `app.css` | 57 KB |
| Páginas individuales | 1-22 KB cada una |
| **Total estimado** | ~500 KB (sin comprimir) |
| **Total con gzip** | ~150 KB |

### Tiempos de respuesta (producción, desde Venezuela)

| URL | Tiempo |
|---|---|
| `/` | 1.512s |
| `/especialidades` | 1.210s |
| `/medicos` | 1.268s |
| `/servicios` | 1.197s |
| `/contacto` | 1.104s |
| **Promedio** | **~1.26s** |

**Nota:** Los tiempos incluyen latencia de red internacional. Desde el servidor mismo, los tiempos son significativamente menores (< 300ms).

### Puntuaciones de Lighthouse

Ver `docs/accessibility-checklist.md` para los resultados detallados.

## Consultas por página (test automatizado)

| Página | Consultas | Objetivo | Estado |
|---|---|---|---|
| Home | ~10 | < 15 | ✅ |
| Especialidades | ~5 | < 10 | ✅ |
| Médicos | ~6 | < 10 | ✅ |
| Perfil de médico | ~8 | < 10 | ✅ |
| Servicios | ~4 | < 10 | ✅ |

## Recomendaciones futuras (post-MVP)

### Corto plazo
- Cachear datos de la home (servicios, especialidades, médicos) por 5-10 minutos.
- Optimizar imágenes con WebP (conversión automática).
- Implementar compresión Brotli si el hosting lo soporta.

### Medio plazo
- Analizar bundle con `vite-bundle-visualizer` para eliminar dependencias no usadas.
- Implementar CDN para assets estáticos si el tráfico crece.
- Cachear consultas frecuentes con Redis si el hosting lo provee.

### Largo plazo
- Migrar a un VPS con más recursos si el tráfico crece.
- Implementar HTTP/2 o HTTP/3 si el hosting lo soporta.
- Monitoreo continuo con herramientas como New Relic o Sentry.