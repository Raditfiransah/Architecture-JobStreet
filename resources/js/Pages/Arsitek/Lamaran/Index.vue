<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { Badge } from '@/Components/UI/ui/badge';
import { Button } from '@/Components/UI/ui/button';
import { Card, CardContent } from '@/Components/UI/ui/card';
import {
  ArrowRight,
  Briefcase,
  Building2,
  Calendar,
  Clock,
  MapPin,
} from 'lucide-vue-next';

const props = defineProps({
  lamarans: {
    type: Array,
    default: () => [],
  },
});

const formatDate = (date) => {
  if (!date) return '-';

  return new Date(date).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric',
  });
};

const getStatusLabel = (status) => {
  const labels = {
    pending: 'Menunggu',
    reviewing: 'Ditinjau',
    shortlisted: 'Shortlist',
    interview: 'Interview',
    accepted: 'Diterima',
    rejected: 'Ditolak',
  };

  return labels[status?.toLowerCase()] || status || 'Menunggu';
};

const getStatusClass = (status) => {
  switch (status?.toLowerCase()) {
    case 'accepted':
      return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    case 'rejected':
      return 'bg-rose-50 text-rose-700 border-rose-200';
    case 'interview':
      return 'bg-blue-50 text-blue-700 border-blue-200';
    case 'shortlisted':
      return 'bg-purple-50 text-purple-700 border-purple-200';
    case 'reviewing':
      return 'bg-sky-50 text-sky-700 border-sky-200';
    default:
      return 'bg-amber-50 text-amber-700 border-amber-200';
  }
};
</script>

<template>
  <ProfileLayout>
    <Head title="Aktivitas Lamaran" />

    <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
      <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
          <h1 class="text-2xl font-display font-bold text-foreground tracking-tight">
            Aktivitas Lamaran
          </h1>
          <p class="text-sm text-muted-foreground mt-1">
            Pantau semua lamaran kerja yang sudah Anda kirimkan.
          </p>
        </div>

        <Button asChild class="rounded-xl font-bold">
          <Link :href="route('lowongan.index')">
            <Briefcase class="w-4 h-4 mr-2" />
            Cari Lowongan
          </Link>
        </Button>
      </div>

      <div v-if="props.lamarans.length > 0" class="space-y-4">
        <Card
          v-for="lamaran in props.lamarans"
          :key="lamaran.id"
          class="border-slate-100 shadow-sm rounded-2xl overflow-hidden bg-white hover:shadow-md transition-shadow"
        >
          <CardContent class="p-5">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
              <div class="flex gap-4 min-w-0">
                <div class="w-12 h-12 rounded-xl bg-primary/5 text-primary flex items-center justify-center shrink-0">
                  <Building2 class="w-6 h-6" />
                </div>

                <div class="min-w-0">
                  <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h2 class="font-display font-bold text-lg text-slate-900 leading-tight">
                      {{ lamaran.lowongan?.posisi || 'Lowongan tidak tersedia' }}
                    </h2>
                    <Badge
                      variant="outline"
                      :class="['rounded-full font-bold text-[10px] uppercase tracking-wider', getStatusClass(lamaran.status)]"
                    >
                      {{ getStatusLabel(lamaran.status) }}
                    </Badge>
                  </div>

                  <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs font-semibold text-slate-500">
                    <span class="flex items-center gap-1.5">
                      <Building2 class="w-3.5 h-3.5 text-slate-400" />
                      {{ lamaran.lowongan?.perusahaan || 'Perusahaan' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                      <MapPin class="w-3.5 h-3.5 text-slate-400" />
                      {{ lamaran.lowongan?.kota || '-' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                      <Calendar class="w-3.5 h-3.5 text-slate-400" />
                      {{ formatDate(lamaran.applied_at || lamaran.created_at) }}
                    </span>
                  </div>
                </div>
              </div>

              <div class="flex items-center gap-3 shrink-0">
                <div class="hidden sm:flex items-center gap-1.5 text-xs font-bold text-slate-400">
                  <Clock class="w-3.5 h-3.5" />
                  {{ getStatusLabel(lamaran.status) }}
                </div>
                <Button asChild variant="outline" class="rounded-xl font-bold border-slate-200">
                  <Link :href="route('arsitek.lamaran.show', lamaran.id)">
                    Detail
                    <ArrowRight class="w-4 h-4 ml-2" />
                  </Link>
                </Button>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>

      <div v-else class="bg-white border border-slate-100 rounded-2xl p-12 shadow-sm text-center">
        <EmptyState
          title="Belum ada lamaran"
          description="Lamaran yang Anda kirimkan ke lowongan kerja akan tampil di sini."
          actionText="Cari Lowongan"
          :actionUrl="route('lowongan.index')"
        />
      </div>
    </div>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
