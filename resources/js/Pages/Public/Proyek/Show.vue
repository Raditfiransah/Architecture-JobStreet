<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { 
  Building2, 
  MapPin, 
  Calendar, 
  Calculator,
  Clock,
  ArrowLeft,
  FileText,
  Eye,
  CheckCircle,
  AlertCircle,
  Upload,
  X,
  Send,
  User,
  Activity
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { Label } from "@/Components/UI/ui/label";
import { Input } from "@/Components/UI/ui/input";
import { Textarea } from "@/Components/UI/ui/textarea";
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  project: {
    type: Object,
    required: true
  },
  myProposal: {
    type: Object,
    default: null
  },
  title: String
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const isArchitect = computed(() => user.value?.role === 'arsitek');

const fileInputRef = ref(null);
const fileName = ref('');
const fileSize = ref('');

const form = useForm({
  bid_amount: '',
  estimated_time: '',
  description: '',
  attachment: null
});

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

const submitProposal = () => {
  form.post(route('arsitek.proposal.store', props.project.id), {
    forceFormData: true
  });
};

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
</script>

<template>
  <PublicLayout>
    <Head :title="title" />

    <main class="flex-1 w-full max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-8 animate-in slide-in-from-bottom-4 duration-500">
      
      <!-- Top back navigation -->
      <Link :href="route('proyek.index')" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
        <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
        Kembali ke Eksplorasi Proyek
      </Link>

      <!-- Project Detail Card -->
      <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
        <CardContent class="p-6 md:p-8 space-y-6">
          <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
            <div class="flex gap-4 items-start">
              <div class="w-14 h-14 rounded-2xl bg-primary/5 text-primary flex items-center justify-center shrink-0">
                <Building2 class="w-7 h-7" />
              </div>
              <div>
                <span class="text-[9px] font-black text-primary uppercase tracking-widest bg-primary/5 px-3 py-1 rounded-lg">{{ project.category }}</span>
                <h1 class="text-2xl md:text-3xl font-display font-bold leading-tight mt-3">{{ project.title }}</h1>
                <div class="flex flex-wrap gap-y-2 gap-x-4 mt-2.5 items-center text-xs font-semibold text-slate-500">
                  <span class="flex items-center gap-1.5"><MapPin class="w-3.5 h-3.5 text-primary" /> {{ project.location }}</span>
                  <span class="flex items-center gap-1.5"><Calendar class="w-3.5 h-3.5 text-primary" /> Diposting {{ new Date(project.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
                </div>
              </div>
            </div>
            
            <div class="bg-slate-50 rounded-2xl p-4 shrink-0 border border-slate-100 flex flex-col md:items-end justify-center min-w-[200px]">
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none">Estimasi Anggaran Klien</span>
              <span class="text-2xl font-display font-extrabold text-primary mt-1.5">{{ formatCurrency(project.budget) }}</span>
              <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Sistem Kontrak Bidding</span>
            </div>
          </div>

          <div class="border-t border-slate-100 pt-6 space-y-3">
            <h3 class="font-bold text-slate-700 text-sm">Spesifikasi Lengkap Kebutuhan:</h3>
            <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50/30 p-5 border border-slate-50 rounded-2xl">{{ project.description }}</p>
          </div>

          <!-- Attachment -->
          <div v-if="project.attachment_path" class="flex items-center gap-3 p-3.5 border border-slate-100 rounded-2xl bg-slate-50/50 w-full max-w-md">
            <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
              <FileText class="w-5 h-5" />
            </div>
            <div class="min-w-0 flex-1">
              <p class="text-xs font-bold text-slate-700 truncate leading-tight">Dokumen Lampiran Proyek</p>
              <p class="text-[10px] text-slate-400 font-semibold mt-0.5">Berkas Referensi Denah/Lahan</p>
            </div>
            <a :href="'/storage/' + project.attachment_path" target="_blank" class="px-4 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 font-bold text-xs shadow-sm flex items-center gap-1.5 transition-colors text-slate-600">
              <Eye class="w-3.5 h-3.5" />
              Lihat
            </a>
          </div>
        </CardContent>
      </Card>

      <!-- Bidding / Proposal Box -->
      <div class="mt-8">
        
        <!-- Case 1: Proposal Already Submitted by Architect -->
        <Card v-if="myProposal" class="border-none shadow-sm rounded-3xl overflow-hidden bg-white">
          <CardHeader class="pb-3">
            <div class="flex items-center gap-3">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                <CheckCircle class="w-5 h-5" />
              </div>
              <div>
                <CardTitle class="text-xl font-display font-bold">Proposal Anda Telah Terkirim</CardTitle>
                <CardDescription>Anda telah mengajukan penawaran untuk proyek arsitektur ini.</CardDescription>
              </div>
            </div>
          </CardHeader>
          <CardContent class="p-6 md:p-8 space-y-6">
            <div class="flex flex-wrap gap-4 items-center justify-between bg-slate-50 p-5 rounded-2xl border border-slate-100/50">
              <div class="space-y-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Nominal Penawaran Anda</span>
                <span class="text-xl font-display font-extrabold text-slate-800">{{ formatCurrency(myProposal.bid_amount) }}</span>
              </div>
              <div class="space-y-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Estimasi Waktu Pengerjaan</span>
                <span class="text-sm font-bold text-slate-700">{{ myProposal.estimated_time }} Hari Kerja</span>
              </div>
              <div class="space-y-1">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Status Proposal</span>
                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border" :class="getProposalStatusColor(myProposal.status)">
                  {{ myProposal.status }}
                </span>
              </div>
            </div>
            
            <div class="space-y-2">
              <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pitch / Detail Penawaran Anda:</span>
              <p class="text-slate-600 text-sm leading-relaxed whitespace-pre-line bg-slate-50/20 p-4 border border-slate-50 rounded-xl">{{ myProposal.description }}</p>
            </div>
          </CardContent>
        </Card>

        <!-- Case 2: Logged in as Architect, Project is Active, No Proposal Submitted Yet -->
        <form v-else-if="isArchitect && project.status === 'aktif'" @submit.prevent="submitProposal" class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
          
          <!-- Form fields: stacks below on mobile, takes 2/3 on desktop -->
          <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">
            <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
              <CardHeader class="pb-4">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                    <Send class="w-5 h-5" />
                  </div>
                  <div>
                    <CardTitle class="text-xl font-display font-bold">Kirim Proposal Penawaran</CardTitle>
                    <CardDescription>Ajukan konsep kasar, estimasi harga, dan rancangan awal Anda.</CardDescription>
                  </div>
                </div>
              </CardHeader>
              <CardContent class="space-y-6">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Bid Amount -->
                  <div class="space-y-2">
                    <Label for="bid_amount" class="text-sm font-bold text-slate-700">Harga Penawaran Jasa (Rp) <span class="text-red-500">*</span></Label>
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
                  <Textarea id="description" v-model="form.description" rows="6" placeholder="Jelaskan konsep arsitektur yang Anda tawarkan untuk memikat hati klien. Sebutkan kelebihan Anda dalam merancang model bangunan seperti ini..." class="rounded-2xl border-slate-200" />
                  <InputError :message="form.errors.description" />
                </div>
              </CardContent>
            </Card>
          </div>

          <div class="space-y-6 order-1 lg:order-2">
            <!-- Concept File Attachment -->
            <Card class="border-slate-100 shadow-sm rounded-3xl overflow-hidden bg-white">
              <CardHeader class="pb-3">
                <CardTitle class="text-lg font-display font-bold">Dokumen Penawaran</CardTitle>
                <CardDescription>Unggah dokumen portofolio konsep pendukung.</CardDescription>
              </CardHeader>
              <CardContent class="space-y-4">
                <!-- Upload area -->
                <div v-if="!form.attachment" 
                     @click="triggerFileSelect"
                     class="border-2 border-dashed border-slate-200 hover:border-primary/50 hover:bg-primary/5 transition-all cursor-pointer p-6 rounded-2xl flex flex-col items-center justify-center gap-2 text-center">
                  <input ref="fileInputRef" type="file" @change="onFileChange" class="hidden" accept=".pdf,.zip,.jpg,.png,.doc,.docx" />
                  <div class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400">
                    <Upload class="w-5 h-5" />
                  </div>
                  <span class="text-xs font-bold text-slate-700">Pilih Dokumen Konsep</span>
                  <span class="text-[10px] text-slate-400">PDF, ZIP, JPG (Maks 10MB)</span>
                </div>

                <!-- Selected File View -->
                <div v-else class="flex items-center gap-3 p-3.5 border border-slate-100 bg-slate-50/50 rounded-2xl">
                  <div class="w-10 h-10 bg-primary/10 text-primary rounded-xl flex items-center justify-center shrink-0">
                    <FileText class="w-5 h-5" />
                  </div>
                  <div class="min-w-0 flex-1">
                    <p class="text-xs font-bold text-slate-700 truncate leading-tight">{{ fileName }}</p>
                    <p class="text-[10px] text-slate-400 font-semibold mt-0.5">{{ fileSize }}</p>
                  </div>
                  <button @click.prevent="removeFile" class="w-8 h-8 rounded-lg hover:bg-slate-200 flex items-center justify-center text-slate-400 hover:text-red-500 transition-colors shrink-0">
                    <X class="w-4 h-4" />
                  </button>
                </div>
                <InputError :message="form.errors.attachment" />
              </CardContent>
            </Card>

            <Button type="submit" :disabled="form.processing" class="w-full h-14 rounded-2xl font-bold gap-2 shadow-xl shadow-primary/20 hover:shadow-primary/30 active:scale-[0.98] transition-all">
              <Send class="w-5 h-5" />
              {{ form.processing ? 'Mengirimkan...' : 'Kirim Penawaran (Bid)' }}
            </Button>
          </div>
        </form>

        <!-- Case 3: Project Closed -->
        <Card v-else-if="project.status !== 'aktif'" class="border-none shadow-sm rounded-3xl overflow-hidden bg-slate-50 border border-slate-100 p-8 text-center space-y-4">
          <div class="w-12 h-12 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto">
            <AlertCircle class="w-6 h-6" />
          </div>
          <div class="max-w-md mx-auto space-y-2">
            <h3 class="text-lg font-bold text-slate-700">Proyek Telah Ditutup</h3>
            <p class="text-sm text-slate-400 leading-relaxed">Penerimaan proposal penawaran arsitek untuk proyek ini telah selesai atau ditutup oleh pemilik proyek.</p>
          </div>
        </Card>

        <!-- Case 4: Logged in but NOT an Architect -->
        <Card v-else-if="user && !isArchitect" class="border-none shadow-sm rounded-3xl overflow-hidden bg-slate-50 border border-slate-100 p-8 text-center space-y-4">
          <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center mx-auto">
            <AlertCircle class="w-6 h-6" />
          </div>
          <div class="max-w-md mx-auto space-y-2">
            <h3 class="text-lg font-bold text-slate-700">Hanya untuk Akun Arsitek</h3>
            <p class="text-sm text-slate-400 leading-relaxed">Anda saat ini masuk sebagai <strong>{{ user.role }}</strong>. Hanya pengguna dengan peran <strong>Arsitek</strong> yang dapat mengajukan proposal penawaran.</p>
          </div>
        </Card>

        <!-- Case 5: Guest (Not logged in) -->
        <Card v-else class="border-none shadow-sm rounded-3xl overflow-hidden bg-slate-50 border border-slate-100 p-8 text-center space-y-4">
          <div class="w-12 h-12 bg-primary/5 text-primary rounded-full flex items-center justify-center mx-auto">
            <User class="w-6 h-6" />
          </div>
          <div class="max-w-md mx-auto space-y-2">
            <h3 class="text-lg font-bold text-slate-700">Ajukan Penawaran Anda</h3>
            <p class="text-sm text-slate-400 leading-relaxed">Apakah Anda arsitek berbakat yang tertarik dengan proyek ini? Silakan masuk untuk mengajukan proposal.</p>
            <div class="pt-4 flex justify-center gap-3">
              <Button asChild class="rounded-xl font-bold px-6">
                <Link :href="route('login')">Masuk Sekarang</Link>
              </Button>
              <Button asChild variant="outline" class="rounded-xl font-bold px-6 bg-white border-slate-200">
                <Link :href="route('register')">Daftar Arsitek</Link>
              </Button>
            </div>
          </div>
        </Card>
      </div>

    </main>
  </PublicLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
