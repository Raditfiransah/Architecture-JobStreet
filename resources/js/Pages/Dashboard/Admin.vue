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
        <Head title="Admin Dashboard - Web Architect" />

        <div class="mb-10">
            <h1 class="text-3xl font-extrabold text-ink tracking-tight underline decoration-primary-300 decoration-4 underline-offset-8">Admin Dashboard</h1>
            <p class="text-[15px] text-ink-muted mt-4 leading-relaxed">
                Kelola infrastruktur platform, verifikasi pengguna, dan moderasi konten dalam satu panel kontrol.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <StatCard title="Lowongan Pending" value="0" color="amber">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="Proyek Pending" value="0" color="blue">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="Verifikasi Arsitek" value="0" color="purple">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </template>
            </StatCard>
            <StatCard title="User Baru" value="0" color="green">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                </template>
            </StatCard>
        </div>

        <div class="bg-white rounded-2xl border border-[#e4ede8] shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b border-[#e4ede8] flex items-center justify-between bg-surface-soft">
                <h3 class="text-xl font-bold text-ink tracking-tight uppercase">Aktivitas Antrian Moderasi</h3>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800">Operational</span>
                </div>
            </div>
            <div class="p-10">
                <EmptyState 
                    title="Antrian bersih!" 
                    description="Semua item moderasi sudah ditangani oleh sistem. Belum ada aksi manual yang diperlukan saat ini." 
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
