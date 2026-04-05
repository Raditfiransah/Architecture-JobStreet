<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { 
    Building2, 
    Users, 
    Clock, 
    PlusCircle, 
    Zap,
    ChevronRight,
    CheckCircle2
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";
import { Card, CardContent } from "@/Components/UI/ui/card";

const props = defineProps({
    user: Object,
    companyName: String,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Dashboard Perusahaan - ' + companyName" />

        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <div>
                <h1 class="text-4xl font-display font-black text-foreground tracking-tight leading-none mb-3">
                    Selamat datang, {{ companyName }}.
                </h1>
                <p class="text-[15px] font-medium text-muted-foreground max-w-2xl leading-relaxed">
                    Kelola lowongan kerja perusahaan dalam skala besar dan temukan talenta arsitek terbaik untuk memperkuat tim Anda.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Button asChild class="rounded-xl font-bold h-11 shadow-xl shadow-primary/20 px-8">
                    <Link href="#">
                        <PlusCircle class="w-4 h-4 mr-2" />
                        Post Lowongan Baru
                    </Link>
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
            <StatCard title="Lowongan Aktif" value="0" color="blue">
                <template #icon><Building2 class="w-5 h-5" /></template>
            </StatCard>
            <StatCard title="Total Pelamar" value="0" color="green">
                <template #icon><Users class="w-5 h-5" /></template>
            </StatCard>
            <StatCard title="Segera Expire" value="0" color="amber">
                <template #icon><Clock class="w-5 h-5" /></template>
            </StatCard>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12 animate-in fade-in slide-in-from-bottom-6 duration-1000 delay-200">
            <Card class="rounded-[32px] border-2 border-border/50 shadow-2xl shadow-primary/5 flex flex-col overflow-hidden group">
                <CardContent class="p-8 md:p-10 flex flex-col justify-between h-full space-y-8 relative">
                    <div class="absolute -right-16 -bottom-16 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors"></div>
                    <div class="relative z-10 space-y-4">
                        <div class="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary group-hover:scale-110 transition-transform">
                            <PlusCircle class="w-7 h-7" />
                        </div>
                        <h3 class="text-2xl font-display font-black text-foreground">Post Lowongan Kerja</h3>
                        <p class="text-[15px] font-medium text-muted-foreground leading-relaxed">
                            Mulai cari arsitek terbaik untuk bergabung dengan tim desain atau proyek konstruksi Anda melalui jaringan kami.
                        </p>
                    </div>
                    <Button variant="outline" asChild class="w-fit rounded-xl font-bold border-border/80 hover:border-primary/30 h-12 shadow-sm">
                        <Link href="#">Buat Lowongan Sekarang</Link>
                    </Button>
                </CardContent>
            </Card>

            <Card class="rounded-[32px] bg-foreground text-background shadow-2xl shadow-foreground/20 flex flex-col overflow-hidden relative group">
                <CardContent class="p-8 md:p-10 flex flex-col justify-between h-full space-y-8 relative">
                    <!-- Abstract bg -->
                    <div class="absolute -right-20 -top-20 w-80 h-80 bg-primary/20 rounded-full blur-[100px] opacity-40 group-hover:opacity-60 transition duration-1000"></div>
                    
                    <div class="relative z-10 space-y-6">
                        <div class="space-y-1">
                            <p class="text-[11px] font-black text-primary uppercase tracking-[0.3em] mb-2 font-display">Paket Saat Ini</p>
                            <h3 class="text-3xl font-display font-black text-white">Starter Plan (Free)</h3>
                        </div>
                        
                        <div class="flex items-end gap-3 text-white">
                            <span class="text-6xl font-display font-black">0</span>
                            <span class="text-lg text-white/40 mb-3 font-bold uppercase tracking-widest text-[11px]">/ 2 slot lowongan terpakai</span>
                        </div>

                        <div class="space-y-3">
                            <div v-for="benefit in ['Akses direktori dasar', '2 Posting lowongan', 'Statistik sederhana']" :key="benefit" class="flex items-center gap-2 text-sm font-bold text-white/70">
                                <CheckCircle2 class="w-4 h-4 text-primary" />
                                {{ benefit }}
                            </div>
                        </div>
                    </div>

                    <Button variant="secondary" class="w-full rounded-2xl font-bold h-14 bg-white text-foreground hover:bg-white/90 shadow-xl group/btn">
                        <Zap class="w-5 h-5 mr-3 fill-primary text-primary" />
                        Upgrade ke Pro Sekarang
                    </Button>
                </CardContent>
            </Card>
        </div>

        <section class="animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-500">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                <h2 class="text-2xl font-display font-bold text-foreground tracking-tight">Lowongan Kerja Aktif</h2>
            </div>
            <div class="bg-card border-2 border-border/50 rounded-[32px] p-20 shadow-xl shadow-primary/5">
                <EmptyState 
                    title="Belum ada lowongan aktif" 
                    description="Buat lowongan pertama Anda untuk mulai menerima lamaran dari arsitek berbakat di Indonesia." 
                    actionText="Post Lowongan Pertama"
                    actionUrl="#"
                />
            </div>
        </section>
    </AuthenticatedLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
</style>
