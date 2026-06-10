<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Textarea } from "@/Components/UI/ui/textarea";
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from "@/Components/UI/ui/select";
import { ArrowLeft, Save, Plus, X, Info } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    lowongan: Object,
    isEdit: Boolean
});

const toDateInputValue = (value) => {
    if (!value) return '';

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) return '';

    const timezoneOffset = date.getTimezoneOffset() * 60000;
    return new Date(date.getTime() - timezoneOffset).toISOString().split('T')[0];
};

const dateAfterToday = (days) => {
    const date = new Date();
    date.setDate(date.getDate() + days);

    return toDateInputValue(date);
};

const form = useForm({
    posisi: props.lowongan?.posisi || '',
    kota: props.lowongan?.kota || '',
    tipe: props.lowongan?.tipe || 'Full Time',
    gaji: props.lowongan?.gaji || '',
    deskripsi: props.lowongan?.deskripsi || '',
    syarat: props.lowongan?.syarat || [''],
    tanggung_jawab: props.lowongan?.tanggung_jawab || [''],
    tanggal_mulai: toDateInputValue(props.lowongan?.tanggal_mulai) || toDateInputValue(new Date()),
    batas_lamaran: toDateInputValue(props.lowongan?.batas_lamaran || props.lowongan?.deadline) || dateAfterToday(30),
});

const addRequirement = () => form.syarat.push('');
const removeRequirement = (index) => form.syarat.splice(index, 1);

const addResponsibility = () => form.tanggung_jawab.push('');
const removeResponsibility = (index) => form.tanggung_jawab.splice(index, 1);

const submit = () => {
    if (props.isEdit) {
        form.put(route('perusahaan.lowongan.update', props.lowongan.id));
    } else {
        form.post(route('perusahaan.lowongan.store'));
    }
};
</script>

<template>
    <ProfileLayout>
        <Head :title="isEdit ? 'Edit Lowongan' : 'Buat Lowongan'" />

        <div class="max-w-4xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-500">
            <!-- Navigation -->
            <Link :href="route('perusahaan.lowongan.index')" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
                <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                Kembali ke Daftar Lowongan
            </Link>

            <form @submit.prevent="submit" class="space-y-8 pb-20">
                <!-- Main Info Card -->
                <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden">
                    <CardHeader class="px-5 sm:px-8 pt-6 sm:pt-8">
                        <CardTitle class="text-xl sm:text-2xl font-display font-bold">{{ isEdit ? 'Perbarui Lowongan' : 'Publikasikan Lowongan Baru' }}</CardTitle>
                        <CardDescription>Berikan detail posisi yang Anda cari untuk menarik kandidat terbaik.</CardDescription>
                    </CardHeader>
                    <CardContent class="px-5 sm:px-8 pb-6 sm:pb-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="posisi" class="text-sm font-bold text-slate-700">Posisi Pekerjaan <span class="text-red-500">*</span></Label>
                                <Input id="posisi" v-model="form.posisi" placeholder="Cth: Senior Architect" class="rounded-xl border-slate-200 h-12 focus:ring-primary/20" />
                                <InputError :message="form.errors.posisi" />
                            </div>
                            <div class="space-y-2">
                                <Label for="kota" class="text-sm font-bold text-slate-700">Lokasi Penempatan <span class="text-red-500">*</span></Label>
                                <Input id="kota" v-model="form.kota" placeholder="Cth: Jakarta Selatan" class="rounded-xl border-slate-200 h-12 focus:ring-primary/20" />
                                <InputError :message="form.errors.kota" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="tipe" class="text-sm font-bold text-slate-700">Tipe Kontrak <span class="text-red-500">*</span></Label>
                                <Select v-model="form.tipe">
                                    <SelectTrigger class="rounded-xl border-slate-200 h-12">
                                        <SelectValue placeholder="Pilih Tipe" />
                                    </SelectTrigger>
                                    <SelectContent class="rounded-xl">
                                        <SelectItem value="Full Time">Full Time</SelectItem>
                                        <SelectItem value="Part Time">Part Time</SelectItem>
                                        <SelectItem value="Freelance">Freelance</SelectItem>
                                        <SelectItem value="Contract">Contract</SelectItem>
                                        <SelectItem value="Internship">Internship</SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.tipe" />
                            </div>
                            <div class="space-y-2">
                                <Label for="gaji" class="text-sm font-bold text-slate-700">Estimasi Gaji (Optional)</Label>
                                <Input id="gaji" v-model="form.gaji" placeholder="Cth: 10jt - 15jt" class="rounded-xl border-slate-200 h-12 focus:ring-primary/20" />
                                <InputError :message="form.errors.gaji" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="tanggal_mulai" class="text-sm font-bold text-slate-700">Tanggal Mulai <span class="text-red-500">*</span></Label>
                                <Input id="tanggal_mulai" type="date" v-model="form.tanggal_mulai" class="rounded-xl border-slate-200 h-12 focus:ring-primary/20" />
                                <InputError :message="form.errors.tanggal_mulai" />
                            </div>
                            <div class="space-y-2">
                                <Label for="batas_lamaran" class="text-sm font-bold text-slate-700">Batas Lamaran <span class="text-red-500">*</span></Label>
                                <Input id="batas_lamaran" type="date" v-model="form.batas_lamaran" class="rounded-xl border-slate-200 h-12 focus:ring-primary/20" />
                                <InputError :message="form.errors.batas_lamaran" />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <Label for="deskripsi" class="text-sm font-bold text-slate-700">Deskripsi Pekerjaan <span class="text-red-500">*</span></Label>
                            <Textarea id="deskripsi" v-model="form.deskripsi" rows="8" placeholder="Jelaskan visi proyek, lingkup kerja, dan ekspektasi Anda..." class="rounded-2xl border-slate-200 focus:ring-primary/20 leading-relaxed" />
                            <InputError :message="form.errors.deskripsi" />
                        </div>
                    </CardContent>
                </Card>

                <!-- Requirements & Responsibilities -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Requirements -->
                    <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden flex flex-col">
                        <CardHeader class="pb-4">
                            <CardTitle class="text-xl font-display font-bold">Persyaratan</CardTitle>
                            <CardDescription>Skill, pengalaman, atau kualifikasi yang dibutuhkan.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4 flex-1">
                            <div v-for="(item, index) in form.syarat" :key="index" class="flex gap-2">
                                <Input v-model="form.syarat[index]" placeholder="Cth: Pengalaman 3 tahun AutoCAD" class="rounded-xl border-slate-200 h-11" />
                                <Button v-if="form.syarat.length > 1" type="button" variant="ghost" size="icon" @click="removeRequirement(index)" class="rounded-xl text-slate-400 hover:text-red-500 shrink-0">
                                    <X class="w-4 h-4" />
                                </Button>
                            </div>
                            <Button type="button" variant="outline" @click="addRequirement" class="w-full rounded-xl border-dashed border-slate-300 font-bold gap-2 text-slate-500 hover:text-primary hover:border-primary">
                                <Plus class="w-4 h-4" /> Tambah Syarat
                            </Button>
                            <InputError :message="form.errors.syarat" />
                        </CardContent>
                    </Card>

                    <!-- Responsibilities -->
                    <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden flex flex-col">
                        <CardHeader class="pb-4">
                            <CardTitle class="text-xl font-display font-bold">Tanggung Jawab</CardTitle>
                            <CardDescription>Apa yang akan dilakukan kandidat setiap harinya.</CardDescription>
                        </CardHeader>
                        <CardContent class="space-y-4 flex-1">
                            <div v-for="(item, index) in form.tanggung_jawab" :key="index" class="flex gap-2">
                                <Input v-model="form.tanggung_jawab[index]" placeholder="Cth: Mengawasi proyek lapangan" class="rounded-xl border-slate-200 h-11" />
                                <Button v-if="form.tanggung_jawab.length > 1" type="button" variant="ghost" size="icon" @click="removeResponsibility(index)" class="rounded-xl text-slate-400 hover:text-red-500 shrink-0">
                                    <X class="w-4 h-4" />
                                </Button>
                            </div>
                            <Button type="button" variant="outline" @click="addResponsibility" class="w-full rounded-xl border-dashed border-slate-300 font-bold gap-2 text-slate-500 hover:text-primary hover:border-primary">
                                <Plus class="w-4 h-4" /> Tambah Tanggung Jawab
                            </Button>
                            <InputError :message="form.errors.tanggung_jawab" />
                        </CardContent>
                    </Card>
                </div>

                <!-- Action Button: sticky on desktop, static on mobile -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3 bg-white/80 backdrop-blur-md p-4 sm:p-6 rounded-[24px] sm:rounded-[32px] border border-white sm:sticky sm:bottom-6 shadow-xl shadow-slate-200/50">
                    <Link :href="route('perusahaan.lowongan.index')" class="w-full sm:w-auto">
                        <Button type="button" variant="ghost" class="rounded-xl h-12 px-6 font-bold text-slate-500 w-full sm:w-auto">
                            Batal
                        </Button>
                    </Link>
                    <Button type="submit" :disabled="form.processing" class="rounded-xl h-12 px-8 font-bold gap-2 shadow-lg shadow-primary/20 w-full sm:w-auto">
                        <Save class="w-5 h-5" />
                        {{ form.processing ? 'Menyimpan...' : (isEdit ? 'Simpan Perubahan' : 'Terbitkan Lowongan') }}
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
