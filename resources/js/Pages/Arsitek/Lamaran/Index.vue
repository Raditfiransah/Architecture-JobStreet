<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Card, CardContent } from "@/Components/UI/ui/card";

const activeTab = ref('saved');

const tabs = [
  { id: 'saved', label: 'Tersimpan' },
  { id: 'alerts', label: 'Notifikasi' },
  { id: 'applied', label: 'Terkirim' },
];
</script>

<template>
  <ProfileLayout>
    <Head title="Aktivitas Lamaran" />

    <Card class="border-border/60 shadow-[0_2px_4px_rgba(0,0,0,0.02)] overflow-hidden rounded-2xl bg-white min-h-[600px] flex flex-col">
      <!-- Header Section -->
      <div class="px-8 pt-8 pb-4 flex items-center justify-between">
        <h1 class="text-[28px] font-display font-bold text-slate-800 tracking-tight">Aktivitas Lamaran</h1>
        
        <!-- Abstract Doodle Illustration -->
        <div class="hidden md:block w-48 h-40 opacity-80 mix-blend-multiply mr-4">
           <!-- Using popsy or similar as placeholder for the sketch illustration -->
           <img src="https://illustrations.popsy.co/amber/freelancer.svg" alt="Activity Illustration" class="w-full h-full object-contain grayscale opacity-80" />
        </div>
      </div>

      <!-- Custom Tabs -->
      <div class="px-8 border-b border-border/50">
        <nav class="flex items-center gap-8">
          <button 
            v-for="tab in tabs" 
            :key="tab.id"
            @click="activeTab = tab.id"
            class="pb-4 relative text-sm font-semibold transition-colors outline-none"
            :class="activeTab === tab.id ? 'text-foreground' : 'text-slate-500 hover:text-slate-700'"
          >
            {{ tab.label }}
            <!-- Active Indicator -->
            <div 
              v-if="activeTab === tab.id"
              class="absolute bottom-0 left-0 w-full h-[3px] bg-primary rounded-t-full"
            ></div>
          </button>
        </nav>
      </div>

      <!-- Content Area -->
      <CardContent class="p-0 flex-1 flex flex-col items-center justify-center min-h-[400px]">
        <!-- Empty State for Saved Jobs -->
        <div v-if="activeTab === 'saved'" class="flex flex-col items-center justify-center text-center max-w-md p-8 animate-in fade-in duration-500">
          <img 
            src="https://illustrations.popsy.co/amber/cat-typing.svg" 
            alt="No Saved Jobs" 
            class="w-64 h-64 mb-6 grayscale opacity-90 mix-blend-darken"
          />
          <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Belum ada lowongan tersimpan</h3>
          <p class="text-[15px] font-medium text-slate-900 mb-2">
            Cari dari ribuan posisi terbaik dan simpan sebagai favoritmu.
          </p>
          <Link :href="route('lowongan.index')" class="text-[15px] font-bold text-primary hover:underline hover:text-primary/80 transition-colors">
            Cari lowongan untuk Anda
          </Link>
        </div>

        <!-- Placeholder for Alerts -->
        <div v-if="activeTab === 'alerts'" class="flex flex-col items-center justify-center text-center max-w-md p-8 animate-in fade-in duration-500">
           <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mb-6">
             <svg class="w-10 h-10 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
           </div>
          <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Tidak ada notifikasi baru</h3>
          <p class="text-sm font-medium text-slate-500">
            Anda akan menerima notifikasi terbaru mengenai seleksi Anda di sini.
          </p>
        </div>

        <!-- Placeholder for Applied -->
        <div v-if="activeTab === 'applied'" class="flex flex-col items-center justify-center text-center max-w-md p-8 animate-in fade-in duration-500">
           <div class="w-20 h-20 bg-primary/5 rounded-full flex items-center justify-center mb-6">
             <svg class="w-10 h-10 text-primary/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
           </div>
          <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Belum ada lamaran terkirim</h3>
          <p class="text-sm font-medium text-slate-500 mb-3">
            Perjalanan karir Anda dimulai dengan satu langkah melamar.
          </p>
          <Link :href="route('lowongan.index')" class="text-sm font-bold text-primary hover:underline transition-colors">
            Mulai melamar sekarang
          </Link>
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
