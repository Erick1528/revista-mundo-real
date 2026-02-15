<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Revista Mundo Real' }}</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&family=Open+Sans:wght@400&family=Playfair+Display:wght@400&display=swap');
    </style>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500&family=Open+Sans:wght@400&family=Playfair+Display:wght@400&display=swap" rel="stylesheet">
</head>
<body style="margin: 0; padding: 0; background-color: #faf8f8;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #faf8f8;">
        <tr>
            <td align="center" style="padding: 40px 20px 40px 20px;">
                <table role="presentation" width="560" cellspacing="0" cellpadding="0" border="0" style="max-width: 560px; width: 100%; background-color: #ffffff; border: 1px solid #d8d8d1;">

                    {{-- Cabecera: solo texto (se adapta al tema del dispositivo; la imagen no) --}}
                    <tr>
                        <td align="center" style="padding: 40px 32px 24px 32px;">
                            <p style="margin: 0; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-size: 28px; color: #22221d; text-align: center; line-height: 1.2;">Revista Mundo Real</p>
                            <p style="margin: 10px 0 0 0; font-family: 'Montserrat', Arial, sans-serif; font-size: 11px; color: #72726b; text-align: center; text-transform: uppercase; letter-spacing: 0.08em;">Conectando Culturas · Inspirando Viajes · Celebrando Tradiciones</p>
                            <table role="presentation" width="40" cellspacing="0" cellpadding="0" border="0" style="margin: 20px auto 0 auto;">
                                <tr>
                                    <td style="height: 2px; background-color: #b7b699;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Cuerpo del mensaje --}}
                    <tr>
                        <td style="padding: 12px 32px 44px 32px;">
                            @if(!empty($title))
                            <p style="margin: 0 0 20px 0; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-size: 20px; color: #22221d;">
                                {{ $title }}
                            </p>
                            @endif
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><td style="font-family: 'Open Sans', Arial, sans-serif; font-size: 15px; line-height: 1.65; color: #22221d;">
                                @yield('content')
                            </td></tr></table>
                        </td>
                    </tr>

                    {{-- Pie: discreto, misma línea de acento --}}
                    <tr>
                        <td style="border-top: 1px solid #d8d8d1; padding: 28px 32px 32px 32px;">
                            <table role="presentation" width="40" cellspacing="0" cellpadding="0" border="0" style="margin: 0 auto 18px auto;">
                                <tr>
                                    <td style="height: 2px; background-color: #b7b699;"></td>
                                </tr>
                            </table>
                            <p style="margin: 0 0 6px 0; font-family: 'Montserrat', Arial, sans-serif; font-size: 13px; color: #72726b; text-align: center;">
                                <a href="{{ url('/') }}" style="color: #22221d; text-decoration: none;">Contacto</a> · <a href="{{ url('/') }}" style="color: #22221d; text-decoration: none;">Política de privacidad</a>
                            </p>
                            <p style="margin: 0; font-family: 'Montserrat', Arial, sans-serif; font-size: 11px; color: #72726b; text-align: center;">
                                &copy; {{ date('Y') }} Revista Mundo Real
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
