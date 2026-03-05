@props([
    'src' => null,
    'alt' => '',
    'imgClass' => '',
    'fallbackClass' => 'flex items-center justify-center bg-gray-100 border border-gray-lighter text-gray-400 min-h-[80px]',
    'imgAttributes' => [],
])
@php
    $wrapperClass = trim($attributes->get('class', ''));
    $isAbsolute = str_contains($wrapperClass, 'absolute');
    $fallbackSvg = '<svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 sm:w-12 sm:h-12 shrink-0 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>';
@endphp
<div {{ $attributes->merge(['class' => $wrapperClass . ($isAbsolute ? '' : ' relative')]) }} x-data="{ imgError: false }">
    @if (!empty(trim((string) $src)))
        <img src="{{ $src }}"
             alt="{{ $alt }}"
             class="{{ $imgClass }}"
             x-show="!imgError"
             x-on:error="imgError = true"
             @foreach ($imgAttributes as $key => $value)
                 {{ $key }}="{{ e($value) }}"
             @endforeach>
        <div x-show="imgError"
             class="{{ $fallbackClass }} absolute inset-0"
             style="display: none;"
             aria-hidden="true">{!! $fallbackSvg !!}</div>
    @else
        <div class="{{ $fallbackClass }}" aria-hidden="true">{!! $fallbackSvg !!}</div>
    @endif
</div>
