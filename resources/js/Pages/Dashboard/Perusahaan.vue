<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';

const props = defineProps({
    user: Object,
    companyName: String,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Dashboard Perusahaan - ' + companyName" />

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-ink tracking-tight">Selamat datang, {{ companyName }}.</h1>
                <p class="text-[15px] text-ink-muted mt-1.5 leading-relaxed">
                    Kelola lowongan kerja dan temukan talenta arsitek terbaik untuk tim Anda.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Link href="#" class="px-5 py-2.5 rounded-xl bg-primary-300 text-[13px] font-bold text-white shadow-md shadow-primary-100 hover:bg-primary-400 transition">
                    Post Lowongan Baru
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <StatCard title="Lowongan Aktif" value="0" color="blue">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="Total Pelamar" value="0" color="green">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="Segera Expire" value="0" color="amber">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </StatCard>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
            <div class="bg-white p-8 rounded-2xl border border-[#e4ede8] shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-xl bg-primary-100 flex items-center justify-center text-primary-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-ink">Post Lowongan Pertama</h3>
                    </div>
                    <p class="text-[15px] text-ink-muted leading-relaxed">
                        Mulai cari arsitek terbaik untuk bergabung dengan tim desain atau proyek konstruksi Anda hari ini.
                    </p>
                </div>
                <div class="mt-8">
                    <Link href="#" class="inline-flex items-center px-6 py-2.5 rounded-xl bg-primary-300 hover:bg-primary-400 text-white font-bold text-sm transition">
                        Buat Lowongan Kerja
                    </Link>
                </div>
            </div>

            <div class="bg-ink p-8 rounded-2xl shadow-xl flex flex-col justify-between overflow-hidden relative group">
                <div class="relative z-10">
                    <p class="text-[11px] font-bold text-primary-300 uppercase tracking-widest mb-3">Paket Saat Ini</p>
                    <h3 class="text-2xl font-bold text-white mb-2">Paket Gratis (Starter)</h3>
                    <div class="mt-6 flex items-end gap-3 text-white">
                        <span class="text-4xl font-extrabold">0</span>
                        <span class="text-lg text-white/50 mb-1">/ 2 lowongan terpakai</span>
                    </div>
                </div>
                <!-- Abstract bg -->
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary-300 rounded-full blur-[80px] opacity-20 group-hover:opacity-30 transition duration-500"></div>
            </div>
        </div>

        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-ink tracking-tight underline decoration-primary-300 decoration-4 underline-offset-8 font-sans">Lowongan Aktif</h2>
            </div>
            <EmptyState 
                title="Belum ada lowongan aktif" 
                description="Buat lowongan pertama Anda untuk mulai menerima lamaran dari arsitek berbakat di Indonesia." 
                actionText="Post Lowongan Pertama"
                actionUrl="#"
            />
        </section>
    </AuthenticatedLayout>
</template>
