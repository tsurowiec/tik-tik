<x-layouts::app.header :title="$title ?? null">
    <flux:main class="pt-2! lg:pt-3!">
        {{ $slot }}
    </flux:main>
</x-layouts::app.header>
