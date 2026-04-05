<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { 
    Clock, 
    FileText, 
    ShieldCheck, 
    Users, 
    Activity,
    ArrowUpRight
} from "lucide-vue-next";
import { Badge } from "@/Components/UI/ui/badge";

const props = defineProps({
    user: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Admin Dashboard - Web Architect" />

        <div class="mb-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <h1 class="text-4xl font-display font-black text-foreground tracking-tight leading-none mb-4">
                Admin Dashboard
            </h1>
            <p class="text-[15px] font-medium text-muted-foreground max-w-2xl leading-relaxed">
                Kelola infrastruktur platform, verifikasi pengguna, dan moderasi konten dalam satu panel kontrol terpadu yang dirancang untuk efisiensi maksimal.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <StatCard title="Lowongan Pending" value="0" color="amber">
                <template #icon>
                    <Clock class="w-5 h-5" />
                </template>
            </StatCard>
            <StatCard title="Proyek Pending" value="0" color="blue">
                <template #icon>
                    <FileText class="w-5 h-5" />
                </template>
            </StatCard>
            <StatCard title="Verifikasi Arsitek" value="0" color="purple">
                <template #icon>
                    <ShieldCheck class="w-5 h-5" />
                </template>
            </StatCard>
            <StatCard title="User Baru" value="0" color="green">
                <template #icon>
                    <Users class="w-5 h-5" />
                </template>
            </StatCard>
        </div>

        <div class="bg-card border-2 border-border/50 rounded-[32px] shadow-2xl shadow-primary/5 overflow-hidden animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-200">
            <div class="px-8 py-6 border-b border-border/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-muted/30">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-primary/10 rounded-xl text-primary">
                        <Activity class="w-5 h-5" />
                    </div>
                    <h3 class="text-lg font-display font-bold text-foreground uppercase tracking-widest">Antrian Moderasi</h3>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-background border border-border/50 rounded-full shadow-sm">
                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-emerald-600">Operational</span>
                    </div>
                </div>
            </div>
            <div class="p-10 md:p-20 bg-background/50">
                <EmptyState 
                    title="Antrian bersih!" 
                    description="Semua item moderasi sudah ditangani oleh sistem. Belum ada aksi manual yang diperlukan saat ini. Kerja bagus, Admin!" 
                />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
</style>
