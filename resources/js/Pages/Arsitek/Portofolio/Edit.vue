<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Textarea } from "@/Components/UI/ui/textarea";
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { ArrowLeft, Upload, X, Image as ImageIcon, Save } from 'lucide-vue-next';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    portofolio: Object
});

const thumbnailPreview = ref(props.portofolio.thumbnail ? '/storage/' + props.portofolio.thumbnail : null);
const galleryPreviews = ref(props.portofolio.images ? props.portofolio.images.map(img => '/storage/' + img) : []);

const form = useForm({
    _method: 'PUT',
    title: props.portofolio.title,
    description: props.portofolio.description,
    project_date: props.portofolio.project_date ? props.portofolio.project_date.split('T')[0] : '',
    link: props.portofolio.link || '',
    thumbnail: null,
    images: [],
});

const onThumbnailChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.thumbnail = file;
        thumbnailPreview.value = URL.createObjectURL(file);
    }
};

const onGalleryChange = (e) => {
    const files = Array.from(e.target.files);
    form.images = [...form.images, ...files];
    
    files.forEach(file => {
        galleryPreviews.value.push(URL.createObjectURL(file));
    });
};

const removeGalleryItem = (index) => {
    // If it's a new file being added
    if (index >= (props.portofolio.images?.length || 0)) {
        const newFileIndex = index - (props.portofolio.images?.length || 0);
        form.images.splice(newFileIndex, 1);
    }
    galleryPreviews.value.splice(index, 1);
};

const submit = () => {
    // Note: We use post with _method PUT because Laravel doesn't support files with patch/put directly in some cases
    form.post(route('arsitek.portofolio.update', props.portofolio.id), {
        forceFormData: true,
        onSuccess: () => {
            // Clean up object URLs
        }
    });
};
</script>

<template>
    <ProfileLayout>
        <Head :title="'Edit ' + portofolio.title" />

        <div class="max-w-4xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-500">
            <!-- Navigation -->
            <Link :href="route('arsitek.portofolio.index')" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
                <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                Kembali ke Portofolio
            </Link>

            <form @submit.prevent="submit" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-6 order-2 lg:order-1">
                    <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden">
                        <CardHeader class="pb-4">
                            <CardTitle class="text-2xl font-display font-bold">Edit Proyek</CardTitle>
                            <CardDescription>Perbarui informasi karya arsitektur Anda.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <div class="space-y-2">
                                <Label for="title" class="text-sm font-bold text-slate-700">Judul Proyek <span class="text-red-500">*</span></Label>
                                <Input id="title" v-model="form.title" placeholder="Cth: Villa Minimalis Canggu" class="rounded-xl border-slate-200 h-12 focus:ring-primary/20" />
                                <InputError :message="form.errors.title" />
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="project_date" class="text-sm font-bold text-slate-700">Tanggal Selesai</Label>
                                    <Input id="project_date" type="date" v-model="form.project_date" class="rounded-xl border-slate-200 h-12" />
                                    <InputError :message="form.errors.project_date" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="link" class="text-sm font-bold text-slate-700">Link Eksternal (Optional)</Label>
                                    <Input id="link" v-model="form.link" placeholder="https://behance.net/..." class="rounded-xl border-slate-200 h-12" />
                                    <InputError :message="form.errors.link" />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label for="description" class="text-sm font-bold text-slate-700">Deskripsi Proyek</Label>
                                <Textarea id="description" v-model="form.description" rows="6" placeholder="Ceritakan tentang konsep, material, dan tantangan..." class="rounded-2xl border-slate-200 focus:ring-primary/20" />
                                <InputError :message="form.errors.description" />
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Gallery -->
                    <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden">
                        <CardHeader>
                            <CardTitle class="text-xl font-display font-bold">Galeri Gambar</CardTitle>
                            <CardDescription>Unggah foto-foto pendukung baru (akan menggantikan galeri saat ini).</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <!-- Previews -->
                                <div v-for="(url, index) in galleryPreviews" :key="index" class="relative aspect-square rounded-2xl overflow-hidden border border-slate-200 group">
                                    <img :src="url" class="w-full h-full object-cover" />
                                    <button @click.prevent="removeGalleryItem(index)" class="absolute top-2 right-2 p-1.5 bg-red-500 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                        <X class="w-4 h-4" />
                                    </button>
                                </div>

                                <!-- Upload Trigger -->
                                <label class="aspect-square rounded-2xl border-2 border-dashed border-slate-200 hover:border-primary/50 hover:bg-primary/5 transition-all cursor-pointer flex flex-col items-center justify-center gap-2">
                                    <input type="file" multiple @change="onGalleryChange" class="hidden" accept="image/*" />
                                    <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center">
                                        <Plus class="w-5 h-5 text-slate-400" />
                                    </div>
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tambah Foto</span>
                                </label>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-4 font-bold uppercase tracking-widest leading-relaxed">Catatan: Mengunggah foto baru akan menggantikan seluruh foto galeri yang sudah ada saat ini.</p>
                            <InputError :message="form.errors.images" class="mt-2" />
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar Actions / Thumbnail -->
                <div class="space-y-6 order-1 lg:order-2">
                    <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden">
                        <CardHeader>
                            <CardTitle class="text-xl font-display font-bold">Thumbnail</CardTitle>
                            <CardDescription>Gambar utama proyek.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="relative w-full aspect-[4/3] rounded-2xl overflow-hidden bg-slate-50 border border-slate-200 group">
                                <img v-if="thumbnailPreview" :src="thumbnailPreview" class="w-full h-full object-cover" />
                                <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-2">
                                    <ImageIcon class="w-10 h-10 opacity-20" />
                                    <span class="text-[10px] font-bold uppercase tracking-widest opacity-40">Utama</span>
                                </div>
                                <label class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer flex items-center justify-center text-white text-xs font-bold gap-2">
                                    <input type="file" @change="onThumbnailChange" class="hidden" accept="image/*" />
                                    <Upload class="w-4 h-4" />
                                    Ganti Thumbnail
                                </label>
                            </div>
                            <InputError :message="form.errors.thumbnail" class="mt-2" />
                        </CardContent>
                    </Card>

                    <Button type="submit" :disabled="form.processing" class="w-full h-14 rounded-2xl font-bold gap-2 shadow-xl shadow-primary/20 hover:shadow-primary/30 active:scale-[0.98] transition-all">
                        <Save class="w-5 h-5" />
                        {{ form.processing ? 'Menyimpan...' : 'Perbarui Portofolio' }}
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
