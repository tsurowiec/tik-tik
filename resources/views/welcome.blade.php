<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts
        @vite(['resources/css/app.css'])
    </head>
    <body class="min-h-screen bg-indigo-50 dark:bg-zinc-950 text-indigo-950 dark:text-zinc-100 antialiased">
        <main class="flex min-h-screen flex-col items-center justify-center px-6">
            <div class="w-full max-w-md text-center">
                <div class="mx-auto mb-8 flex h-14 w-14 items-center justify-center rounded-2xl bg-indigo-950 text-indigo-50 shadow-lg shadow-indigo-950/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-7 w-7">
                        <circle cx="12" cy="13" r="8"/>
                        <path d="M12 9v4l2 2"/>
                        <path d="M5 3 2 6"/>
                        <path d="m22 6-3-3"/>
                    </svg>
                </div>

                <h1 class="text-3xl font-semibold tracking-tight text-indigo-950 dark:text-zinc-50 sm:text-4xl">
                    {{ config('app.name', 'Laravel') }}
                </h1>
                <p class="mt-3 text-base text-indigo-950/60 dark:text-zinc-400">
                    A quiet place for tasks and countdowns.
                </p>

                <div class="mt-10 flex justify-center">
                    @auth
                        <a href="{{ route('tasks') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-indigo-950 px-6 py-2.5 text-sm font-medium text-indigo-50 shadow-sm transition hover:bg-indigo-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-950 focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-50 dark:focus-visible:ring-offset-zinc-950">
                            Tasks
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="ml-1.5 h-4 w-4">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                           class="inline-flex items-center justify-center rounded-lg bg-indigo-950 px-6 py-2.5 text-sm font-medium text-indigo-50 shadow-sm transition hover:bg-indigo-900 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-950 focus-visible:ring-offset-2 focus-visible:ring-offset-indigo-50 dark:focus-visible:ring-offset-zinc-950">
                            Log in
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="ml-1.5 h-4 w-4">
                                <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                    @endauth
                </div>
            </div>
        </main>
    </body>
</html>
