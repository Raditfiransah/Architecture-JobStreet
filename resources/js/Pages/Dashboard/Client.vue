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
        <Head :title="'Dashboard Client - ' + user.name" />

        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-ink tracking-tight">Halo, {{ user.name }}.</h1>
                <p class="text-[15px] text-ink-muted mt-1.5 leading-relaxed">
                    Kelola proyek Anda dan temukan arsitek terbaik untuk mewujudkan desain impian Anda.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Link href="#" class="px-5 py-2.5 rounded-xl bg-primary-300 text-[13px] font-bold text-white shadow-md shadow-primary-100 hover:bg-primary-400 transition">
                    Posting Proyek Baru
                </Link>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            <StatCard title="Proyek Aktif" value="0" color="blue">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="Proposal Masuk" value="0" color="green">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="Proyek Selesai" value="0" color="purple">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </template>
            </StatCard>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-[#e4ede8] shadow-sm flex flex-col md:flex-row items-center justify-between gap-6 mb-10 overflow-hidden relative">
            <div class="relative z-10 flex-1">
                <h3 class="text-xl font-bold text-ink mb-2 text-center md:text-left">Cari Arsitek Terbaik</h3>
                <p class="text-[15px] text-ink-muted leading-relaxed text-center md:text-left">
                    Gunakan direktori kami untuk mencari arsitek berdasarkan spesialisasi dan portofolio mereka.
                </p>
            </div>
            <div class="relative z-10">
                <Link href="#" class="inline-flex items-center px-6 py-2.5 rounded-xl bg-ink hover:bg-ink-soft text-white font-bold text-sm transition">
                    Buka Direktori Arsitek
                </Link>
            </div>
            <!-- Accent -->
            <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-primary-100 rounded-full blur-[80px] opacity-30"></div>
        </div>

        <section>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-ink tracking-tight underline decoration-primary-300 decoration-4 underline-offset-8">Proyek Anda</h2>
            </div>
            <EmptyState 
                title="Satu langkah lagi!" 
                description="Buat proyek pertama Anda untuk mulai menerima proposal profesional dari sistem kami." 
                actionText="Posting Proyek Pertama"
                actionUrl="#"
            />
        </section>
    </AuthenticatedLayout>
</template>
