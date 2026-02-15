@extends('emails.layout')

@section('content')
    {{-- Mensaje introductorio --}}
    <p style="margin: 0 0 20px 0; font-family: 'Open Sans', Arial, sans-serif; font-size: 15px; line-height: 1.65; color: #22221d;">
        {!! nl2br(e($introMessage)) !!}
    </p>

    {{-- Card: misma línea que la web (border gray-lighter #d8d8d1, padding, sin rounded) --}}
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin: 0 0 24px 0; border: 1px solid #d8d8d1; background-color: #ffffff;">
        <tr>
            <td style="padding: 0;">
                @if($articleImageUrl ?? null)
                <img src="{{ $articleImageUrl }}" alt="{{ $article->image_alt_text ?? $article->title }}" width="100%" style="display: block; max-width: 100%; height: auto; max-height: 220px; object-fit: cover; border-bottom: 1px solid #d8d8d1;" />
                @endif
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tr>
                        <td style="padding: 16px 24px 24px 24px;">
                            <p style="margin: 0 0 8px 0; font-family: 'Playfair Display', Georgia, 'Times New Roman', serif; font-size: 18px; color: #22221d; line-height: 1.3;">
                                {{ $article->title }}
                            </p>
                            <p style="margin: 0 0 4px 0; font-family: 'Montserrat', Arial, sans-serif; font-size: 12px; color: #72726b;">
                                Autor: {{ $article->user?->name ?? '—' }}
                            </p>
                            <p style="margin: 0; font-family: 'Montserrat', Arial, sans-serif; font-size: 12px; color: #72726b;">
                                Fecha: {{ $fecha ?? '' }}
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Botón CTA (estilos alineados al dashboard/formularios) --}}
    <p style="margin: 0 0 1em 0;">
        <a href="{{ $buttonUrl }}" style="display: inline-block; padding: 12px 20px; font-family: 'Montserrat', Arial, sans-serif; font-size: 14px; font-weight: 500; color: #22221d; background-color: transparent; border: 1px solid #22221d; text-decoration: none; transition: background-color 0.2s, color 0.2s;">
            {{ $buttonText }}
        </a>
    </p>
@endsection
