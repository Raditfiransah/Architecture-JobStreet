<script setup>
import { ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { Label } from "@/Components/UI/ui/label";
import { Input } from "@/Components/UI/ui/input";
import { Textarea } from "@/Components/UI/ui/textarea";
import { 
  Building2, 
  MapPin, 
  Calendar, 
  ArrowLeft,
  Clock,
  FileText,
  Save,
  CheckCircle,
  AlertCircle,
  XCircle,
  Upload,
  X,
  Sparkles
} from "lucide-vue-next";
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  proposal: {
    type: Object,
    required: true
  }
});

const isEditing = ref(false);
const fileInputRef = ref(null);
const fileName = ref(props.proposal.attachment_path ? props.proposal.attachment_path.split('/').pop() : '');
const fileSize = ref('');

const form = useForm({
  _method: 'PUT',
  bid_amount: props.proposal.bid_amount,
  estimated_time: props.proposal.estimated_time,
  description: props.proposal.description,
  attachment: null
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
    case 'diterima': return 'bg-emerald-50 text-emerald-700 border-emerald-100';
    case 'ditolak': return 'bg-rose-50 text-rose-700 border-rose-100';
    default: return 'bg-amber-50 text-amber-700 border-amber-100';
  }
};

const getStatusMessage = (status) => {
  switch (status?.toLowerCase()) {
    case 'diterima': return 'Selamat! Proposal Anda telah diterima oleh klien. Pemilik proyek telah memilih Anda dan pengerjaan dapat segera dikoordinasikan.';
    case 'ditolak': return 'Mohon maaf, pemilik proyek memutuskan untuk memilih proposal lain. Jangan berkecil hati, tetap jelajahi proyek aktif lainnya!';
    default: return 'Proposal Anda saat ini sedang ditinjau oleh pemilik proyek. Anda dapat merevisi harga atau cover letter selama status masih Pending.';
  }
};

const project = () => props.proposal.proyek || {};

const projectOwner = () => project().user || {};

const onFileChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    form.attachment = file;
    fileName.value = file.name;
    fileSize.value = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
  }
};

const triggerFileSelect = () => {
  fileInputRef.value.click();
};

const removeFile = () => {
  form.attachment = null;
  fileName.value = '';
  fileSize.value = '';
  if (fileInputRef.value) {
    fileInputRef.value.value = '';
  }
};

const updateProposal = () => {
  form.post(route('arsitek.proposal.update', props.proposal.id), {
    forceFormData: true,
    onSuccess: () => {
      isEditing.value = false;
    }
  });
};
</script>

<template>
  <ProfileLayout>
    <Head title="Rincian Proposal Penawaran" />

    <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
      
      <!-- Top back navigation -->
      <div class="flex items-center justify-between">
        <Link :href="route('arsitek.proposal.index')" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
          <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
          Kembali ke Daftar Proposal
        </Link>
      </div>

      <!-- Status Alert Box -->
      <div class="border rounded-2xl p-5 flex gap-4 items-start" :class="getProposalStatusColor(proposal.status)">
        <div class="shrink-0 mt-0.5">
          <CheckCircle v-if="proposal.status === 'diterima'" class="w-6 h-6" />
          <XCircle v-else-if="proposal.status === 'ditolak'" class="w-6 h-6" />
          <AlertCircle v-else class="w-6 h-6" />
        </div>
        <div class="space-y-1 flex-1">
          <h3 class="font-bold text-sm uppercase tracking-wider">Status Proposal: {{ proposal.status }}</h3>
          <p class="text-xs font-semibold opacity-90 leading-relaxed">{{ getStatusMessage(proposal.status) }}</p>
        </div>
      </div>

      <!-- Split Layout: Left Form/Detail, Right Project Summary -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Left Pane: Proposal details or editing form -->
        <div class="lg:col-span-2 space-y-6">
          <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-3 flex flex-row items-center justify-between">
              <div>
                <CardTitle class="text-xl font-display font-bold">Rincian Bid Penawaran</CardTitle>
                <CardDescription>Dokumen penawaran harga dan konsep Anda.</CardDescription>
              </div>
              
              <!-- Toggle edit button if pending -->
              <Button 
                v-if="proposal.status === 'pending'"
                @click="isEditing = !isEditing"
                variant="outline" 
                size="sm" 
                class="rounded-xl border-slate-200 text-slate-600 font-bold"
              >
                {{ isEditing ? 'Batal Edit' : 'Edit Bid' }}
              </Button>
            </CardHeader>
            <CardContent class="p-6 md:p-8 space-y-6">
              
              <!-- CASE A: Static Detail View -->
              <div v-if="!isEditing" class="space-y-6">
                <div class="grid grid-cols-2 gap-4 bg-slate-50 p-5 border border-slate-100/50 rounded-2xl">
                  <div>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Harga Penawaran</span>
                    <span class="text-xl font-display font-extrabold text-primary mt-1 block">{{ formatCurrency(proposal.bid_amount) }}</span>
                  </div>
                  <div class="border-l border-slate-200 pl-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Waktu Pengerjaan</span>
                    <span class="text-base font-bold text-slate-700 mt-1 block">{{ proposal.estimated_time }} Hari Kerja</span>
                  </div>
                </div>

                <div class="space-y-2">
                  <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pitch / Konsep Penawaran Jasa:</span>
                  <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50/20 p-5 rounded-2xl border border-slate-50">
                    {{ proposal.description }}
                  </p>
                </div>

                <!-- Attachment Concepts -->
                <div v-if="proposal.attachment_path" class="flex items-center gap-3 p-3.5 border border-slate-100 rounded-2xl bg-slate-50/50 max-w-md">
                  <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                    <FileText class="w-5 h-5" />
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-700 truncate leading-tight">Konsep Lampiran</p>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Dokumen pendukung</p>
                  </div>
                  <a :href="'/storage/' + proposal.attachment_path" target="_blank" class="px-4 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 font-bold text-xs shadow-sm text-slate-600 transition-all shrink-0">Unduh</a>
                </div>
              </div>

              <!-- CASE B: Edit Form View (Only when pending and clicked edit) -->
              <form v-else @submit.prevent="updateProposal" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Bid Amount -->
                  <div class="space-y-2">
                    <Label for="bid_amount" class="text-sm font-bold text-slate-700">Harga Penawaran (Rp) <span class="text-red-500">*</span></Label>
                    <div class="relative">
                      <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                      <Input id="bid_amount" type="number" v-model="form.bid_amount" placeholder="Cth: 12000000" class="pl-10 rounded-xl border-slate-200 h-12 font-semibold" />
                    </div>
                    <InputError :message="form.errors.bid_amount" />
                  </div>

                  <!-- Estimated Time -->
                  <div class="space-y-2">
                    <Label for="estimated_time" class="text-sm font-bold text-slate-700">Estimasi Pengerjaan (Hari) <span class="text-red-500">*</span></Label>
                    <Input id="estimated_time" type="number" v-model="form.estimated_time" placeholder="Cth: 30" class="rounded-xl border-slate-200 h-12 font-semibold" />
                    <InputError :message="form.errors.estimated_time" />
                  </div>
                </div>

                <!-- Description / Pitch -->
                <div class="space-y-2">
                  <Label for="description" class="text-sm font-bold text-slate-700">Penjelasan Konsep & Alasan Memilih Anda <span class="text-red-500">*</span></Label>
                  <Textarea id="description" v-model="form.description" rows="6" placeholder="Jelaskan konsep arsitektur yang Anda tawarkan untuk memikat hati klien. Sebutkan kelebihan Anda..." class="rounded-2xl border-slate-200" />
                  <InputError :message="form.errors.description" />
                </div>

                <!-- Attachment Concept File -->
                <div class="space-y-2">
                  <Label class="text-sm font-bold text-slate-700">Dokumen Penawaran Baru</Label>
                  <!-- Upload area -->
                  <div v-if="!form.attachment && !fileName" 
                       @click="triggerFileSelect"
                       class="border-2 border-dashed border-slate-200 hover:border-primary/50 hover:bg-primary/5 transition-all cursor-pointer p-6 rounded-2xl flex flex-col items-center justify-center gap-2 text-center">
                    <input ref="fileInputRef" type="file" @change="onFileChange" class="hidden" accept=".pdf,.zip,.jpg,.png,.doc,.docx" />
                    <div class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                      <Upload class="w-5 h-5" />
                    </div>
                    <span class="text-xs font-bold text-slate-700">Ganti File Dokumen</span>
                    <span class="text-[10px] text-slate-400">PDF, ZIP (Maks 10MB)</span>
                  </div>

                  <!-- Selected File View -->
                  <div v-else class="flex items-center gap-3 p-3.5 border border-slate-100 bg-slate-50/50 rounded-2xl">
                    <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                      <FileText class="w-5 h-5" />
                    </div>
                    <div class="min-w-0 flex-1">
                      <p class="text-xs font-bold text-slate-700 truncate leading-tight">{{ fileName }}</p>
                      <p class="text-[10px] text-slate-400 font-semibold mt-0.5" v-if="fileSize">{{ fileSize }}</p>
                      <p class="text-[10px] text-slate-400 font-semibold mt-0.5" v-else>Dokumen Tersimpan</p>
                    </div>
                    <button @click.prevent="removeFile" class="w-8 h-8 rounded-lg hover:bg-slate-200 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors shrink-0">
                      <X class="w-4 h-4" />
                    </button>
                  </div>
                  <InputError :message="form.errors.attachment" />
                </div>

                <Button type="submit" :disabled="form.processing" class="w-full h-12 rounded-xl font-bold gap-2">
                  <Save class="w-4 h-4" />
                  {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan Proposal' }}
                </Button>
              </form>
            </CardContent>
          </Card>
        </div>

        <!-- Right Pane: Parent Project Specs Overview -->
        <div class="space-y-6">
          <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-3 border-b border-slate-100">
              <span class="text-[10px] font-black uppercase tracking-wider text-slate-400 block">Proyek yang Ditawar</span>
            </CardHeader>
            <CardContent class="p-5 space-y-4">
              <div class="space-y-2">
                <span class="text-[9px] font-black text-primary uppercase tracking-wider bg-primary/5 px-2.5 py-0.5 rounded-md inline-block leading-none">{{ project().category || 'Proyek' }}</span>
                <Link v-if="project().id" :href="route('proyek.show', project().id)" class="hover:text-primary transition-colors block mt-2">
                  <h3 class="font-extrabold text-slate-800 text-sm leading-snug">{{ project().title || 'Proyek tidak tersedia' }}</h3>
                </Link>
                <h3 v-else class="font-extrabold text-slate-800 text-sm leading-snug mt-2">Proyek tidak tersedia</h3>
              </div>

              <div class="flex items-center gap-3.5 pt-2">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100"><User class="w-4 h-4 text-slate-400" /></div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Pemilik Proyek</p>
                  <p class="text-xs font-bold text-slate-700 truncate max-w-[130px]">{{ projectOwner().name || 'Client Web-Architect' }}</p>
                </div>
              </div>

              <div class="flex items-center gap-3.5">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100"><MapPin class="w-4 h-4 text-slate-400" /></div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Lokasi Proyek</p>
                  <p class="text-xs font-bold text-slate-700">{{ project().location || 'Lokasi tidak tersedia' }}</p>
                </div>
              </div>

              <div class="flex items-center gap-3.5">
                <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100"><Calendar class="w-4 h-4 text-slate-400" /></div>
                <div>
                  <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wider">Anggaran Klien</p>
                  <p class="text-xs font-bold text-primary">{{ formatCurrency(project().budget || 0) }}</p>
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
