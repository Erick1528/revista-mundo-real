<?php

if (!function_exists('rol_label')) {
    /**
     * Devuelve la etiqueta en español para un rol de usuario.
     */
    function rol_label(?string $rol): string
    {
        $roles = [
            'writer_junior' => 'Escritor Junior',
            'writer_senior' => 'Escritor Senior',
            'editor_junior' => 'Editor Junior',
            'editor_senior' => 'Editor Senior',
            'editor_chief' => 'Editor Jefe',
            'moderator' => 'Moderador',
            'administrator' => 'Administrador',
        ];
        return $rol ? ($roles[$rol] ?? $rol) : '';
    }
}

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

if (!function_exists('get_validated_image_dimensions')) {
    /**
     * Lee las dimensiones de una imagen sin cargarla en memoria y valida que no supere
     * el límite de megapíxeles (evita agotar memoria al procesar con GD).
     *
     * @param string $sourcePath Ruta absoluta al archivo de imagen
     * @param int $maxPixels Máximo de píxeles (ancho × alto). Por defecto 12 Mpx (4000×3000 aprox.)
     * @return array{0: int, 1: int} [width, height]
     * @throws \Exception Si no se puede leer la imagen o supera el límite
     */
    function get_validated_image_dimensions(string $sourcePath, int $maxPixels = 12000000): array
    {
        $imageInfo = @getimagesize($sourcePath);
        if ($imageInfo === false) {
            throw new \Exception('No se puede leer la información de la imagen.');
        }
        $width = (int) $imageInfo[0];
        $height = (int) $imageInfo[1];
        if ($width * $height > $maxPixels) {
            throw new \Exception('La imagen tiene demasiados megapíxeles. Redúcela (máx. recomendado 4000×3000) o usa un tamaño menor.');
        }
        return [$width, $height];
    }
}

if (!function_exists('generateUniqueSlug')) {
    /**
     * Genera un slug único basado en título y subtítulo
     *
     * @param string $title
     * @param string|null $subtitle
     * @param int|null $excludeArticleId ID del artículo a excluir de la verificación (útil para update)
     * @return string
     */
    function generateUniqueSlug($title, $subtitle = null, $excludeArticleId = null)
    {
        // Crear slug base desde título o título + subtítulo
        $baseText = trim($title);
        if (!empty($subtitle)) {
            $baseText = trim($title . ' ' . $subtitle);
        }

        // Generar slug base
        $baseSlug = \Illuminate\Support\Str::slug($baseText);

        // Si el slug base está vacío, usar un slug genérico
        if (empty($baseSlug)) {
            $baseSlug = 'articulo';
        }

        // Verificar si el slug existe
        $slug = $baseSlug;
        $counter = 1;

        while (true) {
            $query = \App\Models\Article::where('slug', $slug);
            
            // Excluir el artículo actual si se está actualizando
            if ($excludeArticleId !== null) {
                $query->where('id', '!=', $excludeArticleId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

if (!function_exists('generateUniqueSlugForAd')) {
    /**
     * Genera un slug único para un anuncio (tabla ads).
     *
     * @param string $name Nombre del anuncio
     * @param int|null $excludeAdId ID del anuncio a excluir (útil para update)
     * @return string
     */
    function generateUniqueSlugForAd(string $name, ?int $excludeAdId = null): string
    {
        $baseSlug = \Illuminate\Support\Str::slug(trim($name));
        if ($baseSlug === '') {
            $baseSlug = 'anuncio';
        }
        $slug = $baseSlug;
        $counter = 1;
        while (true) {
            $query = \App\Models\Ad::where('slug', $slug);
            if ($excludeAdId !== null) {
                $query->where('id', '!=', $excludeAdId);
            }
            if (!$query->exists()) {
                break;
            }
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        return $slug;
    }
}
