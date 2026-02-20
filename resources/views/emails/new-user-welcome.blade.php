@extends('emails.layout')

@section('content')
    <p style="margin: 0 0 1em 0; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #22221d;">
        Hola {{ $user->name }},
    </p>
    <p style="margin: 0 0 1em 0; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #22221d;">
        Se ha creado una cuenta para ti en Revista Mundo Real. Puedes acceder al panel con tu correo y la siguiente contraseña temporal:
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 16px 0 20px 0;">
        <tr>
            <td style="padding: 14px 16px; font-family: 'Montserrat', monospace; font-size: 15px; font-weight: 500; color: #22221d; background-color: #f5f4f0; border: 1px solid #d8d8d1;">
                {{ $temporaryPassword }}
            </td>
        </tr>
    </table>
    <p style="margin: 0 0 1em 0; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #22221d;">
        Te recomendamos iniciar sesión y cambiar esta contraseña desde tu perfil (Panel → Perfil) cuando lo desees.
    </p>
    @if(isset($loginUrl) && $loginUrl)
    <p style="margin: 1em 0 0 0;">
        <a href="{{ $loginUrl }}" style="display: inline-block; padding: 12px 16px; font-family: 'Montserrat', Arial, sans-serif; font-size: 14px; font-weight: 500; color: #22221d; background-color: transparent; border: 1px solid #22221d; text-decoration: none;">
            Acceder al panel
        </a>
    </p>
    @endif
@endsection
