<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? 'Page Title' }}</title>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles
    @vite('resources/css/app.css')
</head>

<body class="bg-[#f1f1f4] dark:bg-gray-900" x-data="{ darkMode: false }" :class="{ 'dark': darkMode === true }"
    class="antialiased">
    <nav class="bg-[#f1f1f4] dark:bg-gray-900 fixed w-full z-20 top-0 start-0 dark:border-gray-600 mb-6">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src="{{ asset('img/logo.png') }}" class="h-20 dark:invert" alt="Flowbite Logo">
                {{-- <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Tramita</span> --}}
            </a>
            <div class="flex md:order-2 items-center gap-4">
                <button @click="darkMode=!darkMode" type="button"
                    class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" role="switch"
                    aria-checked="false">
                    <svg x-show="!darkMode" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-600 dark:text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>

                    <svg x-show="darkMode" xmlns="http://www.w3.org/2000/svg"
                        class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>
                <x-filament::button icon="solar-login-3-bold-duotone" href="{{ route('filament.admin.auth.login') }}"
                    tag="a">
                    Iniciar Seción
                </x-filament::button>
                {{-- <a href="{{ route('filament.admin.auth.login') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-indigo-500 text-white dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    Iniciar Sesión
                </a> --}}
            </div>
            <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
                <x-navlink :href="route('create-tramite')" :active="request()->routeIs('create-tramite')">
                    Tremite
                </x-navlink>
                <x-navlink>
                    Consulta
                </x-navlink>
            </div>
        </div>
    </nav>
    <main class="mt-20">
        {{ $slot }}
    </main>
    @livewire('notifications') {{-- Only required if you wish to send flash notifications --}}

    @filamentScripts
    @vite('resources/js/app.js')

</body>

</html>
