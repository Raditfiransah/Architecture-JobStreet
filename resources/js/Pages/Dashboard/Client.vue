<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { 
  Plus, 
  Users, 
  CheckCircle, 
  Briefcase,
  ChevronRight,
  Search
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent } from "@/Components/UI/ui/card";

const props = defineProps({
  user: Object,
  stats: Object,
});
</script>

<template>
  <ProfileLayout>
    <Head :title="'Dashboard Client - ' + user.name" />

    <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
      <div>
        <h1 class="text-3xl font-display font-bold text-foreground tracking-tight mb-2">
          Halo, {{ user.name }}.
        </h1>
        <p class="text-sm text-muted-foreground max-w-2xl leading-relaxed">
          Kelola proyek Anda dengan mudah dan temukan arsitek terbaik untuk mewujudkan desain impian Anda.
        </p>
      </div>
      <div class="flex items-center gap-3">
        <Button asChild class="rounded-xl font-bold px-6 shadow-md hover:shadow-lg transition-all duration-300">
          <Link :href="route('client.proyek.create')">
            <Plus class="w-4 h-4 mr-2" />
            Posting Proyek Baru
          </Link>
        </Button>
      </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
      <StatCard title="Proyek Aktif" :value="stats?.active_projects || '0'" color="blue">
         <template #icon><Briefcase class="w-5 h-5" /></template>
      </StatCard>
      <StatCard title="Proposal Masuk" :value="stats?.incoming_proposals || '0'" color="green">
        <template #icon><Users class="w-5 h-5" /></template>
      </StatCard>
      <StatCard title="Proyek Selesai" :value="stats?.completed_projects || '0'" color="purple">
        <template #icon><CheckCircle class="w-5 h-5" /></template>
      </StatCard>
    </div>

    <Card class="border-none shadow-sm rounded-2xl overflow-hidden mb-10 bg-gradient-to-br from-white to-slate-50/50">
      <CardContent class="p-0">
        <div class="flex flex-col md:flex-row items-stretch">
          <div class="flex-1 p-8 space-y-6 relative">
            <div class="space-y-4 relative z-10">
              <h3 class="text-xl font-display font-bold text-foreground">Cari Arsitek Terbaik</h3>
              <p class="text-sm text-[#64748B] leading-relaxed max-w-lg">
                Gunakan direktori profesional kami untuk mencari arsitek berdasarkan spesialisasi, gaya desain, dan portofolio terbaik mereka.
              </p>
              <Button variant="outline" asChild class="rounded-xl font-bold border-border/60 hover:border-primary/30 hover:bg-white shadow-sm transition-all duration-300">
                <Link :href="route('arsitek.index')" class="flex items-center gap-2">
                  Buka Direktori Arsitek
                  <ChevronRight class="w-4 h-4" />
                </Link>
              </Button>
            </div>
            <!-- Decorative element -->
            <div class="absolute top-0 right-0 -m-8 w-40 h-40 bg-primary/5 rounded-full blur-3xl"></div>
          </div>
          <div class="w-full md:w-[280px] bg-slate-100/50 p-12 flex items-center justify-center border-t md:border-t-0 md:border-l border-white">
            <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center text-primary group-hover:scale-110 transition-transform duration-500">
              <Search class="w-7 h-7" />
            </div>
          </div>
        </div>
      </CardContent>
    </Card>

    <section>
      <div class="flex items-center gap-3 mb-6">
        <div class="w-1.5 h-6 bg-primary rounded-full"></div>
        <h2 class="text-2xl font-display font-bold text-foreground tracking-tight">Proyek Anda</h2>
      </div>
      <div class="bg-white border border-slate-100 rounded-2xl p-12 shadow-sm text-center">
        <EmptyState 
          title="Satu langkah lagi!" 
          description="Buat proyek pertama Anda untuk mulai menerima proposal profesional dari mitra sistem kami." 
          actionText="Posting Proyek Pertama"
          :actionUrl="route('client.proyek.create')"
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
