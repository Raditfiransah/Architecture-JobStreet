<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { 
  Briefcase, 
  Users, 
  UserCheck, 
  Clock, 
  ArrowRight,
  Plus,
  MoreVertical,
  CheckCircle2,
  XCircle,
  Clock3
} from 'lucide-vue-next';

const props = defineProps({
    stats: Object,
    recentApplications: Array,
    companyName: String
});

const getStatusColor = (status) => {
  const colors = {
    'pending': 'bg-amber-50 text-amber-600 border-amber-100',
    'reviewing': 'bg-blue-50 text-blue-600 border-blue-100',
    'shortlisted': 'bg-indigo-50 text-indigo-600 border-indigo-100',
    'interview': 'bg-purple-50 text-purple-600 border-purple-100',
    'rejected': 'bg-red-50 text-red-600 border-red-100',
    'accepted': 'bg-green-50 text-green-600 border-green-100',
  };
  return colors[status] || 'bg-slate-50 text-slate-600 border-slate-100';
};

const getStatusLabel = (status) => {
  const labels = {
    'pending': 'Menunggu',
    'reviewing': 'Ditinjau',
    'shortlisted': 'Shortlisted',
    'interview': 'Wawancara',
    'rejected': 'Ditolak',
    'accepted': 'Diterima',
  };
  return labels[status] || status;
};

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'short',
    year: 'numeric'
  });
};
</script>

<template>
    <ProfileLayout>
        <Head title="Hiring Dashboard" />

        <div class="space-y-8 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <!-- Welcome Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-display font-bold text-slate-900 tracking-tight">Hiring Dashboard</h1>
                    <p class="text-slate-500 font-medium">Selamat datang, {{ companyName }}. Pantau progres rekrutmen Anda.</p>
                </div>
                <Link :href="route('perusahaan.lowongan.create')">
                    <Button class="rounded-2xl h-12 px-6 font-bold gap-2 shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95">
                        <Plus class="w-5 h-5" />
                        Buat Lowongan
                    </Button>
                </Link>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden hover:shadow-md transition-shadow">
                    <CardContent class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center">
                                <Briefcase class="w-6 h-6 text-primary" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Lowongan Aktif</p>
                                <h3 class="text-2xl font-display font-bold text-slate-900">{{ stats.lowongan_aktif }} / {{ stats.total_lowongan }}</h3>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden hover:shadow-md transition-shadow">
                    <CardContent class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center">
                                <Users class="w-6 h-6 text-blue-500" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Pelamar</p>
                                <h3 class="text-2xl font-display font-bold text-slate-900">{{ stats.total_pelamar }}</h3>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden hover:shadow-md transition-shadow">
                    <CardContent class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 flex items-center justify-center">
                                <UserCheck class="w-6 h-6 text-indigo-500" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Shortlisted</p>
                                <h3 class="text-2xl font-display font-bold text-slate-900">{{ stats.shortlisted }}</h3>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card class="border-border/60 shadow-sm rounded-3xl overflow-hidden hover:shadow-md transition-shadow">
                    <CardContent class="p-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-amber-50 flex items-center justify-center">
                                <Clock3 class="w-6 h-6 text-amber-500" />
                            </div>
                            <div>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perlu Ditinjau</p>
                                <h3 class="text-2xl font-display font-bold text-slate-900">{{ stats.total_pelamar - stats.shortlisted }}</h3>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Recent Applications -->
                <Card class="lg:col-span-2 border-border/60 shadow-sm rounded-[32px] overflow-hidden">
                    <CardHeader class="px-8 pt-8 pb-4 flex flex-row items-center justify-between">
                        <div>
                            <CardTitle class="text-xl font-display font-bold">Kandidat Terbaru</CardTitle>
                            <CardDescription>Daftar pelamar terbaru yang masuk.</CardDescription>
                        </div>
                        <Link :href="route('perusahaan.lowongan.index')" class="text-sm font-bold text-primary hover:underline">
                            Lihat Semua
                        </Link>
                    </CardHeader>
                    <CardContent class="px-8 pb-8">
                        <div v-if="recentApplications.length > 0" class="divide-y divide-slate-100">
                            <div v-for="app in recentApplications" :key="app.id" class="py-4 flex items-center justify-between group">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-400 group-hover:bg-primary/5 group-hover:text-primary transition-colors">
                                        {{ app.user.name.substring(0, 2).toUpperCase() }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-slate-900 leading-none mb-1 group-hover:text-primary transition-colors">{{ app.user.name }}</h4>
                                        <p class="text-xs text-slate-500 font-medium">Melamar: <span class="text-slate-700">{{ app.lowongan.posisi }}</span></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border" :class="getStatusColor(app.status)">
                                        {{ getStatusLabel(app.status) }}
                                    </span>
                                    <Link :href="route('perusahaan.pelamar.show', { id: app.lowongan_id, appId: app.id })">
                                        <Button variant="ghost" size="icon" class="rounded-xl h-10 w-10 hover:bg-slate-50">
                                            <ChevronRight class="w-5 h-5 text-slate-400" />
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                        <div v-else class="py-12 flex flex-col items-center justify-center text-center">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <Users class="w-8 h-8 text-slate-300" />
                            </div>
                            <h4 class="font-bold text-slate-800">Belum ada pelamar</h4>
                            <p class="text-sm text-slate-500">Iklankan lowongan Anda untuk mulai menarik bakat terbaik.</p>
                        </div>
                    </CardContent>
                </Card>

                <!-- Quick Tips -->
                <div class="space-y-6">
                    <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden bg-primary text-white">
                        <CardContent class="p-8">
                            <div class="w-14 h-14 rounded-2xl bg-white/20 flex items-center justify-center mb-6 backdrop-blur-sm">
                                <CheckCircle2 class="w-8 h-8 text-white" />
                            </div>
                            <h3 class="text-xl font-display font-bold mb-3">Tingkatkan Kualitas Iklan</h3>
                            <p class="text-white/80 text-sm leading-relaxed mb-6">Informasi gaji dan deskripsi yang jelas meningkatkan jumlah pelamar hingga 40%.</p>
                            <Button variant="secondary" class="w-full rounded-2xl font-bold bg-white text-primary hover:bg-slate-50">
                                Pelajari Tips
                            </Button>
                        </CardContent>
                    </Card>

                    <Card class="border-border/60 shadow-sm rounded-[24px] overflow-hidden">
                        <CardContent class="p-6 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center shrink-0">
                                <Clock class="w-6 h-6 text-slate-400" />
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-sm text-slate-900">Butuh Bantuan?</h4>
                                <p class="text-xs text-slate-500 truncate">Tim support kami siap membantu rekrutmen Anda.</p>
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
