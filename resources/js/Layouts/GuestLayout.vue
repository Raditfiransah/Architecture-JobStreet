<script setup>
import { Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-surface-soft font-sans antialiased py-12 px-6">
        <div class="mb-8">
            <Link :href="route('home')">
                <ApplicationLogo class="scale-110" />
            </Link>
        </div>

        <div class="w-full sm:max-w-[440px] bg-white border border-[#e4ede8] p-8 md:p-10 rounded-2xl shadow-xl shadow-primary-100/20">
            <slot />
        </div>
        
        <div class="mt-8 text-center text-sm text-ink-muted/60">
            © 2026 Loker Arsitek. Hak Cipta Dilindungi.
        </div>
    </div>
</template>
