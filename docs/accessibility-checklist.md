# Checklist de Accesibilidad y Responsive

## Herramientas usadas
- Lighthouse (Chrome DevTools)
- axe DevTools (extensión)
- Navegación por teclado
- Zoom al 200%
- Dispositivos reales (Android, iOS)

## Resultados de Lighthouse
|  ----  ----  ----  ----  ---- Test Desktop  ----  ----  ----  ----  ----  |
| Página               | Performance | Accessibility | Best Practices | SEO |
|----------------------|-------------|---------------|----------------|-----|
| Home                 |     96      |       86      |       74       | 92  |
| Especialidades       |     98      |       86      |       74       | 100 |
| Médicos              |     98      |       84      |       78       | 100 |
| Servicios            |     98      |       84      |       78       | 100 |
| Sobre Nosotros       |     99      |       86      |       78       | 100 |
| Contacto             |     97      |       92      |       78       | 100 |
| Citas                |     98      |       88      |       78       | 100 |
| Mi cuenta            |     97      |       87      |       78       | 100 |
| Mi cuenta - Citas    |     55      |       84      |       78       | 100 |
| Mi cuenta - Perfil   |     97      |       88      |       78       | 100 |

|  ----  ----  ----  ----  ---- Test Mobile   ----  ----  ----  ----  ----  |
| Página               | Performance | Accessibility | Best Practices | SEO |
|----------------------|-------------|---------------|----------------|-----|
| Home                 |     84      |       91      |       74       | 92  |
| Especialidades       |     86      |       90      |       78       | 100 |
| Médicos              |     67      |       90      |       78       | 100 |
| Servicios            |     67      |       90      |       78       | 100 |
| Sobre Nosotros       |     87      |       91      |       78       | 100 |
| Contacto             |     84      |       92      |       78       | 100 |
| Citas                |     64      |       91      |       78       | 100 |
| Mi cuenta            |     83      |       91      |       78       | 100 |
| Mi cuenta - Citas    |     54      |       89      |       78       | 92  |
| Mi cuenta - Perfil   |     61      |       91      |       78       | 100 |

## Issues encontrados y resueltos

1. Garantiza que el contraste entre colores de primer plano y fondo cumple los límites de la ratio para contraste WCAG 2 AA

Ubicación del elemento: p:nth-child(1)
Para solucionar este problema, debes corregir lo siguiente:
El elemento tiene un contraste de color insuficiente de 3.66 (color de primer plano: #6a7282, color de fondo: #101828, tamaño de fuente: 10.5pt (14px), grosor de fuente: normal). Ratio de contraste esperado: 4.5:1

Nodo relacionado
<footer class="bg-gray-900 text-white">

2. Garantiza que el contraste entre colores de primer plano y fondo cumple los límites de la ratio para contraste WCAG 2 AA

Ubicación del elemento:.border-t > p:nth-child(2)
<p>Desarrollado por <a href="https://jumpdaedalus.com/" class="text-primary-400 hover:text-primary-500">Jump Daedalus</a></p>

Para solucionar este problema, debes corregir lo siguiente:
El elemento tiene un contraste de color insuficiente de 3.66 (color de primer plano: #6a7282, color de fondo: #101828, tamaño de fuente: 10.5pt (14px), grosor de fuente: normal). Ratio de contraste esperado: 4.5:1

Nodo relacionado
<footer class="bg-gray-900 text-white">

3. Los enlaces pueden distinguirse sin depender del color
Ubicación del elemento: .text-primary-400
<a href="https://jumpdaedalus.com/" class="text-primary-400 hover:text-primary-500">Jump Daedalus</a>

Para resolver este problema, es necesario solucionar al menos (1) de los siguientes problemas:
Es necesario distinguir los enlaces respecto al texto adyacente de una forma ajena al color
The link has no styling (such as underline) to distinguish it from the surrounding text

Nodos relacionados
<p>Desarrollado por <a href="https://jumpdaedalus.com/" class="text-primary-400 hover:text-primary-500">Jump Daedalus</a></p>

### Portal público
- ✅ Alt en imágenes de servicios.
- ✅ Labels en formulario de contacto.
- ✅Contraste en footer.

### Portal del paciente
- ✅ Navegación por teclado en sidebar.
- ✅ Focus visible en botones.

## Dispositivos verificados

| Dispositivo | Navegador | Estado |
|---|---|---|
| iPhone SE (375px) | Safari | ✅ |
| iPhone 14 (393px) | Safari | ✅ |
| Android Pixel (412px) | Chrome | ✅ |
| iPad Mini (768px) | Safari | ✅ |
| Desktop (1920px) | Chrome | ✅ |

## Notas
- Las pruebas del panel administrativo de dejaron para despues del despliegue, las vista inical que vera le cliente y los visitantes es la prioridad