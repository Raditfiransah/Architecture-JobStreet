<script setup>
import { ref } from "vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
  ArrowLeft,
  Upload,
  Trash2,
  Save,
  X,
  ImageOff,
  ExternalLink
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Textarea } from "@/Components/UI/ui/textarea";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";

const props = defineProps({
  portofolio: Object,
});

const form = useForm({
  title: props.portofolio.title ?? "",
  description: props.portofolio.description ?? "",
  project_date: props.portofolio.project_date ? props.portofolio.project_date.substring(0, 10) : "",
  link: props.portofolio.link ?? "",
  thumbnail: null,
  images: [],
  _method: "PUT",
});

/* --- preview state --- */
const thumbnailPreview = ref(null);
const newImagesPreview = ref([]);

const storageUrl = (path) => {
  if (!path) return null;
  return path.startsWith("http") ? path : `/storage/${path}`;
};

const onThumbnailChange = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  form.thumbnail = file;
  thumbnailPreview.value = URL.createObjectURL(file);
};

const onImagesChange = (e) => {
  const files = Array.from(e.target.files);
  form.images = files;
  newImagesPreview.value = files.map((f) => URL.createObjectURL(f));
};

const submit = () => {
  form.post(route("admin.portofolio.update", props.portofolio.id), {
    forceFormData: true,
    preserveScroll: true,
  });
};

const handleDeleteImage = (path) => {
  if (!confirm("Hapus gambar ini?")) return;
  router.delete(route("admin.portofolio.destroy-image", props.portofolio.id), {
    data: { path },
    preserveScroll: true,
  });
};
</script>

<template>
  <Head :title="`Edit Portofolio — ${portofolio.title}`" />

  <AuthenticatedLayout>
    <div class="max-w-4xl mx-auto space-y-8">
      <!-- Header -->
      <div class="flex items-center gap-4">
        <Button variant="ghost" size="icon" asChild class="rounded-xl">
          <Link :href="route('admin.portofolio.show', portofolio.user_id)">
            <ArrowLeft class="w-5 h-5" />
          </Link>
        </Button>
        <div>
          <h1 class="text-2xl font-bold tracking-tight text-foreground">Edit Portofolio</h1>
          <p class="text-sm text-muted-foreground">Milik: {{ portofolio.user?.name }}</p>
        </div>
      </div>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

          <!-- Left: Fields -->
          <div class="lg:col-span-2 space-y-6">
            <Card class="border-border/60 shadow-sm rounded-2xl">
              <CardHeader class="border-b border-border/40 bg-muted/10 px-8 py-5">
                <CardTitle class="text-sm font-bold">Informasi Dasar</CardTitle>
              </CardHeader>
              <CardContent class="p-8 space-y-5">
                <div class="space-y-2">
                  <Label for="title" class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Judul Proyek *</Label>
                  <Input id="title" v-model="form.title" placeholder="Nama proyek" class="h-11 rounded-xl" />
                  <p v-if="form.errors.title" class="text-xs text-rose-500 font-medium">{{ form.errors.title }}</p>
                </div>

                <div class="space-y-2">
                  <Label for="desc" class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Deskripsi</Label>
                  <Textarea id="desc" v-model="form.description" rows="4" placeholder="Ceritakan tentang proyek ini..." class="rounded-xl resize-none" />
                </div>

                <div class="grid grid-cols-2 gap-4">
                  <div class="space-y-2">
                    <Label for="date" class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Tanggal Proyek</Label>
                    <Input id="date" v-model="form.project_date" type="date" class="h-11 rounded-xl" />
                  </div>
                  <div class="space-y-2">
                    <Label for="link" class="text-xs font-bold uppercase tracking-wider text-muted-foreground">URL Eksternal</Label>
                    <div class="relative">
                      <ExternalLink class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                      <Input id="link" v-model="form.link" placeholder="https://..." class="h-11 rounded-xl pl-10" />
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Gallery Images -->
            <Card class="border-border/60 shadow-sm rounded-2xl">
              <CardHeader class="border-b border-border/40 bg-muted/10 px-8 py-5">
                <CardTitle class="text-sm font-bold">Galeri Gambar</CardTitle>
              </CardHeader>
              <CardContent class="p-8 space-y-6">
                <!-- Existing images -->
                <div v-if="portofolio.images?.length" class="space-y-3">
                  <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground">Gambar Saat Ini</p>
                  <div class="grid grid-cols-3 gap-3">
                    <div
                      v-for="(img, idx) in portofolio.images"
                      :key="idx"
                      class="group relative aspect-video rounded-xl overflow-hidden border border-border/60"
                    >
                      <img :src="storageUrl(img)" :alt="`Gambar ${idx + 1}`" class="w-full h-full object-cover" />
                      <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                        <button
                          type="button"
                          @click="handleDeleteImage(img)"
                          class="p-2 bg-rose-500 hover:bg-rose-600 text-white rounded-lg transition-colors"
                        >
                          <Trash2 class="w-4 h-4" />
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="flex flex-col items-center justify-center py-8 bg-muted/30 rounded-xl border border-dashed border-border">
                  <ImageOff class="w-8 h-8 text-muted-foreground/40 mb-2" />
                  <p class="text-xs font-medium text-muted-foreground">Belum ada gambar galeri</p>
                </div>

                <!-- Upload new images -->
                <div class="space-y-3">
                  <p class="text-[11px] font-bold uppercase tracking-wider text-muted-foreground">Tambah Gambar Baru</p>
                  <label class="flex flex-col items-center justify-center py-8 px-4 border-2 border-dashed border-border/60 rounded-xl cursor-pointer hover:border-primary/50 hover:bg-primary/5 transition-all duration-200">
                    <Upload class="w-8 h-8 text-muted-foreground/50 mb-3" />
                    <span class="text-sm font-bold text-muted-foreground">Klik atau seret gambar ke sini</span>
                    <span class="text-[11px] text-muted-foreground/60 mt-1">PNG, JPG, WEBP (maks. 50MB per file)</span>
                    <input type="file" multiple accept="image/*" class="hidden" @change="onImagesChange" />
                  </label>
                  <div v-if="newImagesPreview.length" class="grid grid-cols-3 gap-3 mt-3">
                    <div v-for="(src, i) in newImagesPreview" :key="i" class="aspect-video rounded-xl overflow-hidden border border-primary/30">
                      <img :src="src" class="w-full h-full object-cover" />
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          <!-- Right: Thumbnail -->
          <div class="space-y-6">
            <Card class="border-border/60 shadow-sm rounded-2xl sticky top-8">
              <CardHeader class="border-b border-border/40 bg-muted/10 px-6 py-5">
                <CardTitle class="text-sm font-bold">Thumbnail</CardTitle>
              </CardHeader>
              <CardContent class="p-6 space-y-4">
                <div class="aspect-video rounded-xl overflow-hidden border border-border/60 bg-muted/30">
                  <img
                    v-if="thumbnailPreview || storageUrl(portofolio.thumbnail)"
                    :src="thumbnailPreview || storageUrl(portofolio.thumbnail)"
                    class="w-full h-full object-cover"
                    alt="Thumbnail preview"
                  />
                  <div v-else class="w-full h-full flex flex-col items-center justify-center gap-2">
                    <ImageOff class="w-8 h-8 text-muted-foreground/30" />
                    <span class="text-[11px] text-muted-foreground/40 font-bold uppercase">Belum ada</span>
                  </div>
                </div>

                <label class="flex items-center justify-center gap-2 px-4 py-3 border border-dashed border-border/60 rounded-xl cursor-pointer hover:border-primary/50 hover:bg-primary/5 transition-all">
                  <Upload class="w-4 h-4 text-muted-foreground" />
                  <span class="text-xs font-bold text-muted-foreground">Ganti Thumbnail</span>
                  <input type="file" accept="image/*" class="hidden" @change="onThumbnailChange" />
                </label>
              </CardContent>
            </Card>

            <!-- Save Button -->
            <Button
              type="submit"
              :disabled="form.processing"
              class="w-full h-12 rounded-xl font-bold text-sm uppercase tracking-wider shadow-lg shadow-primary/20 gap-2"
            >
              <Save class="w-4 h-4" />
              {{ form.processing ? "Menyimpan..." : "Simpan Perubahan" }}
            </Button>

            <Button
              type="button"
              variant="outline"
              asChild
              class="w-full h-11 rounded-xl font-bold text-xs uppercase tracking-wider"
            >
              <Link :href="route('admin.portofolio.show', portofolio.user_id)">
                <X class="w-4 h-4 mr-2" />
                Batal
              </Link>
            </Button>
          </div>
        </div>
      </form>
    </div>
  </AuthenticatedLayout>
</template>
