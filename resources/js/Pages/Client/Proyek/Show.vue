<script setup>
import { ref, computed } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent } from "@/Components/UI/ui/card";
import { 
  Building2, 
  MapPin, 
  Calendar, 
  FileText, 
  ArrowLeft,
  Clock,
  ArrowUpRight,
  TrendingUp,
  DollarSign,
  User,
  CheckCircle,
  XCircle,
  Check,
  Slash,
  Eye,
  Activity
} from "lucide-vue-next";
import {
  Avatar,
  AvatarFallback,
  AvatarImage
} from "@/Components/UI/ui/avatar";
import EmptyState from '@/Components/EmptyState.vue';

const props = defineProps({
  project: {
    type: Object,
    required: true
  }
});

const selectedBids = ref([]);
const projectProposals = computed(() => props.project.proposals || []);

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

const getStatusColor = (status) => {
  switch (status?.toLowerCase()) {
    case 'aktif': return 'bg-emerald-50 text-emerald-700 border-emerald-100';
    case 'ditutup': return 'bg-slate-50 text-slate-700 border-slate-100';
    case 'selesai': return 'bg-blue-50 text-blue-700 border-blue-100';
    default: return 'bg-yellow-50 text-yellow-700 border-yellow-100';
  }
};

const getProposalStatusColor = (status) => {
  switch (status?.toLowerCase()) {
    case 'diterima': return 'bg-emerald-100 text-emerald-800 border-emerald-200';
    case 'ditolak': return 'bg-rose-100 text-rose-800 border-rose-200';
    default: return 'bg-amber-100 text-amber-800 border-amber-200';
  }
};

const architectUser = (proposal) => proposal.user || {};

const architectProfile = (proposal) => architectUser(proposal).arsitek_profile || {};

const architectName = (proposal) => architectUser(proposal).name || 'Arsitek';

const architectInitials = (proposal) => {
  return architectName(proposal).split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
};

const toggleBidSelection = (bidId) => {
  const index = selectedBids.value.indexOf(bidId);
  if (index > -1) {
    selectedBids.value.splice(index, 1);
  } else {
    if (selectedBids.value.length < 3) {
      selectedBids.value.push(bidId);
    } else {
      alert("Anda dapat membandingkan maksimal 3 proposal sekaligus.");
    }
  }
};

const compareProposals = () => {
  if (selectedBids.value.length < 1) return;
  router.get(route('client.proposal.compare', props.project.id), {
    ids: selectedBids.value.join(',')
  });
};

const acceptProposal = (propId) => {
  if (confirm("Apakah Anda yakin ingin menerima proposal ini? Menerima proposal ini akan menutup pendaftaran dan secara otomatis menolak proposal lainnya.")) {
    router.post(route('client.proposal.terima', propId));
  }
};

const rejectProposal = (propId) => {
  if (confirm("Apakah Anda yakin ingin menolak proposal ini?")) {
    router.post(route('client.proposal.tolak', propId));
  }
};

const closeProject = () => {
  if (confirm("Apakah Anda yakin ingin menutup proyek ini secara manual?")) {
    router.put(route('client.proyek.tutup', props.project.id));
  }
};
</script>

<template>
  <ProfileLayout>
    <Head :title="project.title" />

    <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500 pb-20 relative">
      <!-- Top Actions -->
      <div class="flex items-center justify-between">
        <Link :href="route('client.proyek.index')" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
          <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
          Kembali ke Proyek
        </Link>
        
        <div class="flex items-center gap-2" v-if="project.status === 'aktif'">
          <Button variant="outline" size="sm" class="rounded-xl font-bold border-slate-200 text-slate-600 hover:bg-slate-50" @click="closeProject">
            <XCircle class="w-4 h-4 mr-2" />
            Tutup Proyek
          </Button>
          <Button asChild size="sm" class="rounded-xl font-bold">
            <Link :href="route('client.proyek.edit', project.id)">
              Edit Rincian
            </Link>
          </Button>
        </div>
      </div>

      <!-- Project Main Specs Card -->
      <Card class="border-none shadow-sm rounded-3xl overflow-hidden bg-white">
        <CardContent class="p-6 md:p-8 space-y-6">
          <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div class="flex gap-4 items-start">
              <div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center shrink-0">
                <Building2 class="w-7 h-7" />
              </div>
              <div>
                <h1 class="text-2xl md:text-3xl font-display font-bold leading-tight">{{ project.title }}</h1>
                <div class="flex flex-wrap gap-y-2 gap-x-4 mt-2 items-center text-xs font-semibold text-slate-500">
                  <span class="flex items-center gap-1.5"><MapPin class="w-3.5 h-3.5 text-primary" /> {{ project.location }}</span>
                  <span class="flex items-center gap-1.5"><Calendar class="w-3.5 h-3.5 text-primary" /> Dibuat pada {{ new Date(project.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border" :class="getStatusColor(project.status)">
                    {{ project.status }}
                  </span>
                </div>
              </div>
            </div>
            
            <div class="bg-slate-50 rounded-2xl p-4 shrink-0 border border-slate-100 flex flex-col md:items-end justify-center min-w-[200px]">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Estimasi Anggaran</span>
              <span class="text-2xl font-display font-extrabold text-primary mt-1.5">{{ formatCurrency(project.budget) }}</span>
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1 shrink-0 bg-primary/5 text-primary px-2 py-0.5 rounded-md self-start md:self-auto">{{ project.category }}</span>
            </div>
          </div>

          <div class="border-t border-slate-100 pt-6">
            <h3 class="font-bold text-slate-700 text-sm mb-3">Deskripsi Lengkap Kebutuhan:</h3>
            <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50/30 p-5 border border-slate-50 rounded-2xl">{{ project.description }}</p>
          </div>

          <!-- Attachment -->
          <div v-if="project.attachment_path" class="flex items-center gap-3 p-3.5 border border-slate-100 rounded-2xl bg-slate-50/50 w-full max-w-md">
            <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
              <FileText class="w-5 h-5" />
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-bold text-slate-700 truncate leading-tight">Dokumen Lampiran Proyek</p>
              <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Format file terunggah</p>
            </div>
            <a :href="'/storage/' + project.attachment_path" target="_blank" class="px-4 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 font-bold text-xs shadow-sm flex items-center gap-1.5 transition-colors text-slate-600">
              <Eye class="w-3.5 h-3.5" />
              Lihat
            </a>
          </div>
        </CardContent>
      </Card>

      <!-- Proposals (Bids) Received Header -->
      <div class="flex items-center justify-between border-b border-slate-200 pb-3 mt-10">
        <div>
          <h2 class="text-xl font-display font-bold">Proposal Penawaran Masuk</h2>
          <p class="text-xs text-slate-500 mt-0.5">Berikut adalah arsitek yang mengajukan penawaran untuk proyek Anda.</p>
        </div>
        <span class="bg-primary text-white font-bold text-xs px-3 py-1 rounded-full">{{ projectProposals.length }} Proposal</span>
      </div>

      <!-- Proposal Cards Grid/List -->
      <div v-if="projectProposals.length > 0" class="space-y-4">
        <!-- Select Bids Help Alert if comparing -->
        <div class="bg-primary/5 border border-primary/10 p-4 rounded-2xl flex items-center justify-between" v-if="project.status === 'aktif'">
          <div class="flex items-center gap-3 text-sm font-semibold text-slate-700">
            <Activity class="w-5 h-5 text-primary" />
            <span>Pilih hingga 3 proposal di bawah untuk dibandingkan secara berdampingan.</span>
          </div>
          <span class="text-xs font-bold text-primary uppercase tracking-wider" v-if="selectedBids.length > 0">{{ selectedBids.length }} Terpilih</span>
        </div>

        <div v-for="proposal in projectProposals" :key="proposal.id" 
             :class="[
               'bg-white border rounded-3xl overflow-hidden transition-all duration-300 relative',
               selectedBids.includes(proposal.id) ? 'border-primary shadow-md' : 'border-slate-100 shadow-sm hover:shadow-md'
             ]">
          <div class="p-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
              
              <!-- Checkbox (only if project active) & Architect Profile Info -->
              <div class="flex gap-4 items-start flex-1 min-w-0">
                <button 
                  v-if="project.status === 'aktif' && proposal.status === 'pending'"
                  @click="toggleBidSelection(proposal.id)"
                  :class="[
                    'w-6 h-6 rounded-lg border flex items-center justify-center shrink-0 mt-1 transition-all',
                    selectedBids.includes(proposal.id) ? 'bg-primary border-primary text-white shadow-sm' : 'border-slate-200 bg-white hover:border-primary/50'
                  ]"
                >
                  <Check class="w-4 h-4" v-if="selectedBids.includes(proposal.id)" />
                </button>

                <Avatar class="h-12 w-12 rounded-xl border border-slate-100 shrink-0">
                  <AvatarImage :src="architectUser(proposal).avatar_url" />
                  <AvatarFallback class="bg-primary/5 text-primary font-bold text-sm">
                    {{ architectInitials(proposal) }}
                  </AvatarFallback>
                </Avatar>

                <div class="min-w-0">
                  <div class="flex items-center gap-2 flex-wrap">
                    <h4 class="font-bold text-slate-800 truncate text-base leading-tight">{{ architectName(proposal) }}</h4>
                    <span v-if="architectProfile(proposal).verification_status === 'verified'" class="bg-emerald-50 text-emerald-700 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-100 flex items-center gap-1 shrink-0">
                      <CheckCircle class="w-3 h-3" /> Terverifikasi
                    </span>
                  </div>
                  <p class="text-xs text-slate-400 mt-1 font-semibold truncate">
                    {{ architectProfile(proposal).status_pekerjaan || 'Arsitek Profesional' }}
                  </p>
                  
                  <div class="flex flex-wrap gap-x-4 gap-y-1 mt-3 items-center text-xs font-semibold text-slate-500">
                    <span class="flex items-center gap-1"><MapPin class="w-3.5 h-3.5 text-slate-400" /> {{ architectUser(proposal).location || 'Lokasi belum diatur' }}</span>
                    <span class="flex items-center gap-1"><Clock class="w-3.5 h-3.5 text-slate-400" /> Diajukan {{ new Date(proposal.created_at).toLocaleDateString('id-ID', { month: 'short', day: 'numeric', year: 'numeric' }) }}</span>
                  </div>
                </div>
              </div>

              <!-- Bid Price & Duration specs -->
              <div class="flex flex-wrap gap-4 shrink-0 bg-slate-50/50 p-4 border border-slate-50 rounded-2xl min-w-[200px] md:flex-col md:items-end justify-center">
                <div>
                  <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none block">Nilai Penawaran</span>
                  <span class="text-lg font-display font-extrabold text-slate-800 mt-0.5 block">{{ formatCurrency(proposal.bid_amount) }}</span>
                </div>
                <div>
                  <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none block">Durasi Pengerjaan</span>
                  <span class="text-sm font-bold text-primary mt-0.5 block">{{ proposal.estimated_time }} Hari Kerja</span>
                </div>
              </div>

              <!-- Proposal Bid Status Badge (if already accepted/rejected) -->
              <div class="shrink-0 flex items-center justify-end" v-if="project.status !== 'aktif' || proposal.status !== 'pending'">
                <span class="px-3.5 py-1 rounded-full text-xs font-bold uppercase tracking-wider border" :class="getProposalStatusColor(proposal.status)">
                  {{ proposal.status }}
                </span>
              </div>
            </div>

            <!-- Pitch cover letter content -->
            <div class="mt-6 border-t border-slate-100 pt-5">
              <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Penjelasan Konsep & Penawaran (Pitch):</h5>
              <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50/30 p-4 border border-slate-50 rounded-xl">{{ proposal.description }}</p>
            </div>

            <!-- Proposal attachments -->
            <div v-if="proposal.attachment_path" class="mt-4 flex items-center gap-2">
              <FileText class="w-4 h-4 text-slate-400 shrink-0" />
              <span class="text-xs font-bold text-slate-500 truncate">Berkas Konsep Lampiran:</span>
              <a :href="'/storage/' + proposal.attachment_path" target="_blank" class="text-xs font-bold text-primary hover:underline ml-1">Unduh Dokumen</a>
            </div>

            <!-- Client Action Buttons (Accept / Reject) -->
            <div class="mt-6 flex justify-end gap-2" v-if="project.status === 'aktif' && proposal.status === 'pending'">
              <Button variant="outline" size="sm" class="rounded-xl border-slate-200 text-red-500 hover:bg-red-50 font-bold text-xs" @click="rejectProposal(proposal.id)">
                <XCircle class="w-3.5 h-3.5 mr-1.5" />
                Tolak
              </Button>
              <Button size="sm" class="rounded-xl font-bold text-xs bg-emerald-600 hover:bg-emerald-700 text-white border-none" @click="acceptProposal(proposal.id)">
                <CheckCircle class="w-3.5 h-3.5 mr-1.5" />
                Terima Proposal & Pilih Arsitek
              </Button>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-else class="bg-white border border-slate-100 rounded-3xl p-16 shadow-sm text-center">
        <EmptyState 
          title="Belum ada penawaran" 
          description="Proyek Anda masih aktif, namun belum ada arsitek yang mengajukan proposal penawaran. Anda akan diberitahu ketika proposal pertama masuk." 
          actionText="Kembali ke Dashboard"
          :actionUrl="route('client.profile')"
        />
      </div>

      <!-- Floating Action Bar for Side-by-Side Comparison -->
      <div 
        v-if="selectedBids.length > 0" 
        class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-white/95 backdrop-blur shadow-2xl rounded-2xl py-3.5 px-6 border border-slate-200 flex items-center gap-4 z-50 animate-in fade-in-0 slide-in-from-bottom-5 duration-300 max-w-[90%] w-max"
      >
        <span class="text-xs font-bold text-slate-700">
          <span class="bg-primary text-white w-5 h-5 rounded-full inline-flex items-center justify-center text-[10px] font-black mr-2">{{ selectedBids.length }}</span>
          Proposal Terpilih
        </span>
        <Button size="sm" class="rounded-xl font-bold shadow-md bg-primary hover:bg-primary/95 text-xs px-5 h-9" @click="compareProposals">
          Bandingkan Penawaran
          <ArrowUpRight class="w-3.5 h-3.5 ml-1.5" />
        </Button>
        <button @click.prevent="selectedBids = []" class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors">
          <XCircle class="w-4 h-4" />
        </button>
      </div>
    </div>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
