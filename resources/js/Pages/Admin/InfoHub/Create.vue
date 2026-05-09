<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/Components/UI/ui/button';
import { Input } from '@/Components/UI/ui/input';
import { Textarea } from '@/Components/UI/ui/textarea';
import { Label } from '@/Components/UI/ui/label';
import { ArrowLeft, Upload, Save } from 'lucide-vue-next';

const form = useForm({
    judul: '',
    kategori: '',
    deskripsi: '',
    gambar_poster: null,
});

const submit = () => {
    form.post(route('admin.info.store'), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Tambah Info Baru" />

    <AuthenticatedLayout>
        <div class="max-w-3xl mx-auto space-y-6">
            <div class="flex items-center gap-4">
                <Link :href="route('admin.info.index')">
                    <Button variant="ghost" size="icon" class="rounded-full">
                        <ArrowLeft class="w-5 h-5" />
                    </Button>
                </Link>
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-foreground">Tambah Info Baru</h1>
                    <p class="text-muted-foreground mt-1">Buat pengumuman event, sayembara, atau magang.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="bg-card border border-border/60 rounded-2xl p-6 md:p-8 shadow-sm space-y-6">
                <!-- Judul -->
                <div class="space-y-2">
                    <Label for="judul">Judul Info</Label>
                    <Input id="judul" v-model="form.judul" type="text" placeholder="Masukkan judul..." />
                    <p v-if="form.errors.judul" class="text-sm text-red-500 font-medium mt-1">{{ form.errors.judul }}</p>
                </div>

                <!-- Kategori -->
                <div class="space-y-2">
                    <Label for="kategori">Kategori</Label>
                    <select 
                        id="kategori" 
                        v-model="form.kategori" 
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    >
                        <option value="" disabled>Pilih Kategori</option>
                        <option value="Event">Event</option>
                        <option value="Sayembara">Sayembara</option>
                        <option value="Magang">Magang</option>
                    </select>
                    <p v-if="form.errors.kategori" class="text-sm text-red-500 font-medium mt-1">{{ form.errors.kategori }}</p>
                </div>

                <!-- Deskripsi -->
                <div class="space-y-2">
                    <Label for="deskripsi">Deskripsi</Label>
                    <Textarea id="deskripsi" v-model="form.deskripsi" rows="6" placeholder="Tuliskan detail lengkap di sini..." />
                    <p v-if="form.errors.deskripsi" class="text-sm text-red-500 font-medium mt-1">{{ form.errors.deskripsi }}</p>
                </div>

                <!-- Gambar Poster -->
                <div class="space-y-2">
                    <Label for="gambar_poster">Gambar Poster</Label>
                    <div class="border-2 border-dashed border-border/60 rounded-xl p-6 flex flex-col items-center justify-center bg-muted/20 hover:bg-muted/40 transition-colors">
                        <Upload class="w-8 h-8 text-muted-foreground mb-3" />
                        <Input 
                            id="gambar_poster" 
                            type="file" 
                            accept="image/jpeg,image/png,image/jpg" 
                            @input="form.gambar_poster = $event.target.files[0]"
                            class="max-w-xs cursor-pointer"
                        />
                        <p class="text-xs text-muted-foreground mt-2">Format yang didukung: JPG, JPEG, PNG. Max 2MB.</p>
                    </div>
                    <p v-if="form.errors.gambar_poster" class="text-sm text-red-500 font-medium mt-1">{{ form.errors.gambar_poster }}</p>
                </div>

                <div class="pt-4 border-t border-border/50 flex justify-end">
                    <Button type="submit" :disabled="form.processing" class="gap-2 px-8">
                        <Save class="w-4 h-4" />
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Info' }}
                    </Button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
