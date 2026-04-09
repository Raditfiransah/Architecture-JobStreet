<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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
});
</script>

<template>
  <AuthenticatedLayout>
    <Head :title="'Dashboard Client - ' + user.name" />

    <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6">
      <div>
        <h1 class="text-3xl font-display font-bold text-foreground tracking-tight mb-3">
          Halo, {{ user.name }}.
        </h1>
        <p class="text-sm text-muted-foreground max-w-2xl leading-relaxed">
          Kelola proyek Anda dengan mudah dan temukan arsitek terbaik untuk mewujudkan desain impian Anda.
        </p>
      </div>
      <div class="flex items-center gap-3">
        <Button asChild class="rounded-lg font-medium">
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

    <Card class="border border-border rounded-xl overflow-hidden mb-12">
      <CardContent class="p-0">
        <div class="flex flex-col md:flex-row items-stretch">
          <div class="flex-1 p-6 md:p-8 space-y-6 relative bg-muted/20">
            <div class="space-y-4 relative z-10">
              <h3 class="text-xl font-display font-bold text-foreground">Cari Arsitek Terbaik</h3>
              <p class="text-sm text-muted-foreground leading-relaxed max-w-lg">
                Gunakan direktori profesional kami untuk mencari arsitek berdasarkan spesialisasi, gaya desain, dan portofolio terbaik mereka.
              </p>
              <Button variant="outline" asChild class="rounded-lg font-medium border-border hover:border-primary/30">
                <Link href="#" class="flex items-center gap-2">
                  Buka Direktori Arsitek
                  <ChevronRight class="w-4 h-4" />
                </Link>
              </Button>
            </div>
          </div>
          <div class="w-full md:w-[300px] bg-muted/30 p-12 flex items-center justify-center border-t md:border-t-0 md:border-l border-border">
            <div class="w-20 h-20 bg-card rounded-xl shadow flex items-center justify-center text-primary">
              <Search class="w-8 h-8" />
            </div>
          </div>
        </div>
      </CardContent>
    </Card>

    <section>
      <div class="flex items-center gap-3 mb-8">
        <div class="w-1.5 h-8 bg-primary rounded-full"></div>
        <h2 class="text-2xl font-display font-bold text-foreground tracking-tight">Proyek Anda</h2>
      </div>
      <div class="bg-card border border-border rounded-xl p-10">
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
  font-family: 'Outfit', sans-serif;
}
</style>
