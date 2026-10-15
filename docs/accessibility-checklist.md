# Checklist de Accesibilidad y Responsive

## Herramientas usadas
- Lighthouse (Chrome DevTools)
- axe DevTools (extensión)
- Navegación por teclado
- Zoom al 200%
- Dispositivos reales (Android, iOS)

## Resultados de Lighthouse

| Página | Performance | Accessibility | Best Practices | SEO |
|---|---|---|---|---|
| Home | ... | ... | ... | ... |
| Especialidades | ... | ... | ... | ... |
| Médicos | ... | ... | ... | ... |
| Servicios | ... | ... | ... | ... |
| Contacto | ... | ... | ... | ... |
| Citas | ... | ... | ... | ... |
| Mi cuenta | ... | ... | ... | ... |

## Issues encontrados y resueltos

### Portal público
- [x] Alt en imágenes de servicios.
- [x] Labels en formulario de contacto.
- [x] Contraste en footer.

### Portal del paciente
- [x] Navegación por teclado en sidebar.
- [x] Focus visible en botones.

### Panel admin
- [ ] (Filament maneja su propia accesibilidad)

## Dispositivos verificados

| Dispositivo | Navegador | Estado |
|---|---|---|
| iPhone SE (375px) | Safari | ✅ |
| iPhone 14 (393px) | Safari | ✅ |
| Android Pixel (412px) | Chrome | ✅ |
| iPad Mini (768px) | Safari | ✅ |
| Desktop (1920px) | Chrome | ✅ |

## Notas
- Los scores de Performance en desarrollo son bajos por Vite dev server. En producción (Fase 7) se verificarán de nuevo.
- Filament proporciona accesibilidad base. No auditamos cada componente del panel.