<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen flex flex-col bg-indigo-200/80 dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 dark:border-zinc-700 dark:bg-zinc-900 [&>div]:max-w-3xl">

            <x-app-logo href="{{ route('tasks') }}" wire:navigate />

            <flux:spacer />

            <x-desktop-user-menu />
        </flux:header>

<div id="container" class="flex-1 bg-indigo-50 dark:bg-mist-900">
            <div class="mx-auto w-full max-w-3xl">
                {{ $slot }}
            </div>
        </div>
        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
