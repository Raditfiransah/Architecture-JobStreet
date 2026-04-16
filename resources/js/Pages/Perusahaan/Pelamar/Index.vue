<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { 
  ArrowLeft, 
  MapPin, 
  Mail, 
  Phone, 
  FileText, 
  ChevronRight,
  UserCheck,
  XCircle,
  MessageSquare,
  Star
} from 'lucide-vue-next';
import { 
  Tabs, 
  TabsContent, 
  TabsList, 
  TabsTrigger 
} from "@/Components/UI/ui/tabs";

const props = defineProps({
    lowongan: Object,
    lamarans: Array
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
    'pending': 'Tinjau',
    'reviewing': 'Sedang Ditinjau',
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

const updateStatus = (appId, newStatus) => {
    router.put(route('perusahaan.lamaran.status', appId), {
        status: newStatus
    });
};
</script>

<template>
    <ProfileLayout>
        <Head :title="'Kandidat - ' + lowongan.posisi" />

        <div class="space-y-8 animate-in slide-in-from-bottom-4 duration-500">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                <div class="space-y-4">
                    <Link :href="route('perusahaan.lowongan.index')" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-primary transition-colors gap-2 group">
                        <ArrowLeft class="w-4 h-4 group-hover:-translate-x-1 transition-transform" />
                        Kembali ke Lowongan
                    </Link>
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-3xl font-display font-bold text-slate-900 tracking-tight">{{ lowongan.posisi }}</h1>
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-slate-100 text-slate-600 border border-slate-200">
                                {{ lowongan.lamarans.length }} Pelamar
                            </span>
                        </div>
                        <p class="text-slate-500 font-medium flex items-center gap-2">
                            <MapPin class="w-4 h-4" /> {{ lowongan.kota }}
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            {{ lowongan.tipe }}
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 shrink-0">
                    <Link :href="route('perusahaan.lowongan.edit', lowongan.id)">
                        <Button variant="outline" class="rounded-xl font-bold border-slate-200 text-slate-600 hover:bg-slate-50">
                            Edit Lowongan
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Main Content -->
            <Card class="border-border/60 shadow-sm rounded-[32px] overflow-hidden min-h-[500px]">
                <CardHeader class="px-8 pt-8 pb-0">
                    <CardTitle class="text-xl font-display font-bold">Daftar Kandidat</CardTitle>
                    <CardDescription>Kelola dan tindak lanjuti setiap aplikasi yang masuk.</CardDescription>
                </CardHeader>
                <CardContent class="p-0">
                    <Tabs default-value="all" class="w-full">
                        <div class="px-8 border-b border-slate-100 mt-6">
                            <TabsList class="bg-transparent h-auto p-0 gap-8">
                                <TabsTrigger value="all" class="bg-transparent border-b-2 border-transparent rounded-none data-[state=active]:border-primary data-[state=active]:text-primary data-[state=active]:shadow-none px-0 pb-4 text-sm font-bold text-slate-400">
                                    Semua Pelamar
                                </TabsTrigger>
                                <TabsTrigger value="shortlisted" class="bg-transparent border-b-2 border-transparent rounded-none data-[state=active]:border-primary data-[state=active]:text-primary data-[state=active]:shadow-none px-0 pb-4 text-sm font-bold text-slate-400">
                                    Shortlisted
                                </TabsTrigger>
                            </TabsList>
                        </div>

                        <TabsContent value="all" class="m-0">
                            <div v-if="lamarans.length > 0" class="divide-y divide-slate-50">
                                <div v-for="app in lamarans" :key="app.id" class="px-8 py-6 hover:bg-slate-50/50 transition-colors group">
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                        <div class="flex items-center gap-5">
                                            <!-- Candidate Avatar Placeholder -->
                                            <div class="w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-xl font-bold text-slate-400 group-hover:text-primary transition-colors">
                                                {{ app.user.name.substring(0, 2).toUpperCase() }}
                                            </div>
                                            <div>
                                                <h3 class="font-display font-bold text-lg text-slate-900 leading-tight mb-1 group-hover:text-primary transition-colors">{{ app.user.name }}</h3>
                                                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm font-medium text-slate-500">
                                                    <span class="text-slate-400">Dilamar: {{ formatDate(app.applied_at) }}</span>
                                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                                    <span class="px-3 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider border" :class="getStatusColor(app.status)">
                                                        {{ getStatusLabel(app.status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-3 shrink-0">
                                            <Link :href="route('perusahaan.pelamar.show', { id: lowongan.id, appId: app.id })">
                                                <Button variant="outline" class="rounded-xl font-bold border-slate-200 text-slate-600 hover:bg-slate-50 gap-2">
                                                    Review Profil
                                                    <ChevronRight class="w-4 h-4" />
                                                </Button>
                                            </Link>
                                            <Button 
                                                v-if="app.status === 'pending' || app.status === 'reviewing'"
                                                @click="updateStatus(app.id, 'shortlisted')"
                                                variant="secondary" 
                                                class="rounded-xl font-bold bg-indigo-50 text-indigo-600 hover:bg-indigo-100 border-transparent gap-2"
                                            >
                                                <UserCheck class="w-4 h-4" />
                                                Shortlist
                                            </Button>
                                            <Button v-else-if="app.status === 'shortlisted'" variant="secondary" class="rounded-xl font-bold bg-green-50 text-green-600 hover:bg-green-100 border-transparent gap-2">
                                                <Star class="w-4 h-4 fill-current" />
                                                Shortlisted
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Empty State -->
                            <div v-else class="py-24 flex flex-col items-center justify-center text-center max-w-sm mx-auto">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6">
                                    <Users class="w-10 h-10 text-slate-200" />
                                </div>
                                <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Belum ada pelamar</h3>
                                <p class="text-sm font-medium text-slate-500">Data pelamar akan muncul di sini segera setelah ada yang mengirimkan lamaran.</p>
                            </div>
                        </TabsContent>

                        <TabsContent value="shortlisted" class="m-0">
                            <!-- Similar list but filtered by shortlisted status -->
                            <div v-if="lamarans.filter(a => a.status === 'shortlisted').length > 0" class="divide-y divide-slate-50">
                                <div v-for="app in lamarans.filter(a => a.status === 'shortlisted')" :key="app.id" class="px-8 py-6 hover:bg-slate-50/50 transition-colors group">
                                    <!-- Same row content as above -->
                                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                                        <div class="flex items-center gap-5">
                                            <div class="w-16 h-16 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-xl font-bold text-indigo-500">
                                                {{ app.user.name.substring(0, 2).toUpperCase() }}
                                            </div>
                                            <div>
                                                <h3 class="font-display font-bold text-lg text-slate-900 group-hover:text-primary transition-colors leading-tight mb-1">{{ app.user.name }}</h3>
                                                <div class="flex items-center gap-3">
                                                    <span class="text-xs font-bold text-indigo-500 uppercase tracking-widest">Kandidat Pilihan</span>
                                                    <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                                    <span class="text-xs text-slate-400 font-medium">Applied: {{ formatDate(app.applied_at) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-3">
                                            <Link :href="route('perusahaan.pelamar.show', { id: lowongan.id, appId: app.id })">
                                                <Button variant="outline" class="rounded-xl font-bold border-slate-200 text-slate-600 hover:bg-slate-50">
                                                    Lihat Detail
                                                </Button>
                                            </Link>
                                            <Button class="rounded-xl font-bold gap-2">
                                                <MessageSquare class="w-4 h-4" />
                                                Hubungi
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="py-24 flex flex-col items-center justify-center text-center max-w-sm mx-auto">
                                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-6">
                                    <Star class="w-10 h-10 text-indigo-200" />
                                </div>
                                <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Tidak ada kandidat shortlisted</h3>
                                <p class="text-sm font-medium text-slate-500">Tandai kandidat terbaik Anda untuk melihatnya di sini.</p>
                            </div>
                        </TabsContent>
                    </Tabs>
                </CardContent>
            </Card>
        </div>
    </ProfileLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Outfit', sans-serif;
}
</style>
