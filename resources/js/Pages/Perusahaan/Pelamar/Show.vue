<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";
import { 
  ArrowLeft, 
  MapPin, 
  Mail, 
  Phone, 
  FileText, 
  ExternalLink,
  Download,
  MessageSquare,
  CheckCircle2,
  XCircle,
  Clock,
  Briefcase,
  GraduationCap,
  Calendar
} from 'lucide-vue-next';

const props = defineProps({
    lamaran: Object
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
    'pending': 'Dalam Antrian',
    'reviewing': 'Sedang Ditinjau',
    'shortlisted': 'Shortlisted',
    'interview': 'Wawancara',
    'rejected': 'Ditolak',
    'accepted': 'Diterima',
  };
  return labels[status] || status;
};

const updateStatus = (newStatus) => {
    router.put(route('perusahaan.lamaran.status', props.lamaran.id), {
        status: newStatus
    });
};
</script>

<template>
    <ProfileLayout>
        <Head :title="'Review Kandidat - ' + lamaran.user.name" />

        <div class="max-w-5xl mx-auto space-y-8 animate-in slide-in-from-bottom-4 duration-500 pb-20">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="space-y-4">
                    <Link :href="route('perusahaan.pelamar.index', lamaran.lowongan_id)" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
                        <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                        Kembali ke Daftar Pelamar
                    </Link>
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="w-16 h-16 sm:w-24 sm:h-24 rounded-2xl sm:rounded-3xl bg-primary/5 border border-primary/10 flex items-center justify-center text-xl sm:text-3xl font-bold text-primary shadow-sm shrink-0">
                            {{ lamaran.user.name.substring(0, 2).toUpperCase() }}
                        </div>
                        <div class="space-y-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                <h1 class="text-xl sm:text-3xl font-display font-bold text-slate-900 tracking-tight">{{ lamaran.user.name }}</h1>
                                <Badge variant="outline" :class="getStatusColor(lamaran.status) + ' rounded-lg border px-3 py-1 font-bold uppercase tracking-wider text-[10px]'">
                                    {{ getStatusLabel(lamaran.status) }}
                                </Badge>
                            </div>
                            <p class="text-sm sm:text-lg font-medium text-slate-600">{{ lamaran.user.arsitek_profile?.status_pekerjaan || 'Arsitek Profesional' }}</p>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs sm:text-sm font-medium text-slate-400">
                                <div class="flex items-center gap-1.5"><MapPin class="w-3.5 h-3.5" /> {{ lamaran.user.location || 'Lokasi tidak diatur' }}</div>
                                <div class="flex items-center gap-1.5"><Mail class="w-3.5 h-3.5" /> {{ lamaran.user.email }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 shrink-0 sm:pt-8">
                    <Button variant="outline" class="rounded-xl font-bold gap-2">
                        <MessageSquare class="w-4 h-4" /> Hubungi
                    </Button>
                    <Button @click="updateStatus('shortlisted')" v-if="lamaran.status !== 'shortlisted' && lamaran.status !== 'accepted'" class="rounded-xl font-bold bg-indigo-600 hover:bg-indigo-700">
                        Shortlist
                    </Button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Details -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- About / Experience -->
                    <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden">
                        <CardHeader>
                            <CardTitle class="text-xl font-display font-bold">Tentang Kandidat</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-6">
                            <p class="text-slate-600 leading-relaxed">
                                {{ lamaran.user.arsitek_profile?.biodata || 'Tidak ada deskripsi profil.' }}
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-slate-50">
                                <div class="space-y-4">
                                    <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                        <GraduationCap class="w-4 h-4 text-primary" /> Pendidikan
                                    </h4>
                                    <div class="pl-6 space-y-1 border-l-2 border-slate-100">
                                        <p class="text-sm font-bold text-slate-800">{{ lamaran.user.arsitek_profile?.education_institution || '-' }}</p>
                                        <p class="text-xs text-slate-500">{{ lamaran.user.arsitek_profile?.education_degree || '-' }}</p>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                                        <Briefcase class="w-4 h-4 text-primary" /> Pengalaman
                                    </h4>
                                    <div class="pl-6 space-y-1 border-l-2 border-slate-100">
                                        <p class="text-sm font-bold text-slate-800">{{ lamaran.user.arsitek_profile?.years_of_experience || 0 }} Tahun Pengalaman</p>
                                        <p class="text-xs text-slate-500">Keahlian: {{ lamaran.user.arsitek_profile?.specialization || '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Application Attachments -->
                    <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden bg-slate-50/50">
                        <CardHeader>
                            <CardTitle class="text-xl font-display font-bold">Dokumen & Portfolio</CardTitle>
                            <CardDescription>Review lampiran yang dikirimkan oleh kandidat.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <a :href="'/storage/' + lamaran.cv_path" target="_blank" v-if="lamaran.cv_path" class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-primary/50 transition-all flex items-center justify-between group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                                            <FileText class="w-6 h-6 text-red-500" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 tracking-tight">Curriculum Vitae</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">PDF Document</p>
                                        </div>
                                    </div>
                                    <Download class="w-5 h-5 text-slate-300 group-hover:text-primary transition-colors" />
                                </a>

                                <a :href="lamaran.portfolio_path" target="_blank" v-if="lamaran.portfolio_path" class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-primary/50 transition-all flex items-center justify-between group">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                                            <ExternalLink class="w-6 h-6 text-blue-500" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 tracking-tight">Portfolio Link</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">External URL</p>
                                        </div>
                                    </div>
                                    <ChevronRight class="w-5 h-5 text-slate-300 group-hover:text-primary transition-colors" />
                                </a>
                            </div>

                            <div v-if="lamaran.notes" class="mt-6 p-6 rounded-2xl bg-white border border-slate-100 italic text-sm text-slate-500 leading-relaxed shadow-sm">
                                " {{ lamaran.notes }} "
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Right Column: Status & Timeline -->
                <div class="space-y-6">
                    <!-- Status Actions Card -->
                    <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden">
                        <CardHeader>
                            <CardTitle class="text-lg font-display font-bold">Update Status</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <Button @click="updateStatus('reviewing')" variant="outline" class="w-full justify-start rounded-xl font-bold border-slate-100 hover:bg-blue-50 hover:text-blue-600 gap-3">
                                <Clock class="w-4 h-4" /> Sedang Ditinjau
                            </Button>
                            <Button @click="updateStatus('interview')" variant="outline" class="w-full justify-start rounded-xl font-bold border-slate-100 hover:bg-purple-50 hover:text-purple-600 gap-3">
                                <MessageSquare class="w-4 h-4" /> Wawancara
                            </Button>
                            <Button @click="updateStatus('accepted')" variant="outline" class="w-full justify-start rounded-xl font-bold border-slate-100 hover:bg-green-50 hover:text-green-600 gap-3">
                                <CheckCircle2 class="w-4 h-4" /> Terima Kandidat
                            </Button>
                            <Button @click="updateStatus('rejected')" variant="outline" class="w-full justify-start rounded-xl font-bold border-slate-100 hover:bg-red-50 hover:text-red-600 gap-3">
                                <XCircle class="w-4 h-4" /> Tolak Lambda
                            </Button>
                        </CardContent>
                    </Card>

                    <!-- Info Card -->
                    <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden bg-primary/5 border-primary/10">
                        <CardContent class="p-6">
                            <h4 class="text-sm font-bold text-primary flex items-center gap-2 mb-3 tracking-tight">
                                <Calendar class="w-4 h-4" /> Timeline Aplikasi
                            </h4>
                            <div class="space-y-4">
                                <div class="flex gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-primary mt-1.5"></div>
                                    <div class="text-xs">
                                        <p class="font-bold text-slate-800">Lamaran Dikirim</p>
                                        <p class="text-slate-500">{{ new Date(lamaran.applied_at).toLocaleString() }}</p>
                                    </div>
                                </div>
                                <div v-if="lamaran.updated_at !== lamaran.applied_at" class="flex gap-3">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300 mt-1.5"></div>
                                    <div class="text-xs">
                                        <p class="font-bold text-slate-800">Update Terakhir</p>
                                        <p class="text-slate-500">{{ new Date(lamaran.updated_at).toLocaleString() }}</p>
                                    </div>
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
