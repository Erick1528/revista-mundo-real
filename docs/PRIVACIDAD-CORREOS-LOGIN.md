# Privacidad: correo de inicio de sesión (IP y ubicación)

## Resumen

En la notificación por correo al iniciar sesión se pueden incluir la **IP (enmascarada)** y la **ubicación aproximada** (ciudad, región, país) para que el usuario detecte accesos no autorizados. En el correo **no se muestra la IP completa**: solo los dos primeros octetos (IPv4) o los dos primeros grupos (IPv6), por ejemplo `88.12.***.***` y `Girona, Cataluña, España`. Así se reduce el riesgo normativo (GDPR, CCPA, etc.) porque no se expone un identificador completo. La IP completa solo se usa en el servidor para consultar la ubicación a ip-api.com y no se guarda en el correo.

### Normativas

| Ámbito | Consideración |
|--------|----------------|
| **UE (GDPR)** | La IP es dato personal. El uso para seguridad puede apoyarse en **interés legítimo**, pero debe constar en la política de privacidad y ser proporcionado. |
| **EE. UU. (p. ej. CCPA)** | La IP puede ser información personal; suele exigirse transparencia y, en su caso, derechos de acceso/eliminación. |
| **Honduras** | Derecho a la intimidad (Const. art. 76) y hábeas data; conviene informar el tratamiento y minimizar datos. |

**Recomendación:** Indicar en la política de privacidad que se usa la IP (y, si aplica, un servicio de geolocalización) con fines de seguridad en el aviso de inicio de sesión.

---

## Configuración para cumplir normativas

En el `.env` puedes ajustar el comportamiento sin tocar código:

- **`MAIL_LOGIN_NOTIFY_IP=false`**  
  El correo de login **no** incluye IP ni ubicación. Opción más restrictiva (p. ej. si quieres evitar cualquier tratamiento de IP en el correo).

- **`MAIL_LOGIN_GEOLOCATION=false`**  
  El correo **sí** incluye la IP enmascarada (p. ej. `88.12.***.***`), pero **no** se llama a ip-api.com: no se envía la IP a terceros ni se muestra ubicación.

Con ambas en `true` (por defecto), se muestra IP y ubicación y la IP se envía a ip-api.com para obtener ciudad/región/país. En ese caso, conviene mencionarlo en la política de privacidad.

---

## Texto sugerido para tu política de privacidad

Puedes adaptar y pegar algo como lo siguiente en la sección correspondiente (seguridad / correos / datos que tratamos):

> **Notificaciones de seguridad (inicio de sesión)**  
> Cuando inicias sesión en tu cuenta, te enviamos un correo para que puedas comprobar que el acceso lo has hecho tú. Ese correo puede incluir una parte enmascarada de la dirección IP (por ejemplo, solo los primeros segmentos, no la IP completa) y, en algunos casos, una ubicación aproximada (ciudad, región, país) obtenida mediante un servicio externo de geolocalización, con la única finalidad de ayudarte a detectar accesos no autorizados. No conservamos direcciones IP completas en nuestras bases de datos de forma permanente asociadas a tu cuenta. Si has configurado la aplicación para no incluir la IP o la geolocalización en estos correos, esa información no se incluirá ni se enviará a terceros.

(Adapta “servicio externo” si quieres nombrar explícitamente a ip-api.com y enlazar su política de privacidad.)

---

## Servicio de geolocalización (ip-api.com)

- Uso gratuito (no comercial): [ip-api.com](https://ip-api.com).
- Política de privacidad: <https://ip-api.com/docs/legal>  
  En el plan gratuito indican que la IP se mantiene en RAM como máximo 1 minuto para limitación de tasa y que no conservan logs de las peticiones.

Si no quieres enviar la IP a ningún tercero, configura **`MAIL_LOGIN_GEOLOCATION=false`** en tu `.env`.
