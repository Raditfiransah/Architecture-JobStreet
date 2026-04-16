<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { 
  Plus, 
  MapPin, 
  Clock, 
  Users, 
  MoreVertical, 
  Edit3, 
  Trash2, 
  Eye, 
  PauseCircle, 
  PlayCircle 
} from 'lucide-vue-next';
import { 
  DropdownMenu, 
  DropdownMenuContent, 
  DropdownMenuItem, 
  DropdownMenuTrigger 
} from "@/Components/UI/ui/dropdown-menu";

const props = defineProps({
    lowongans: Array
});

const getStatusColor = (status) => {
    switch (status) {
        case 'aktif': return 'bg-green-50 text-green-600 border-green-100';
        case 'ditutup': return 'bg-red-50 text-red-600 border-red-100';
        case 'draft': return 'bg-slate-50 text-slate-500 border-slate-100';
        default: return 'bg-slate-50 text-slate-500 border-slate-100';
    }
};

const formatDate = (dateString) => {
  if (!dateString) return '-';
  return new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
};

const deleteLowongan = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus lowongan ini? Semua data lamaran terkait juga akan terhapus.')) {
        router.delete(route('perusahaan.lowongan.destroy', id));
    }
};

const toggleStatus = (id, currentStatus) => {
    if (currentStatus === 'aktif') {
        router.put(route('perusahaan.lowongan.tutup', id));
    } else {
        router.put(route('perusahaan.lowongan.perpanjang', id));
    }
};
</script>

<template>
    <ProfileLayout>
        <Head title="Kelola Lowongan" />

        <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-display font-bold text-slate-900 tracking-tight">Kelola Lowongan</h1>
                    <p class="text-slate-500 font-medium">Buat dan pantau lowongan kerja yang Anda publikasikan.</p>
                </div>
                <Link :href="route('perusahaan.lowongan.create')">
                    <Button class="rounded-2xl h-12 px-6 font-bold gap-2 shadow-lg shadow-primary/20">
                        <Plus class="w-5 h-5" />
                        Tambah Lowongan Baru
                    </Button>
                </Link>
            </div>

            <!-- Job List Container -->
            <div class="grid grid-cols-1 gap-6">
                <div v-if="lowongans.length > 0" class="space-y-4">
                    <Card v-for="job in lowongans" :key="job.id" class="border-border/60 shadow-sm rounded-3xl overflow-hidden hover:shadow-md transition-all group">
                        <CardContent class="p-0">
                            <div class="flex flex-col md:flex-row items-stretch md:items-center p-6 gap-6">
                                <!-- Job Icon/Initial -->
                                <div class="w-16 h-16 rounded-2xl bg-primary/5 flex items-center justify-center text-2xl font-bold text-primary shrink-0 border border-primary/10">
                                    {{ job.inisial }}
                                </div>

                                <!-- Job Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-1">
                                        <h3 class="text-xl font-display font-bold text-slate-900 truncate">{{ job.posisi }}</h3>
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border shrink-0" :class="getStatusColor(job.status)">
                                            {{ job.status }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm font-medium text-slate-500">
                                        <div class="flex items-center gap-1.5">
                                            <MapPin class="w-4 h-4 text-slate-400" />
                                            {{ job.kota }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <Clock class="w-4 h-4 text-slate-400" />
                                            {{ job.tipe }}
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <Users class="w-4 h-4 text-slate-400" />
                                            {{ job.lamarans_count }} Pelamar
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions Desktop -->
                                <div class="flex items-center gap-3 shrink-0">
                                    <Link :href="route('perusahaan.pelamar.index', job.id)">
                                        <Button variant="outline" class="rounded-xl font-bold border-slate-200 text-slate-600 hover:bg-slate-50 gap-2">
                                            <Eye class="w-4 h-4" />
                                            Lihat Kandidat
                                        </Button>
                                    </Link>
                                    
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button variant="ghost" size="icon" class="rounded-xl h-10 w-10 border border-slate-100">
                                                <MoreVertical class="w-5 h-5 text-slate-400" />
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end" class="w-48 rounded-2xl p-2 shadow-xl border-border/60">
                                            <DropdownMenuItem @click="router.get(route('perusahaan.lowongan.edit', job.id))" class="rounded-xl px-3 py-2 text-sm font-bold gap-3 text-slate-700">
                                                <Edit3 class="w-4 h-4" /> Edit Lowongan
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="toggleStatus(job.id, job.status)" class="rounded-xl px-3 py-2 text-sm font-bold gap-3 text-slate-700">
                                                <component :is="job.status === 'aktif' ? PauseCircle : PlayCircle" class="w-4 h-4" />
                                                {{ job.status === 'aktif' ? 'Tutup Lowongan' : 'Aktifkan Kembali' }}
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="deleteLowongan(job.id)" class="rounded-xl px-3 py-2 text-sm font-bold gap-3 text-red-500 focus:text-red-500 focus:bg-red-50">
                                                <Trash2 class="w-4 h-4" /> Hapus Permanen
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </div>
                            </div>
                            
                            <!-- Progress Bar for applications (optional design touch) -->
                            <div class="h-1 bg-slate-50 w-full overflow-hidden">
                                <div class="h-full bg-primary/20 transition-all duration-1000 origin-left" :style="{ width: Math.min(job.lamarans_count * 10, 100) + '%' }"></div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Empty State -->
                <div v-else class="py-20 flex flex-col items-center justify-center text-center max-w-lg mx-auto animate-in fade-in duration-700">
                    <img src="https://illustrations.popsy.co/amber/work-from-home.svg" alt="Empty Job" class="w-64 h-64 grayscale opacity-80 mb-8" />
                    <h3 class="text-2xl font-display font-bold text-slate-800 mb-3">Belum ada lowongan terbit</h3>
                    <p class="text-slate-500 font-medium mb-8">Mulailah merekrut tim impianmu dengan memposting lowongan kerja pertama Anda di platform Arsitek.</p>
                    <Link :href="route('perusahaan.lowongan.create')">
                        <Button class="rounded-2xl h-14 px-8 font-bold gap-2 shadow-xl shadow-primary/20">
                            <Plus class="w-6 h-6" />
                            Buat Lowongan Sekarang
                        </Button>
                    </Link>
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
