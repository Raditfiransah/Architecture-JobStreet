<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { 
  Building, 
  Users, 
  Clock, 
  PlusCircle, 
  Zap,
  ChevronRight,
  CheckCircle
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

    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
      <div>
        <h1 class="text-3xl font-display font-bold text-foreground tracking-tight mb-3">
          Selamat datang, {{ companyName }}.
        </h1>
        <p class="text-sm text-muted-foreground max-w-2xl leading-relaxed">
          Kelola lowongan kerja perusahaan dan temukan talenta arsitek terbaik untuk memperkuat tim Anda.
        </p>
      </div>
      <div class="flex items-center gap-3">
        <Button asChild class="rounded-lg font-medium">
          <Link href="#">
             <PlusCircle class="w-4 h-4 mr-2" />
            Post Lowongan Baru
          </Link>
        </Button>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
      <StatCard title="Lowongan Aktif" value="0" color="blue">
        <template #icon><Building class="w-5 h-5" /></template>
      </StatCard>
      <StatCard title="Total Pelamar" value="0" color="green">
         <template #icon><Users class="w-5 h-5" /></template>
      </StatCard>
      <StatCard title="Segera Expire" value="0" color="amber">
        <template #icon><Clock class="w-5 h-5" /></template>
      </StatCard>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
      <Card class="border border-border rounded-xl flex flex-col overflow-hidden">
        <CardContent class="p-6 md:p-8 flex flex-col justify-between h-full space-y-6 relative bg-muted/20">
          <div class="space-y-4 relative z-10">
            <div class="w-12 h-12 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
               <PlusCircle class="w-6 h-6" />
            </div>
            <h3 class="text-xl font-display font-bold text-foreground">Post Lowongan Kerja</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">
              Mulai cari arsitek terbaik untuk bergabung dengan tim desain atau proyek konstruksi Anda melalui jaringan kami.
            </p>
          </div>
          <Button variant="outline" asChild class="w-fit rounded-lg font-medium border-border hover:border-primary/30">
            <Link href="#">Buat Lowongan Sekarang</Link>
          </Button>
        </CardContent>
      </Card>

      <Card class="bg-foreground text-background rounded-xl flex flex-col overflow-hidden">
        <CardContent class="p-6 md:p-8 flex flex-col justify-between h-full space-y-6 relative">
          <div class="relative z-10 space-y-6">
            <div class="space-y-1">
              <p class="text-[11px] font-black text-primary uppercase tracking-wider mb-2 font-display">Paket Saat Ini</p>
              <h3 class="text-2xl font-display font-bold text-white">Starter Plan (Free)</h3>
            </div>
            
            <div class="flex items-end gap-3 text-white">
              <span class="text-4xl font-display font-bold">0</span>
              <span class="text-sm text-white/40 mb-1 font-bold uppercase tracking-widest text-[11px]">/ 2 slot lowongan terpakai</span>
            </div>

            <div class="space-y-3">
              <div v-for="benefit in ['Akses direktori dasar', '2 Posting lowongan', 'Statistik sederhana']" :key="benefit" class="flex items-center gap-2 text-sm font-bold text-white/70">
                 <CheckCircle class="w-4 h-4 text-primary" />
                {{ benefit }}
              </div>
            </div>
          </div>

          <Button variant="secondary" class="w-full rounded-lg font-medium h-12 bg-white text-foreground hover:bg-white/90">
            <Zap class="w-5 h-5 mr-3 fill-primary text-primary" />
            Upgrade ke Pro Sekarang
          </Button>
        </CardContent>
      </Card>
    </div>

    <section>
      <div class="flex items-center gap-3 mb-8">
        <div class="w-1.5 h-8 bg-primary rounded-full"></div>
        <h2 class="text-2xl font-display font-bold text-foreground tracking-tight">Lowongan Kerja Aktif</h2>
      </div>
      <div class="bg-card border border-border rounded-xl p-10">
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
  font-family: 'Outfit', sans-serif;
}
</style>
