@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Tik-Tik" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-10 items-center justify-center rounded-full bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-6 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Tik-Tik" {{ $attributes->class('[&>div:last-child]:text-xl [&>div:last-child]:font-bold') }}>
        <x-slot name="logo" class="flex aspect-square size-10 items-center justify-center rounded-full bg-accent-content text-accent-foreground">
            <x-app-logo-icon class="size-6 fill-current text-white dark:text-black" />
        </x-slot>
    </flux:brand>
@endif
