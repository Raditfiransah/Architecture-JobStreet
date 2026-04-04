<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: String,
    description: String,
});
</script>

<template>
    <div class="min-h-screen bg-surface flex flex-col font-sans antialiased text-ink">
        <Head :title="title" />
        
        <!-- Minimal Header -->
        <header class="h-20 bg-white border-b border-surface-muted flex items-center px-6 md:px-12 justify-between sticky top-0 z-50">
            <Link :href="route('home')" class="text-xl font-extrabold tracking-tighter text-ink flex items-center gap-2">
                <span class="w-8 h-8 rounded-lg bg-primary-300 flex items-center justify-center text-white text-xs">L</span>
                LokerArsitek
            </Link>
            <Link :href="route('home')" class="text-sm font-bold text-primary-500 hover:text-primary-600 transition">
                Kembali ke Beranda
            </Link>
        </header>

        <main class="flex-1 flex items-center justify-center p-6 bg-surface-soft">
            <div class="max-w-2xl w-full text-center">
                <div class="w-24 h-24 bg-primary-50 text-primary-300 rounded-full flex items-center justify-center mx-auto mb-8 animate-pulse">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight text-ink mb-4">{{ title }}</h1>
                <p class="text-lg text-ink-muted leading-relaxed mb-10">
                    {{ description || 'Halaman ini sedang dalam tahap pengembangan sistem untuk memberikan pengalaman mencari kerja yang lebih baik bagi arsitek Indonesia.' }}
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <Link :href="route('home')" class="px-8 py-3 rounded-xl bg-ink text-white font-bold text-sm shadow-xl shadow-ink/20 hover:scale-[1.02] active:scale-[0.98] transition">
                        Kembali ke Home
                    </Link>
                    <Link :href="route('register')" class="px-8 py-3 rounded-xl border-2 border-primary-300 text-primary-500 font-bold text-sm hover:bg-primary-50 transition">
                        Daftar Akun Baru
                    </Link>
                </div>
            </div>
        </main>

        <footer class="py-12 bg-white border-t border-surface-muted text-center">
            <p class="text-sm text-ink-muted">© 2024 Loker Arsitek Indonesia. Premium Recruitment Platform.</p>
        </footer>
    </div>
</template>
