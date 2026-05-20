<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { 
  Mail, 
  Activity, 
  Eye, 
  ChevronRight,
  Zap,
  Search
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";

const props = defineProps({
  user: Object,
  stats: Object,
});
</script>

<template>
  <ProfileLayout>
    <Head :title="'Dashboard ' + user.name" />

    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
      <div>
        <h1 class="text-3xl font-display font-bold text-foreground tracking-tight mb-3">
          Halo, {{ user.name }}.
        </h1>
        <p class="text-sm text-muted-foreground max-w-2xl leading-relaxed">
          Lengkapi profil profesionalmu untuk menarik perhatian biro arsitektur terbaik dan temukan proyek impianmu.
        </p>
      </div>
      <div class="flex items-center gap-3">
        <Button variant="outline" asChild class="rounded-lg font-medium border-border hover:border-primary/30">
          <Link href="#">Pengaturan Akun</Link>
        </Button>
        <Button asChild class="rounded-lg font-medium">
          <Link href="#">
             <Search class="w-4 h-4 mr-2" />
            Cari Lowongan
          </Link>
        </Button>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">
      <StatCard title="Lamaran Dikirim" :value="stats?.lamaran_dikirim || '0'" color="blue">
        <template #icon><Mail class="w-5 h-5" /></template>
      </StatCard>
      <StatCard title="Proposal Aktif" :value="stats?.proposal_aktif || '0'" color="green">
        <template #icon><Activity class="w-5 h-5" /></template>
      </StatCard>
      <StatCard title="Profil Dilihat" :value="stats?.profil_dilihat || '0'" color="purple">
        <template #icon><Eye class="w-5 h-5" /></template>
      </StatCard>
    </div>

    <section>
      <div class="flex items-center gap-3 mb-8">
        <div class="w-1.5 h-8 bg-primary rounded-full"></div>
        <h2 class="text-2xl font-display font-bold text-foreground tracking-tight">Aktivitas Terkini</h2>
      </div>
      <div class="bg-card border border-border rounded-xl p-10">
        <EmptyState 
          title="Belum ada aktivitas" 
          description="Aktivitas Anda akan muncul di sini setelah Anda mulai melamar proyek atau melengkapi profil profesional Anda." 
          actionText="Cari Proyek Pertama"
          actionUrl="#"
        />
      </div>
    </section>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
