@props(['active' => false, 'href' => '#'])

<a 
    href="{{ $href }}" 
    {{ $attributes->merge([
        'class' => ($active 
            ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-200' 
            : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700') . 
            ' flex items-center p-4 text-sm font-medium transition-colors duration-200'
    ]) }}
>
    {{ $slot }}
</a>