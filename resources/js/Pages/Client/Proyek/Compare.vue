<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent } from "@/Components/UI/ui/card";
import { 
  Building2, 
  ArrowLeft,
  CheckCircle,
  Clock,
  DollarSign,
  FileText,
  User,
  MapPin,
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
  proposals: {
    type: Array,
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

const acceptProposal = (propId) => {
  if (confirm("Apakah Anda yakin ingin menerima proposal dari arsitek ini? Menerima proposal ini akan menutup pendaftaran dan secara otomatis menolak proposal lainnya.")) {
    router.post(route('client.proposal.terima', propId));
  }
};
</script>

<template>
  <ProfileLayout>
    <Head :title="'Bandingkan Penawaran - ' + project.title" />

    <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
      <!-- Navigation -->
      <div class="flex items-center justify-between">
        <Link :href="route('client.proyek.show', project.id)" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
          <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
          Kembali ke Detail Proyek
        </Link>
      </div>

      <!-- Header title -->
      <div class="space-y-1">
        <h1 class="text-2xl md:text-3xl font-display font-bold">Bandingkan Penawaran Proposal</h1>
        <p class="text-xs text-slate-500">Bandingkan rincian biaya, estimasi waktu, dan konsep arsitektur secara langsung sebelum memilih arsitek.</p>
      </div>

      <!-- Comparative Layout Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch">
        <Card 
          v-for="proposal in proposals" 
          :key="proposal.id" 
          class="border-slate-100 hover:border-primary/30 transition-all duration-300 shadow-sm hover:shadow-md rounded-3xl overflow-hidden bg-white flex flex-col justify-between"
        >
          <!-- Card Body -->
          <div class="p-6 md:p-8 space-y-6 flex-1">
            <!-- Architect Header Profile -->
            <div class="text-center space-y-3 pb-5 border-b border-slate-100">
              <Avatar class="h-16 w-16 mx-auto rounded-2xl border border-slate-100 shadow-sm">
                <AvatarImage :src="proposal.user.avatar_url" />
                <AvatarFallback class="bg-primary/5 text-primary font-bold text-lg">
                  {{ proposal.user.name.split(' ').map(n=>n[0]).join('').substring(0,2).toUpperCase() }}
                </AvatarFallback>
              </Avatar>
              
              <div class="space-y-1">
                <div class="flex items-center justify-center gap-1.5 flex-wrap">
                  <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ proposal.user.name }}</h3>
                </div>
                <span v-if="proposal.user.arsitek_profile?.verification_status === 'verified'" class="inline-flex bg-emerald-50 text-emerald-700 text-[9px] font-black px-2 py-0.5 rounded-full border border-emerald-100 uppercase tracking-wider items-center gap-1">
                  <CheckCircle class="w-2.5 h-2.5" /> Terverifikasi
                </span>
                <p class="text-xs text-slate-400 font-semibold">
                  {{ proposal.user.arsitek_profile?.status_pekerjaan || 'Arsitek Profesional' }}
                </p>
                <p class="text-xs text-slate-500 font-medium flex items-center justify-center gap-1">
                  <MapPin class="w-3.5 h-3.5 text-slate-400 shrink-0" /> {{ proposal.user.location || 'Lokasi belum diatur' }}
                </p>
              </div>
            </div>

            <!-- Specs Grid (Bid Amount & Estimated Time) -->
            <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 border border-slate-50 rounded-2xl text-center">
              <div>
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none block">Nilai Bid</span>
                <span class="text-base font-display font-extrabold text-primary mt-1 block">{{ formatCurrency(proposal.bid_amount) }}</span>
              </div>
              <div class="border-l border-slate-200">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none block">Durasi</span>
                <span class="text-sm font-bold text-slate-700 mt-1 block">{{ proposal.estimated_time }} Hari Kerja</span>
              </div>
            </div>

            <!-- Cover Letter / Pitch Penawaran -->
            <div class="space-y-2">
              <h4 class="text-xs font-black text-slate-400 uppercase tracking-wider flex items-center gap-1">
                <Sparkles class="w-3.5 h-3.5 text-yellow-500 shrink-0" />
                Konsep & Penawaran
              </h4>
              <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50/20 p-4 border border-slate-50 rounded-2xl h-48 overflow-y-auto">
                {{ proposal.description }}
              </p>
            </div>

            <!-- Attachment Concepts -->
            <div v-if="proposal.attachment_path" class="flex items-center gap-2 p-2 border border-slate-100 rounded-xl bg-slate-50/50">
              <FileText class="w-4 h-4 text-slate-400 shrink-0" />
              <span class="text-[11px] font-bold text-slate-500 truncate flex-1">Konsep Lampiran</span>
              <a :href="'/storage/' + proposal.attachment_path" target="_blank" class="text-[11px] font-bold text-primary hover:underline shrink-0">Unduh</a>
            </div>
          </div>

          <!-- Card Footer (Accept Action) -->
          <div class="p-6 md:p-8 bg-slate-50/50 border-t border-slate-100">
            <Button class="w-full h-12 rounded-xl font-bold bg-emerald-600 hover:bg-emerald-700 text-white border-none shadow-md shadow-emerald-600/10 active:scale-[0.98] transition-all" @click="acceptProposal(proposal.id)">
              Pilih Arsitek Ini
            </Button>
          </div>
        </Card>
      </div>
    </div>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
