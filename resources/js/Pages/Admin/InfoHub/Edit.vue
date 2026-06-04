<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/Components/UI/ui/button';
import { Input } from '@/Components/UI/ui/input';
import { Textarea } from '@/Components/UI/ui/textarea';
import { Label } from '@/Components/UI/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/UI/ui/select';
import { ArrowLeft, Save, Upload } from 'lucide-vue-next';

const props = defineProps({
    categories: {
        type: Array,
        default: () => ['Event', 'Sayembara', 'Magang'],
    },
    infoHub: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    _method: 'put',
    judul: props.infoHub.judul,
    kategori: props.infoHub.kategori,
    deskripsi: props.infoHub.deskripsi,
    gambar_poster: null,
});

const submit = () => {
    form.post(route('admin.info.update', props.infoHub.id), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Edit Info Hub" />

    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.info.index')">
                    <Button variant="ghost" size="icon" class="rounded-full">
                        <ArrowLeft class="w-5 h-5" />
                    </Button>
                </Link>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-foreground">Edit Postingan Mading</h1>
                    <p class="text-muted-foreground mt-1">Perbarui judul, kategori, deskripsi, atau poster Info Hub.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="bg-card border border-border/60 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
                <div class="space-y-2">
                    <Label for="judul">Judul Info</Label>
                    <Input id="judul" v-model="form.judul" type="text" placeholder="Masukkan judul..." />
                    <p v-if="form.errors.judul" class="text-sm text-red-500 font-medium mt-1">{{ form.errors.judul }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="kategori">Kategori</Label>
                    <Select v-model="form.kategori">
                        <SelectTrigger id="kategori" class="h-10">
                            <SelectValue placeholder="Pilih kategori" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem v-for="category in categories" :key="category" :value="category">
                                {{ category }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                    <p v-if="form.errors.kategori" class="text-sm text-red-500 font-medium mt-1">{{ form.errors.kategori }}</p>
                </div>

                <div class="space-y-2">
                    <Label for="deskripsi">Deskripsi Lengkap</Label>
                    <Textarea id="deskripsi" v-model="form.deskripsi" rows="8" placeholder="Tuliskan detail jadwal, lokasi, ketentuan, atau informasi kegiatan..." />
                    <p v-if="form.errors.deskripsi" class="text-sm text-red-500 font-medium mt-1">{{ form.errors.deskripsi }}</p>
                </div>

                <div class="space-y-3">
                    <Label for="gambar_poster">Gambar Poster</Label>
                    <div v-if="infoHub.image_url" class="overflow-hidden rounded-xl border border-border/60 bg-muted">
                        <img :src="infoHub.image_url" :alt="`Poster ${infoHub.judul}`" class="aspect-video w-full object-cover" />
                    </div>
                    <div class="border-2 border-dashed border-border/60 rounded-xl p-6 flex flex-col items-center justify-center bg-muted/20 hover:bg-muted/40 transition-colors">
                        <Upload class="w-8 h-8 text-muted-foreground mb-3" />
                        <Input
                            id="gambar_poster"
                            type="file"
                            accept="image/jpeg,image/png,image/jpg,image/webp"
                            @change="form.gambar_poster = $event.target.files[0]"
                            class="max-w-xs cursor-pointer"
                        />
                        <p class="text-xs text-muted-foreground mt-2">Kosongkan jika tidak ingin mengganti poster. Maksimal 4MB.</p>
                    </div>
                    <p v-if="form.errors.gambar_poster" class="text-sm text-red-500 font-medium mt-1">{{ form.errors.gambar_poster }}</p>
                </div>

                <div class="pt-4 border-t border-border/50 flex justify-end">
                    <Button type="submit" :disabled="form.processing" class="gap-2 px-8">
                        <Save class="w-4 h-4" />
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </Button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
