<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Textarea } from "@/Components/UI/ui/textarea";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { ArrowLeft, Upload, FileText, X, Save, Building2 } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';

const fileInputRef = ref(null);
const fileName = ref('');
const fileSize = ref('');

const categories = [
  'Residensial (Rumah, Villa, Apartemen)',
  'Komersial (Ruko, Kantor, Hotel, Kafe)',
  'Desain Interior',
  'Lansekap & Taman',
  'Urban Planning & Kawasan',
  'Renovasi & Sipil',
  'Lainnya'
];

const form = useForm({
  title: '',
  description: '',
  budget: '',
  category: '',
  location: '',
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

const submit = () => {
  form.post(route('client.proyek.store'), {
    forceFormData: true
  });
};
</script>

<template>
  <ProfileLayout>
    <Head title="Posting Proyek Baru" />

    <div class="max-w-4xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-500">
      <!-- Navigation -->
      <Link :href="route('client.proyek.index')" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
        <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
        Kembali ke Daftar Proyek
      </Link>

      <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Form Info -->
        <div class="lg:col-span-2 space-y-6">
          <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-4">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                  <Building2 class="w-5 h-5" />
                </div>
                <div>
                  <CardTitle class="text-2xl font-display font-bold">Detail Proyek</CardTitle>
                  <CardDescription>Berikan rincian desain bangunan yang ingin Anda kerjakan.</CardDescription>
                </div>
              </div>
            </CardHeader>
            <CardContent class="space-y-6">
              <!-- Judul Proyek -->
              <div class="space-y-2">
                <Label for="title" class="text-sm font-bold text-slate-700">Nama/Judul Proyek <span class="text-red-500">*</span></Label>
                <Input id="title" v-model="form.title" placeholder="Cth: Desain Rumah Minimalis 2 Lantai Bali" class="rounded-xl border-slate-200 h-12 focus:ring-primary/20" />
                <InputError :message="form.errors.title" />
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Kategori -->
                <div class="space-y-2">
                  <Label for="category" class="text-sm font-bold text-slate-700">Kategori <span class="text-red-500">*</span></Label>
                  <select id="category" v-model="form.category" class="w-full h-12 rounded-xl border border-slate-200 bg-white px-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                    <option value="" disabled selected>Pilih Kategori</option>
                    <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
                  </select>
                  <InputError :message="form.errors.category" />
                </div>

                <!-- Lokasi -->
                <div class="space-y-2">
                  <Label for="location" class="text-sm font-bold text-slate-700">Lokasi Proyek <span class="text-red-500">*</span></Label>
                  <Input id="location" v-model="form.location" placeholder="Cth: Malang, Jawa Timur" class="rounded-xl border-slate-200 h-12" />
                  <InputError :message="form.errors.location" />
                </div>
              </div>

              <!-- Deskripsi Proyek -->
              <div class="space-y-2">
                <Label for="description" class="text-sm font-bold text-slate-700">Rincian Deskripsi Proyek <span class="text-red-500">*</span></Label>
                <Textarea id="description" v-model="form.description" rows="8" placeholder="Tuliskan spesifikasi ruangan, kebutuhan luas tanah, gaya arsitektur yang diinginkan (Klasik, Skandinavia, Modern), estimasi pengerjaan, dan catatan tambahan lainnya..." class="rounded-2xl border-slate-200 focus:ring-primary/20" />
                <InputError :message="form.errors.description" />
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Right Side Settings & Attachments -->
        <div class="space-y-6">
          <!-- Budget & Actions -->
          <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-3">
              <CardTitle class="text-lg font-display font-bold">Estimasi Anggaran</CardTitle>
              <CardDescription>Berapa estimasi dana untuk jasa arsitek?</CardDescription>
            </CardHeader>
            <CardContent class="space-y-4">
              <div class="space-y-2">
                <Label for="budget" class="text-sm font-bold text-slate-700">Anggaran (Rp) <span class="text-red-500">*</span></Label>
                <div class="relative">
                  <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm font-bold text-slate-400">Rp</span>
                  <Input id="budget" type="number" v-model="form.budget" placeholder="Cth: 15000000" class="pl-10 rounded-xl border-slate-200 h-12 focus:ring-primary/20 font-semibold" />
                </div>
                <InputError :message="form.errors.budget" />
              </div>
            </CardContent>
          </Card>

          <!-- Lampiran File -->
          <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden bg-white">
            <CardHeader class="pb-3">
              <CardTitle class="text-lg font-display font-bold">Dokumen Pendukung</CardTitle>
              <CardDescription>Unggah denah kasar lahan atau sketsa referensi.</CardDescription>
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
                <span class="text-xs font-bold text-slate-700">Pilih File Lampiran</span>
                <span class="text-[10px] text-slate-400">PDF, ZIP, JPG, atau PNG (Maks 10MB)</span>
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
            <Save class="w-5 h-5" />
            {{ form.processing ? 'Memublikasikan...' : 'Publikasikan Proyek' }}
          </Button>
        </div>
      </form>
    </div>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
