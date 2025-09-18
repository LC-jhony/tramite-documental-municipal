@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'px-4 py-2 mt-2 text-sm font-semibold text-gray-900 dark:text-white bg-gray-200 dark:bg-gray-700 rounded-lg md:mt-0 md:ml-4 hover:text-gray-900 dark:hover:text-white focus:text-gray-900 dark:focus:text-white hover:bg-gray-200 dark:hover:bg-gray-600 focus:bg-gray-200 dark:focus:bg-gray-600 focus:outline-none focus:shadow-outline'
            : 'px-4 py-2 mt-2 text-sm font-semibold text-gray-900 dark:text-white bg-transparent rounded-lg md:mt-0 md:ml-4 hover:text-gray-900 dark:hover:text-white focus:text-gray-900 dark:focus:text-white hover:bg-gray-200 dark:hover:bg-gray-600 focus:bg-gray-200 dark:focus:bg-gray-600 focus:outline-none focus:shadow-outline';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
