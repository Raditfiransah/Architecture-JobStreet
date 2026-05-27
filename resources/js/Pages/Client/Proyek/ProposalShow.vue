<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import {
  Building2,
  MapPin,
  Calendar,
  ArrowLeft,
  Clock,
  FileText,
  CheckCircle,
  XCircle,
  AlertCircle,
  User,
  DollarSign,
  Sparkles
} from "lucide-vue-next";
import {
  Avatar,
  AvatarFallback,
  AvatarImage
} from "@/Components/UI/ui/avatar";

const props = defineProps({
  project: {
    type: Object,
    required: true
  },
  proposal: {
    type: Object,
    required: true
  }
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

const getProposalStatusColor = (status) => {
  switch (status?.toLowerCase()) {
    case 'diterima': return 'bg-emerald-50 text-emerald-700 border-emerald-200';
    case 'ditolak': return 'bg-rose-50 text-rose-700 border-rose-200';
    default: return 'bg-amber-50 text-amber-700 border-amber-200';
  }
};

const getProjectStatusColor = (status) => {
  switch (status?.toLowerCase()) {
    case 'aktif': return 'bg-blue-50 text-blue-700 border-blue-100';
    case 'ditutup': return 'bg-slate-50 text-slate-700 border-slate-100';
    case 'selesai': return 'bg-emerald-50 text-emerald-700 border-emerald-100';
    default: return 'bg-yellow-50 text-yellow-700 border-yellow-100';
  }
};

const acceptProposal = () => {
  if (confirm("Apakah Anda yakin ingin menerima proposal ini? Menerima proposal ini akan menutup pendaftaran dan secara otomatis menolak proposal lainnya.")) {
    router.post(route('client.proposal.terima', props.proposal.id));
  }
};

const rejectProposal = () => {
  if (confirm("Apakah Anda yakin ingin menolak proposal ini?")) {
    router.post(route('client.proposal.tolak', props.proposal.id));
  }
};
</script>

<template>
  <ProfileLayout>
    <Head :title="'Detail Proposal - ' + proposal.user.name" />

    <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">

      <!-- Back Navigation -->
      <Link :href="route('client.proyek.show', project.id)" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
        <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
        Kembali ke Detail Proyek
      </Link>

      <!-- Proposal Status Alert -->
      <div class="border rounded-2xl p-5 flex gap-4 items-start" :class="getProposalStatusColor(proposal.status)">
        <div class="shrink-0 mt-0.5">
          <CheckCircle v-if="proposal.status === 'diterima'" class="w-6 h-6" />
          <XCircle v-else-if="proposal.status === 'ditolak'" class="w-6 h-6" />
          <AlertCircle v-else class="w-6 h-6" />
        </div>
        <div class="space-y-1 flex-1">
          <h3 class="font-bold text-sm uppercase tracking-wider">
            Status Proposal:
            <span class="capitalize">{{ proposal.status }}</span>
          </h3>
          <p class="text-xs font-semibold opacity-90 leading-relaxed">
            <template v-if="proposal.status === 'diterima'">
              Anda telah menerima proposal dari arsitek ini. Proyek telah ditutup untuk penawaran baru.
            </template>
            <template v-else-if="proposal.status === 'ditolak'">
              Anda telah menolak proposal dari arsitek ini.
            </template>
            <template v-else>
              Proposal ini sedang menunggu keputusan Anda. Tinjau rincian penawaran dan ambil tindakan di bawah.
            </template>
          </p>
        </div>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        <!-- Left: Proposal Detail -->
        <div class="lg:col-span-2 space-y-6">

          <!-- Architect Profile Card -->
          <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-4 border-b border-slate-100">
              <CardTitle class="text-xl font-display font-bold">Profil Arsitek</CardTitle>
              <CardDescription>Informasi arsitek yang mengajukan penawaran ini.</CardDescription>
            </CardHeader>
            <CardContent class="p-6 md:p-8">
              <div class="flex items-start gap-5">
                <Avatar class="h-16 w-16 rounded-2xl border border-slate-100 shadow-sm shrink-0">
                  <AvatarImage :src="proposal.user.avatar_url" />
                  <AvatarFallback class="bg-primary/5 text-primary font-bold text-lg">
                    {{ proposal.user.name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase() }}
                  </AvatarFallback>
                </Avatar>

                <div class="flex-1 min-w-0 space-y-2">
                  <div class="flex items-center gap-2 flex-wrap">
                    <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ proposal.user.name }}</h3>
                    <span
                      v-if="proposal.user.arsitek_profile?.verification_status === 'verified'"
                      class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-[9px] font-black px-2 py-0.5 rounded-full border border-emerald-100 uppercase tracking-wider"
                    >
                      <CheckCircle class="w-2.5 h-2.5" /> Terverifikasi
                    </span>
                  </div>
                  <p class="text-xs text-slate-500 font-semibold">
                    {{ proposal.user.arsitek_profile?.status_pekerjaan || 'Arsitek Profesional' }}
                  </p>
                  <div class="flex flex-wrap gap-x-4 gap-y-1 text-xs font-semibold text-slate-400">
                    <span class="flex items-center gap-1.5">
                      <MapPin class="w-3.5 h-3.5" />
                      {{ proposal.user.location || 'Lokasi belum diatur' }}
                    </span>
                    <span class="flex items-center gap-1.5">
                      <Clock class="w-3.5 h-3.5" />
                      Diajukan {{ new Date(proposal.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </span>
                  </div>
                  <div class="pt-2">
                    <a
                      :href="route('public.arsitek.show', proposal.user.id)"
                      target="_blank"
                      class="text-xs font-bold text-primary hover:underline inline-flex items-center gap-1"
                    >
                      Lihat Profil Publik Arsitek →
                    </a>
                  </div>
                </div>
              </div>
            </CardContent>
          </Card>

          <!-- Bid Detail Card -->
          <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-4 border-b border-slate-100">
              <CardTitle class="text-xl font-display font-bold">Rincian Penawaran</CardTitle>
              <CardDescription>Harga, estimasi waktu, dan konsep yang diajukan arsitek.</CardDescription>
            </CardHeader>
            <CardContent class="p-6 md:p-8 space-y-6">

              <!-- Bid Specs Grid -->
              <div class="grid grid-cols-2 gap-4 bg-slate-50 p-5 border border-slate-100/50 rounded-2xl">
                <div>
                  <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Nilai Penawaran</span>
                  <span class="text-xl font-display font-extrabold text-primary mt-1 block">
                    {{ formatCurrency(proposal.bid_amount) }}
                  </span>
                </div>
                <div class="border-l border-slate-200 pl-4">
                  <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Estimasi Pengerjaan</span>
                  <span class="text-base font-bold text-slate-700 mt-1 block">{{ proposal.estimated_time }} Hari Kerja</span>
                </div>
              </div>

              <!-- Pitch / Cover Letter -->
              <div class="space-y-2">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                  <Sparkles class="w-3.5 h-3.5 text-yellow-500 shrink-0" />
                  Konsep & Penjelasan Penawaran
                </h4>
                <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50/30 p-5 border border-slate-50 rounded-2xl">
                  {{ proposal.description }}
                </p>
              </div>

              <!-- Attachment -->
              <div v-if="proposal.attachment_path" class="flex items-center gap-3 p-3.5 border border-slate-100 rounded-2xl bg-slate-50/50 max-w-md">
                <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                  <FileText class="w-5 h-5" />
                </div>
                <div class="min-w-0 flex-1">
                  <p class="text-xs font-bold text-slate-700 truncate leading-tight">Dokumen Konsep Lampiran</p>
                  <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Berkas pendukung dari arsitek</p>
                </div>
                <a
                  :href="'/storage/' + proposal.attachment_path"
                  target="_blank"
                  class="px-4 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 font-bold text-xs shadow-sm text-slate-600 transition-all shrink-0"
                >
                  Unduh
                </a>
              </div>
            </CardContent>
          </Card>

          <!-- Action Buttons (only if project is active and proposal is pending) -->
          <div v-if="project.status === 'aktif' && proposal.status === 'pending'" class="flex flex-col sm:flex-row gap-3">
            <Button
              variant="outline"
              class="flex-1 h-12 rounded-2xl font-bold border-rose-200 text-rose-600 hover:bg-rose-50 hover:border-rose-300 transition-all"
              @click="rejectProposal"
            >
              <XCircle class="w-4 h-4 mr-2" />
              Tolak Proposal Ini
            </Button>
            <Button
              class="flex-1 h-12 rounded-2xl font-bold bg-emerald-600 hover:bg-emerald-700 text-white border-none shadow-md shadow-emerald-600/20 active:scale-[0.98] transition-all"
              @click="acceptProposal"
            >
              <CheckCircle class="w-4 h-4 mr-2" />
              Terima & Pilih Arsitek Ini
            </Button>
          </div>
        </div>

        <!-- Right: Project Summary -->
        <div class="space-y-6">
          <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-3 border-b border-slate-100">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Proyek Terkait</span>
            </CardHeader>
            <CardContent class="p-5 space-y-4">
              <div class="space-y-2">
                <span class="text-[9px] font-black text-primary uppercase tracking-wider bg-primary/5 px-2.5 py-0.5 rounded-md inline-block leading-none">
                  {{ project.category }}
                </span>
                <h3 class="font-extrabold text-slate-800 text-sm leading-snug mt-2">{{ project.title }}</h3>
                <span class="px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border inline-block" :class="getProjectStatusColor(project.status)">
                  {{ project.status }}
                </span>
              </div>

              <div class="flex items-center gap-3 pt-1">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100">
                  <MapPin class="w-4 h-4 text-slate-400" />
                </div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Lokasi</p>
                  <p class="text-xs font-bold text-slate-700">{{ project.location }}</p>
                </div>
              </div>

              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100">
                  <DollarSign class="w-4 h-4 text-slate-400" />
                </div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Anggaran Anda</p>
                  <p class="text-xs font-bold text-primary">{{ formatCurrency(project.budget) }}</p>
                </div>
              </div>

              <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100">
                  <Calendar class="w-4 h-4 text-slate-400" />
                </div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Diposting</p>
                  <p class="text-xs font-bold text-slate-700">
                    {{ new Date(project.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'short', day: 'numeric' }) }}
                  </p>
                </div>
              </div>

              <div class="pt-2 border-t border-slate-100">
                <Link
                  :href="route('client.proyek.show', project.id)"
                  class="text-xs font-bold text-primary hover:underline inline-flex items-center gap-1"
                >
                  Lihat Semua Proposal Proyek →
                </Link>
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
