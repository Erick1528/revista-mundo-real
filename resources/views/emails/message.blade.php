{{--
    Vista para correos con solo título + mensaje.
    Extiende el layout. El Mailable debe pasar: 'title' y 'body'.
    (No usar $message: Laravel lo reserva para el objeto del correo.)
--}}
@extends('emails.layout')

@section('content')
    <p style="margin: 0 0 1em 0; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px; line-height: 1.6; color: #22221d;">
        {!! nl2br(e($body ?? '')) !!}
    </p>
@endsection
