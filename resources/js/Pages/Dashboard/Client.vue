<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { 
    Plus, 
    Users, 
    CheckCircle, 
    LayoutDashboard, 
    Briefcase,
    Search,
    ChevronRight,
    Sparkles
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent } from "@/Components/UI/ui/card";

const props = defineProps({
    user: Object,
});
</script>

<template>
    <AuthenticatedLayout>
        <Head :title="'Dashboard Client - ' + user.name" />

        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <div>
                <h1 class="text-4xl font-display font-black text-foreground tracking-tight leading-none mb-3">
                    Halo, {{ user.name }}.
                </h1>
                <p class="text-[15px] font-medium text-muted-foreground max-w-2xl leading-relaxed">
                    Kelola proyek Anda dengan mudah dan temukan arsitek terbaik untuk mewujudkan desain impian Anda dalam hitungan hari.
                </p>
            </div>
            <div class="flex items-center gap-3">
                <Button asChild class="rounded-xl font-bold h-11 shadow-xl shadow-primary/20 px-8">
                    <Link href="#">
                        <Plus class="w-4 h-4 mr-2" />
                        Posting Proyek Baru
                    </Link>
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
            <StatCard title="Proyek Aktif" value="0" color="blue">
                <template #icon><Briefcase class="w-5 h-5" /></template>
            </StatCard>
            <StatCard title="Proposal Masuk" value="0" color="green">
                <template #icon><Users class="w-5 h-5" /></template>
            </StatCard>
            <StatCard title="Proyek Selesai" value="0" color="purple">
                <template #icon><CheckCircle class="w-5 h-5" /></template>
            </StatCard>
        </div>

        <!-- Premium CTA Card -->
        <Card class="rounded-[32px] border-2 border-border/50 shadow-2xl shadow-primary/5 overflow-hidden mb-12 group">
            <CardContent class="p-0">
                <div class="flex flex-col md:flex-row items-stretch">
                    <div class="flex-1 p-8 md:p-12 space-y-6 relative overflow-hidden">
                        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors"></div>
                        <div class="relative z-10 space-y-4">
                            <h3 class="text-2xl font-display font-black text-foreground">Cari Arsitek Terbaik</h3>
                            <p class="text-[16px] font-medium text-muted-foreground leading-relaxed max-w-lg">
                                Gunakan direktori profesional kami untuk mencari arsitek berdasarkan spesialisasi, gaya desain, dan portofolio terbaik mereka.
                            </p>
                            <Button variant="outline" asChild class="rounded-xl font-bold border-border/80 hover:border-primary/30 h-12 px-8">
                                <Link href="#" class="flex items-center gap-2">
                                    Buka Direktori Arsitek
                                    <ChevronRight class="w-4 h-4" />
                                </Link>
                            </Button>
                        </div>
                    </div>
                    <div class="w-full md:w-[300px] bg-muted/30 p-12 flex items-center justify-center border-t md:border-t-0 md:border-l border-border/50">
                        <div class="w-24 h-24 bg-card rounded-3xl shadow-xl flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
                            <Search class="w-10 h-10" />
                        </div>
                    </div>
                </div>
            </CardContent>
        </Card>

        <section class="animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-300">
            <div class="flex items-center gap-3 mb-8">
                <div class="w-1.5 h-8 bg-primary rounded-full"></div>
                <h2 class="text-2xl font-display font-bold text-foreground tracking-tight">Proyek Anda</h2>
            </div>
            <div class="bg-card border-2 border-border/50 rounded-[32px] p-20 shadow-xl shadow-primary/5">
                <EmptyState 
                    title="Satu langkah lagi!" 
                    description="Buat proyek pertama Anda untuk mulai menerima proposal profesional dari mitra sistem kami." 
                    actionText="Posting Proyek Pertama"
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
