### Deuda Técnica #00X — [Título corto]

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