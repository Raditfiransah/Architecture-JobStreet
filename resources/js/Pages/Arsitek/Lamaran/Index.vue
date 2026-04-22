<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Card, CardContent } from "@/Components/UI/ui/card";

const props = defineProps({
  lamarans: Array
});

const activeTab = ref('applied'); // Set default to 'applied' to show data

const tabs = [
  { id: 'applied', label: 'Terkirim' },
  { id: 'saved', label: 'Tersimpan' },
  { id: 'alerts', label: 'Notifikasi' },
];

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
    'shortlisted': 'Lolos Seleksi',
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
    <Head title="Aktivitas Lamaran" />

    <Card class="border-border/60 shadow-[0_2px_12px_rgba(0,0,0,0.03)] overflow-hidden rounded-[32px] bg-white min-h-[600px] flex flex-col">
      <!-- Header Section -->
      <div class="px-8 pt-10 pb-6 flex items-start justify-between">
        <div>
           <h1 class="text-[32px] font-display font-bold text-slate-900 tracking-tight leading-none mb-2">Aktivitas Lamaran</h1>
           <p class="text-slate-500 font-medium">Pantau status lamaran kerja Anda secara real-time.</p>
        </div>
        
        <!-- Abstract Doodle Illustration -->
        <div class="hidden md:block w-40 h-32 opacity-80 mix-blend-multiply mr-4">
           <img src="https://illustrations.popsy.co/amber/freelancer.svg" alt="Activity Illustration" class="w-full h-full object-contain grayscale opacity-80" />
        </div>
      </div>

      <!-- Custom Tabs -->
      <div class="px-8 border-b border-slate-100">
        <nav class="flex items-center gap-10">
          <button 
            v-for="tab in tabs" 
            :key="tab.id"
            @click="activeTab = tab.id"
            class="pb-5 relative text-sm font-bold transition-all outline-none"
            :class="activeTab === tab.id ? 'text-primary' : 'text-slate-400 hover:text-slate-600'"
          >
            {{ tab.label }}
            <span v-if="tab.id === 'applied' && lamarans.length > 0" class="ml-2 px-1.5 py-0.5 rounded-md bg-primary/10 text-[10px]">{{ lamarans.length }}</span>
            <!-- Active Indicator -->
            <div 
              v-if="activeTab === tab.id"
              class="absolute bottom-0 left-0 w-full h-[4px] bg-primary rounded-t-full shadow-[0_-2px_8px_rgba(var(--primary),0.3)]"
            ></div>
          </button>
        </nav>
      </div>

      <!-- Content Area -->
      <CardContent class="p-8 flex-1 flex flex-col min-h-[400px]">
        <!-- List of Applied Jobs -->
        <div v-if="activeTab === 'applied'" class="w-full space-y-4">
           <div v-if="lamarans.length > 0" class="divide-y divide-slate-50">
              <div v-for="item in lamarans" :key="item.id" class="py-6 first:pt-0 group hover:bg-slate-50/50 rounded-3xl transition-colors px-4 -mx-4 flex flex-col md:flex-row md:items-center justify-between gap-6">
                 <div class="flex items-center gap-5">
                    <!-- Icon / Company Initial -->
                    <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center text-xl font-bold text-primary shrink-0">
                       {{ item.lowongan.inisial }}
                    </div>
                    <div>
                       <h3 class="font-display font-bold text-slate-900 group-hover:text-primary transition-colors leading-tight mb-1">{{ item.lowongan.posisi }}</h3>
                       <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-sm font-medium text-slate-500">
                          <span class="text-slate-900">{{ item.lowongan.perusahaan }}</span>
                          <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                          <span>{{ item.lowongan.kota }}</span>
                          <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                          <span class="text-slate-400">Melamar: {{ formatDate(item.applied_at) }}</span>
                       </div>
                    </div>
                 </div>

                 <div class="flex items-center justify-between md:justify-end gap-6 shrink-0">
                    <div class="flex flex-col items-end gap-1">
                       <span class="px-4 py-1.5 rounded-full text-xs font-bold border" :class="getStatusColor(item.status)">
                          {{ getStatusLabel(item.status) }}
                       </span>
                    </div>
                    <Link :href="route('arsitek.lamaran.show', item.id)" class="p-3 rounded-xl border border-slate-200 text-slate-400 hover:text-primary hover:border-primary/50 hover:bg-primary/5 transition-all">
                       <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </Link>
                 </div>
              </div>
           </div>

           <!-- Empty State for Applied -->
           <div v-else class="flex flex-col items-center justify-center text-center max-w-md mx-auto py-12 animate-in fade-in duration-500">
              <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mb-6">
                <svg class="w-10 h-10 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
              </div>
             <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Belum ada lamaran terkirim</h3>
             <p class="text-sm font-medium text-slate-500 mb-4">
               Perjalanan karir Anda dimulai dengan satu langkah melamar.
             </p>
             <Link :href="route('lowongan.index')" class="text-sm font-bold text-primary hover:underline transition-colors">
               Mulai melamar sekarang
             </Link>
           </div>
        </div>

        <!-- Empty State for Saved Jobs -->
        <div v-if="activeTab === 'saved'" class="flex flex-col items-center justify-center text-center max-w-md mx-auto py-12 animate-in fade-in duration-500">
          <img 
            src="https://illustrations.popsy.co/amber/cat-typing.svg" 
            alt="No Saved Jobs" 
            class="w-64 h-64 mb-6 grayscale opacity-90 mix-blend-darken"
          />
          <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Belum ada lowongan tersimpan</h3>
          <p class="text-[15px] font-medium text-slate-900 mb-4">
            Cari dari ribuan posisi terbaik dan simpan sebagai favoritmu.
          </p>
          <Link :href="route('lowongan.index')" class="text-[15px] font-bold text-primary hover:underline hover:text-primary/80 transition-colors">
            Cari lowongan untuk Anda
          </Link>
        </div>

        <!-- Placeholder for Alerts -->
        <div v-if="activeTab === 'alerts'" class="flex flex-col items-center justify-center text-center max-w-md mx-auto py-12 animate-in fade-in duration-500">
           <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mb-6">
             <svg class="w-10 h-10 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
           </div>
          <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Tidak ada notifikasi baru</h3>
          <p class="text-sm font-medium text-slate-500">
            Anda akan menerima notifikasi terbaru mengenai seleksi Anda di sini.
          </p>
        </div>
      </CardContent>
    </Card>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
