<?php

namespace App\Notifications;

use App\Mail\SimpleMessageMail;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

class AuthNotificationService
{
    /**
     * Notificar al usuario que se ha iniciado sesión en su cuenta (seguridad).
     * Incluye IP y ubicación aproximada si se pasa la IP (p. ej. request()->ip()).
     */
    public static function notifyLogin(User $user, ?string $ip = null): void
    {
        if (! $user->email) {
            return;
        }

        $title = 'Inicio de sesión';
        $fecha = now()->translatedFormat('d/m/Y \a \l\a\s H:i');

        $bloqueUbicacion = '';
        if (config('mail.login_notify_ip', true) && $ip) {
            $ipEnmascarada = self::maskIp($ip);
            if (! self::isPrivateOrLocalIp($ip) && config('mail.login_geolocation', true)) {
                $location = self::resolveLocationFromIp($ip);
                $bloqueUbicacion = sprintf(
                    "IP (parcial): %s\nUbicación aproximada: %s\n\n",
                    $ipEnmascarada,
                    $location ?: 'No disponible'
                );
            } else {
                $bloqueUbicacion = sprintf("IP (parcial): %s\n\n", $ipEnmascarada);
            }
        }

        $body = sprintf(
            "Hola %s,\n\n"
            . "Se ha iniciado sesión en tu cuenta de Revista Mundo Real.\n\n"
            . "%s"
            . "Fecha y hora: %s\n\n"
            . "Si no fuiste tú, te recomendamos cambiar tu contraseña desde el panel (Perfil) lo antes posible.",
            $user->name,
            $bloqueUbicacion,
            $fecha
        );

        Mail::to($user->email)->send(new SimpleMessageMail($title, $body));
    }

    /**
     * Obtiene ubicación aproximada desde IP (ip-api.com): región y país, sin ciudad.
     * Formato general: "Cataluña, España" (menos detalle = más privacidad).
     */
    private static function resolveLocationFromIp(string $ip): ?string
    {
        try {
            $response = Http::timeout(3)->get('https://ip-api.com/json/'.urlencode($ip), [
                'fields' => 'status,regionName,country',
                'lang' => 'es',
            ]);
            if (! $response->successful()) {
                return null;
            }
            $data = $response->json();
            if (($data['status'] ?? '') !== 'success') {
                return null;
            }
            $region = $data['regionName'] ?? '';
            $country = $data['country'] ?? '';
            $parts = array_filter([$region, $country]);

            return $parts ? implode(', ', $parts) : ($country ?: null);
        } catch (\Throwable) {
            return null;
        }
    }

    private static function isPrivateOrLocalIp(string $ip): bool
    {
        return filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        ) === false;
    }

    /**
     * Enmascara la IP para mostrarla en el correo (solo parte visible).
     * Reduce riesgo normativo (GDPR, CCPA, etc.): no se expone la IP completa.
     * IPv4: primeros 2 octetos visibles (ej. 88.12.***.***). IPv6: primeros 2 grupos (ej. 2a01:4f8:***:***:...).
     */
    private static function maskIp(string $ip): string
    {
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $octetos = explode('.', $ip);

            return ($octetos[0] ?? '***').'.'.($octetos[1] ?? '***').'.***.***';
        }
        if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $grupos = array_values(array_filter(explode(':', $ip), fn ($g) => $g !== ''));
            $visibles = array_slice($grupos, 0, 2);

            return (implode(':', $visibles) ?: '***').':***:***:***:***:***:***';
        }

        return '***';
    }
}
