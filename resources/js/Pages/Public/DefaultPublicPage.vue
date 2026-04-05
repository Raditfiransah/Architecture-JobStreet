<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from "@/Layouts/PublicLayout.vue";

defineProps({
    title: String,
    description: String,
});
</script>

<template>
    <PublicLayout>
        <Head :title="title" />

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

    </PublicLayout>
</template>
