<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';

const props = defineProps({
    user: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Dashboard ' + user.name" />

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-ink tracking-tight">Halo, {{ user.name }}.</h1>
                <p class="text-[15px] text-ink-muted mt-1.5 leading-relaxed">
                    Lengkapi profilmu untuk mulai melamar dan temukan proyek impianmu.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Link href="#" class="px-5 py-2.5 rounded-xl bg-white border border-[#e4ede8] text-[13px] font-bold text-ink hover:bg-surface-muted transition">
                    Pengaturan Akun
                </Link>
                <Link href="#" class="px-5 py-2.5 rounded-xl bg-primary-300 text-[13px] font-bold text-white shadow-md shadow-primary-100 hover:bg-primary-400 transition">
                    Cari Lowongan
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <StatCard title="Lamaran Dikirim" value="0" color="blue">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="Proposal Aktif" value="0" color="green">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v1m0 8v1m0-1v-1m-3.301-3h6.602" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="Profil Dilihat" value="0" color="purple">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </template>
            </StatCard>
        </div>

        <div class="bg-primary-50 border border-primary-100 rounded-2xl p-6 md:p-8 mb-10 flex flex-col md:flex-row items-center justify-between gap-6 overflow-hidden relative shadow-sm">
            <div class="relative z-10 flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-2 h-2 rounded-full bg-primary-400"></span>
                    <h3 class="text-lg font-bold text-ink">Profil belum lengkap</h3>
                </div>
                <p class="text-[15px] text-ink-muted leading-relaxed max-w-lg">
                    Lengkapi profil dan portofolio Anda untuk meningkatkan peluang mendapatkan proyek impian hingga 80%.
                </p>
                <div class="mt-6 flex flex-wrap gap-4">
                    <Link href="#" class="px-6 py-2.5 rounded-xl bg-primary-300 hover:bg-primary-400 text-white font-bold text-sm transition">
                        Lengkapi Sekarang
                    </Link>
                </div>
            </div>
            
            <!-- Abstract illustration -->
            <div class="hidden lg:block absolute -right-4 -bottom-4 w-64 h-64 bg-primary-100 rounded-full blur-[60px] opacity-70"></div>
            <div class="relative z-10 w-full md:w-auto">
                <div class="grid grid-cols-2 gap-3 sm:gap-4 h-full">
                    <div class="w-32 h-32 rounded-2xl bg-white/60 backdrop-blur-md border border-white/40 flex items-center justify-center p-4 transform rotate-6">
                        <div class="w-full h-full bg-primary-100 rounded-lg flex items-center justify-center text-primary-500 font-bold text-xl uppercase tracking-widest">{{ user.name.charAt(0) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-ink tracking-tight underline decoration-primary-300 decoration-4 underline-offset-8">Aktivitas Terkini</h2>
            </div>
            <EmptyState 
                title="Belum ada aktivitas" 
                description="Aktivitas Anda akan muncul di sini setelah Anda mulai melamar proyek atau melengkapi profil profesional Anda." 
                actionText="Cari Proyek Pertama"
                actionUrl="#"
            />
        </section>
    </AuthenticatedLayout>
</template>
