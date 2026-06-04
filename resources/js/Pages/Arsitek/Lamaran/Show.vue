<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import {
  Briefcase,
  MapPin,
  Calendar,
  ArrowLeft,
  Clock,
  FileText,
  CheckCircle,
  XCircle,
  AlertCircle,
  Building2,
  DollarSign
} from "lucide-vue-next";

const props = defineProps({
  lamaran: {
    type: Object,
    required: true
  }
});

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

const formatCurrency = (value) => {
  if (!value) return 'Tidak disebutkan';
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

const getStatusColor = (status) => {
  switch (status?.toLowerCase()) {
    case 'accepted': return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    case 'rejected': return 'bg-rose-50 text-rose-700 border-rose-200';
    case 'interview': return 'bg-blue-50 text-blue-700 border-blue-200';
    case 'shortlisted': return 'bg-purple-50 text-purple-700 border-purple-200';
    case 'reviewing': return 'bg-sky-50 text-sky-700 border-sky-200';
    default: return 'bg-amber-50 text-amber-700 border-amber-200';
  }
};

const getStatusLabel = (status) => {
  const labels = {
    pending: 'Menunggu',
    reviewing: 'Sedang Ditinjau',
    shortlisted: 'Masuk Shortlist',
    interview: 'Dipanggil Interview',
    accepted: 'Diterima',
    rejected: 'Ditolak'
  };
  return labels[status?.toLowerCase()] || status;
};

const getStatusMessage = (status) => {
  switch (status?.toLowerCase()) {
    case 'accepted': return 'Selamat! Lamaran Anda telah diterima. Segera hubungi perusahaan untuk langkah selanjutnya.';
    case 'rejected': return 'Mohon maaf, perusahaan memutuskan untuk memilih kandidat lain. Jangan menyerah, terus lamar lowongan lainnya!';
    case 'interview': return 'Anda dipanggil untuk interview! Persiapkan diri Anda dengan baik dan hubungi perusahaan untuk konfirmasi jadwal.';
    case 'shortlisted': return 'Profil Anda masuk dalam daftar kandidat terpilih. Perusahaan sedang mengevaluasi lebih lanjut.';
    case 'reviewing': return 'Lamaran Anda sedang dalam proses peninjauan oleh tim rekrutmen perusahaan.';
    default: return 'Lamaran Anda telah terkirim dan sedang menunggu ditinjau oleh perusahaan.';
  }
};
</script>

<template>
  <ProfileLayout>
    <Head title="Detail Lamaran" />

    <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">

      <!-- Back Navigation -->
      <Link :href="route('arsitek.lamaran.index')" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
        <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
        Kembali ke Daftar Lamaran
      </Link>

      <!-- Status Alert -->
      <div class="border rounded-2xl p-5 flex gap-4 items-start" :class="getStatusColor(lamaran.status)">
        <div class="shrink-0 mt-0.5">
          <CheckCircle v-if="lamaran.status === 'accepted'" class="w-6 h-6" />
          <XCircle v-else-if="lamaran.status === 'rejected'" class="w-6 h-6" />
          <AlertCircle v-else class="w-6 h-6" />
        </div>
        <div class="space-y-1 flex-1">
          <h3 class="font-bold text-sm uppercase tracking-wider">
            Status Lamaran: {{ getStatusLabel(lamaran.status) }}
          </h3>
          <p class="text-xs font-semibold opacity-90 leading-relaxed">
            {{ getStatusMessage(lamaran.status) }}
          </p>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left: Application Detail -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Application Info Card -->
          <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-4 border-b border-slate-100">
              <CardTitle class="text-xl font-display font-bold">Rincian Lamaran</CardTitle>
              <CardDescription>Informasi lamaran yang Anda kirimkan.</CardDescription>
            </CardHeader>
            <CardContent class="p-6 md:p-8 space-y-6">

              <!-- Dates -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-5 border border-slate-100/50 rounded-2xl">
                <div>
                  <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Tanggal Melamar</span>
                  <span class="text-sm font-bold text-slate-700 mt-1 block">
                    {{ formatDate(lamaran.applied_at || lamaran.created_at) }}
                  </span>
                </div>
                <div class="sm:border-l sm:border-slate-200 sm:pl-4">
                  <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Status Terkini</span>
                  <span class="mt-1 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border inline-block" :class="getStatusColor(lamaran.status)">
                    {{ getStatusLabel(lamaran.status) }}
                  </span>
                </div>
              </div>

              <!-- Notes / Cover Letter -->
              <div v-if="lamaran.notes" class="space-y-2">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider">Catatan / Cover Letter:</h4>
                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50/30 p-5 border border-slate-50 rounded-2xl">
                  {{ lamaran.notes }}
                </p>
              </div>

              <!-- CV Attachment -->
              <div v-if="lamaran.cv_path" class="flex items-center gap-3 p-3.5 border border-slate-100 rounded-2xl bg-slate-50/50 max-w-md">
                <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                  <FileText class="w-5 h-5" />
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-bold text-slate-700 truncate leading-tight">CV / Resume</p>
                  <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Dokumen yang Anda lampirkan</p>
                </div>
                <a
                  :href="'/storage/' + lamaran.cv_path"
                  target="_blank"
                  class="px-4 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 font-bold text-xs shadow-sm text-slate-600 transition-all shrink-0"
                >
                  Unduh
                </a>
              </div>

              <!-- Portfolio Attachment -->
              <div v-if="lamaran.portfolio_path" class="flex items-center gap-3 p-3.5 border border-slate-100 rounded-2xl bg-slate-50/50 max-w-md">
                <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                  <Briefcase class="w-5 h-5" />
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-bold text-slate-700 truncate leading-tight">Portofolio</p>
                  <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Dokumen portofolio yang Anda lampirkan</p>
                </div>
                <a
                  :href="'/storage/' + lamaran.portfolio_path"
                  target="_blank"
                  class="px-4 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 font-bold text-xs shadow-sm text-slate-600 transition-all shrink-0"
                >
                  Unduh
                </a>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Right: Job Listing Summary -->
        <div class="space-y-6">
          <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-3 border-b border-slate-100">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Lowongan yang Dilamar</span>
            </CardHeader>
            <CardContent class="p-5 space-y-4">
              <div class="space-y-1">
                <span class="text-[9px] font-black text-primary uppercase tracking-wider bg-primary/5 px-2.5 py-0.5 rounded-md inline-block leading-none">
                  {{ lamaran.lowongan?.tipe || 'Full Time' }}
                </span>
                <h3 class="font-extrabold text-slate-800 text-sm leading-snug mt-2">
                  {{ lamaran.lowongan?.posisi || 'Posisi tidak tersedia' }}
                </h3>
              </div>

              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100">
                  <Building2 class="w-4 h-4 text-slate-400" />
                </div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Perusahaan</p>
                  <p class="text-xs font-bold text-slate-700">{{ lamaran.lowongan?.perusahaan || '-' }}</p>
                </div>
              </div>

              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100">
                  <MapPin class="w-4 h-4 text-slate-400" />
                </div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Kota</p>
                  <p class="text-xs font-bold text-slate-700">{{ lamaran.lowongan?.kota || '-' }}</p>
                </div>
              </div>

              <div v-if="lamaran.lowongan?.gaji" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100">
                  <DollarSign class="w-4 h-4 text-slate-400" />
                </div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Gaji</p>
                  <p class="text-xs font-bold text-primary">{{ lamaran.lowongan.gaji }}</p>
                </div>
              </div>

              <div v-if="lamaran.lowongan?.deadline" class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100">
                  <Calendar class="w-4 h-4 text-slate-400" />
                </div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Deadline</p>
                  <p class="text-xs font-bold text-slate-700">{{ formatDate(lamaran.lowongan.deadline) }}</p>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>
      </div>
    </div>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
