<?php

if (!function_exists('markdownLite')) {
    /**
     * Convierte markdown básico (negrita e itálica) a HTML
     *
     * @param string $text
     * @return string
     */
    function markdownLite($text)
    {
        // Negrita (soporta espacios antes y después)
        $text = preg_replace('/\*\*\s*(.*?)\s*\*\*/', '<strong>$1</strong>', $text);
        // Itálica (soporta espacios antes y después)
        $text = preg_replace('/\*\s*(.*?)\s*\*/', '<em>$1</em>', $text);
        return $text;
    }
}

if (!function_exists('fixStrongSpacing')) {
    /**
     * Agrega espacios antes y después de <strong> si está pegado a letras
     *
     * @param string $html
     * @return string
     */
    function fixStrongSpacing($html)
    {
        // Espacio antes de <strong> si está pegado a una letra
        $html = preg_replace('/([a-zA-ZáéíóúÁÉÍÓÚñÑ])<strong>/', '$1 <strong>', $html);

        // Espacio después de </strong> si está pegado a una letra
        $html = preg_replace('/<\/strong>([a-zA-ZáéíóúÁÉÍÓÚñÑ])/', '</strong> $1', $html);

        return $html;
    }
}
