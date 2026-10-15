# Solución de Problemas

Guía para diagnosticar y resolver problemas comunes.

## Problemas del sistema

### Error 500 — Internal Server Error

**Síntomas:** El sitio muestra "500 Internal Server Error".

**Diagnóstico:**

### 1. Revisar el log:

tail -100 storage/logs/laravel.log

### 2. Buscar el error específico en el log.