<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent } from "@/Components/UI/ui/card";
import { 
  Building2, 
  MapPin, 
  Clock, 
  ArrowUpRight,
  TrendingUp,
  FileText,
  Hourglass,
  CheckCircle,
  XCircle
} from "lucide-vue-next";
import EmptyState from '@/Components/EmptyState.vue';

const props = defineProps({
  proposals: {
    type: Array,
    default: () => []
  }
});

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};

const getStatusColor = (status) => {
  switch (status?.toLowerCase()) {
    case 'diterima': return 'bg-emerald-100 text-emerald-700 border-emerald-200';
    case 'ditolak': return 'bg-rose-100 text-rose-700 border-rose-200';
    default: return 'bg-yellow-100 text-yellow-700 border-yellow-200';
  }
};

const getStatusIcon = (status) => {
  switch (status?.toLowerCase()) {
    case 'diterima': return CheckCircle;
    case 'ditolak': return XCircle;
    default: return Hourglass;
  }
};
</script>

<template>
  <ProfileLayout>
    <Head title="Proposal Penawaran Proyek" />

    <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
      <div>
        <h1 class="text-2xl font-display font-bold text-foreground">Proposal Penawaran Proyek</h1>
        <p class="text-sm text-muted-foreground">Pantau status, harga penawaran, dan keputusan klien terhadap proyek yang Anda tawar.</p>
      </div>

      <!-- Proposal Bids List -->
      <div v-if="proposals.length > 0" class="space-y-4">
        <Card v-for="proposal in proposals" :key="proposal.id" class="border-none shadow-sm rounded-3xl overflow-hidden hover:shadow-md transition-shadow duration-300 bg-white">
          <CardContent class="p-6">
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
              
              <!-- Project Meta Info -->
              <div class="flex gap-4 items-start flex-1 min-w-0">
                <div class="w-12 h-12 rounded-2xl bg-primary/5 text-primary flex items-center justify-center shrink-0">
                  <Building2 class="w-6 h-6" />
                </div>
                <div class="min-w-0">
                  <span class="text-[9px] font-black text-primary uppercase tracking-wider bg-primary/5 px-2.5 py-0.5 rounded-md leading-none">{{ proposal.proyek.category }}</span>
                  <Link :href="route('proyek.show', proposal.proyek.id)" class="hover:text-primary transition-colors block mt-2">
                    <h3 class="font-bold text-slate-800 text-base leading-snug truncate">{{ proposal.proyek.title }}</h3>
                  </Link>
                  <p class="text-xs text-slate-400 font-semibold mt-1 flex items-center gap-1"><MapPin class="w-3.5 h-3.5" /> {{ proposal.proyek.location }}</p>
                  
                  <div class="flex items-center gap-4 mt-3 text-xs text-slate-500 font-semibold">
                    <span class="flex items-center gap-1.5"><Clock class="w-3.5 h-3.5" /> Dikirim: {{ new Date(proposal.created_at).toLocaleDateString('id-ID', { month: 'short', day: 'numeric', year: 'numeric' }) }}</span>
                  </div>
                </div>
              </div>

              <!-- Bid specs details (Amount & Duration) -->
              <div class="flex flex-wrap gap-4 shrink-0 bg-slate-50/50 p-4 border border-slate-50 rounded-2xl min-w-[200px] md:flex-col md:items-end justify-center">
                <div>
                  <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none block">Penawaran Anda</span>
                  <span class="text-base font-display font-extrabold text-slate-700 mt-1 block">{{ formatCurrency(proposal.bid_amount) }}</span>
                </div>
                <div>
                  <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none block">Durasi Kerja</span>
                  <span class="text-xs font-bold text-primary mt-1 block">{{ proposal.estimated_time }} Hari</span>
                </div>
              </div>

              <!-- Action buttons & status badges -->
              <div class="flex flex-col md:items-end justify-between shrink-0 gap-3">
                <span class="px-3.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border flex items-center gap-1.5 shrink-0" :class="getStatusColor(proposal.status)">
                  <component :is="getStatusIcon(proposal.status)" class="w-3.5 h-3.5" />
                  {{ proposal.status }}
                </span>
                
                <Button asChild variant="outline" size="sm" class="rounded-xl border-slate-200 text-slate-600 font-bold text-xs">
                  <Link :href="route('arsitek.proposal.show', proposal.id)">
                    Detail Proposal
                    <ArrowUpRight class="w-3.5 h-3.5 ml-1.5" />
                  </Link>
                </Button>
              </div>

            </div>
          </CardContent>
        </Card>
      </div>

      <!-- Empty State -->
      <div v-else class="bg-white border border-slate-100 rounded-3xl p-16 shadow-sm text-center">
        <EmptyState 
          title="Belum ada proposal" 
          description="Anda belum pernah mengajukan proposal penawaran proyek. Jelajahi proyek aktif dan ajukan proposal desain bangunan terbaik Anda." 
          actionText="Eksplorasi Proyek Aktif"
          :actionUrl="route('proyek.index')"
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
