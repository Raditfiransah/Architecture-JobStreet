<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { 
    Mail, 
    TrendingUp, 
    Eye, 
    CheckCircle2, 
    PenTool, 
    Search,
    ChevronRight,
    Sparkles
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";

const props = defineProps({
    user: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Dashboard ' + user.name" />

        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <div>
                <h1 class="text-4xl font-display font-black text-foreground tracking-tight leading-none mb-3">
                    Halo, {{ user.name }}.
                </h1>
                <p class="text-[15px] font-medium text-muted-foreground max-w-2xl leading-relaxed">
                    Lengkapi profil profesionalmu untuk menarik perhatian biro arsitektur terbaik dan temukan proyek impianmu.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Button variant="outline" asChild class="rounded-xl font-bold border-border/80 hover:border-primary/30 h-11">
                    <Link href="#">Pengaturan Akun</Link>
                </Button>
                <Button asChild class="rounded-xl font-bold h-11 shadow-xl shadow-primary/20 px-8">
                    <Link href="#">
                        <Search class="w-4 h-4 mr-2" />
                        Cari Lowongan
                    </Link>
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
            <StatCard title="Lamaran Dikirim" value="0" color="blue">
                <template #icon><Mail class="w-5 h-5" /></template>
            </StatCard>
            <StatCard title="Proposal Aktif" value="0" color="green">
                <template #icon><TrendingUp class="w-5 h-5" /></template>
            </StatCard>
            <StatCard title="Profil Dilihat" value="0" color="purple">
                <template #icon><Eye class="w-5 h-5" /></template>
            </StatCard>
        </div>

        <!-- Premium CTA Card -->
        <div class="bg-primary/5 border-2 border-primary/20 rounded-[32px] p-8 md:p-10 mb-12 flex flex-col md:flex-row items-center justify-between gap-10 overflow-hidden relative shadow-2xl shadow-primary/5 group">
            <!-- Decoration -->
            <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-primary/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
            
            <div class="relative z-10 flex-1 space-y-6">
                <div class="space-y-2">
                    <Badge variant="secondary" class="bg-primary/20 text-primary hover:bg-primary/20 rounded-full px-3 py-1 font-bold text-[10px] uppercase tracking-wider flex items-center w-fit gap-2">
                        <Sparkles class="w-3 h-3" />
                        Wujudkan Karir Profesional
                    </Badge>
                    <h3 class="text-2xl font-display font-black text-foreground">Profilmu belum lengkap, Arsitek!</h3>
                </div>
                <p class="text-[16px] font-medium text-muted-foreground leading-relaxed max-w-lg">
                    Lengkapi profil dan portofolio digital Anda untuk meningkatkan peluang mendapatkan proyek impian hingga 80% lebih besar.
                </p>
                <Button size="lg" class="rounded-2xl font-bold h-14 px-10 shadow-xl shadow-primary/20 group/btn">
                    Lengkapi Sekarang
                    <ChevronRight class="ml-2 w-5 h-5 group-hover/btn:translate-x-1 transition-transform" />
                </Button>
            </div>
            
            <div class="relative z-10 hidden lg:flex items-center justify-center pr-10">
                <div class="relative group">
                    <div class="absolute inset-0 bg-primary/20 rounded-[40px] blur-2xl group-hover:bg-primary/30 transition-colors"></div>
                    <div class="relative w-48 h-48 rounded-[40px] bg-card border-4 border-background shadow-2xl flex items-center justify-center rotate-6 hover:rotate-0 transition-transform duration-500 overflow-hidden">
                        <div class="w-full h-full bg-gradient-to-br from-primary/20 to-primary/5 flex items-center justify-center font-display font-black text-6xl text-primary/40 uppercase">
                            {{ user.name.charAt(0) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <section class="animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-300">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                <h2 class="text-2xl font-display font-bold text-foreground tracking-tight">Aktivitas Terkini</h2>
            </div>
            <div class="bg-card border-2 border-border/50 rounded-[32px] p-20 shadow-xl shadow-primary/5">
                <EmptyState 
                    title="Belum ada aktivitas" 
                    description="Aktivitas Anda akan muncul di sini setelah Anda mulai melamar proyek atau melengkapi profil profesional Anda." 
                    actionText="Cari Proyek Pertama"
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
