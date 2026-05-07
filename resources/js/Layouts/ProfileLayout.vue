<script setup>
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
  User, 
  Briefcase, 
  Settings, 
  Bell, 
  LogOut, 
  MapPin, 
  GraduationCap, 
  FileText, 
  Target, 
  Activity,
  Edit2,
  ChevronRight,
  HelpCircle,
  Menu,
  History,
  Building2,
  MessageSquare,
  LayoutDashboard
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import { Card, CardContent } from "@/Components/UI/ui/card";
import { Separator } from "@/Components/UI/ui/separator";
import Navbar from "@/Components/Public/Navbar.vue";
import VerificationBadge from '@/Components/Profile/VerificationBadge.vue';

const page = usePage();
const user = computed(() => page.props.auth.user || {});
const profile = computed(() => {
  if (user.value.role === 'arsitek') return user.value.arsitek_profile || {};
  if (user.value.role === 'perusahaan') return user.value.company_profile || {};
  if (user.value.role === 'client') return user.value.client_profile || {};
  return {};
});

const verificationStatus = computed(() => profile.value?.verification_status || 'unverified');
const verificationNote = computed(() => profile.value?.verification_note || null);

const userInitials = computed(() => {
  if (!user.value?.name) return 'U';
  return user.value.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
});

const jobTitle = computed(() => {
  if (user.value.role === 'arsitek') {
    if (profile.value.is_student) return `Student at ${profile.value.education_institution || 'Unspecified'}`;
    return profile.value.status_pekerjaan || 'Arsitek Profesional';
  } else if (user.value.role === 'perusahaan') {
    return profile.value.industry || 'Perusahaan';
  } else if (user.value.role === 'client') {
    return profile.value.client_type || 'Client';
  } else if (user.value.role === 'admin') {
    return user.value.location || 'Administrator'; // Using location field for jabatan
  }
  return 'Pengguna';
});

const locationText = computed(() => {
  return profile.value.location || user.value.location || 'Lokasi belum diatur';
});

const editRoute = computed(() => {
  if (user.value.role === 'client') return route('client.profil.edit'); 
  return route(user.value.role + '.profil.edit');
});

const menuItems = computed(() => {
  const role = user.value?.role;
  
  if (role === 'arsitek') {
    return [
      { group: 'Utama', items: [
        { label: 'Dashboard', icon: LayoutDashboard, href: route('arsitek.dashboard') },
      ]},
      { group: 'Kelola Portofolio & Lamaran', items: [
        { label: 'Portofolio', icon: Briefcase, href: route('arsitek.portofolio.index') },
        { label: 'Aktivitas Lamaran', icon: History, href: route('arsitek.lamaran.index') },
      ]},
      { group: 'Mengelola Akun', items: [
        { label: 'Pengaturan Akun', icon: Settings, href: route('arsitek.pengaturan.index') },
      ]}
    ];
  }
  
  if (role === 'perusahaan') {
    return [
      { group: 'Utama', items: [
        { label: 'Dashboard', icon: LayoutDashboard, href: route('perusahaan.dashboard') },
      ]},
      { group: 'Manajemen Rekrutmen', items: [
        { label: 'Kelola Lowongan', icon: Briefcase, href: route('perusahaan.lowongan.index') },
        { label: 'Kandidat', icon: Target, href: route('perusahaan.pelamar.all') },
      ]},
      { group: 'Mengelola Akun', items: [
        { label: 'Pengaturan Akun', icon: Settings, href: route('perusahaan.pengaturan.index') },
      ]}
    ];
  }
  
  if (role === 'client') {
    return [
      { group: 'Utama', items: [
        { label: 'Dashboard', icon: LayoutDashboard, href: route('client.dashboard') },
      ]},
      { group: 'Kelola Proyek', items: [
        { label: 'Proyek', icon: Briefcase, href: route('client.proyek.index') },
      ]},
      { group: 'Mengelola Akun', items: [
        { label: 'Pengaturan Akun', icon: Settings, href: route('client.pengaturan.index') },
      ]}
    ];
  }

  return [];
});

const form = useForm({});

const logout = () => {
    form.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-[#F3F6F9] font-sans antialiased text-[#1E293B] selection:bg-primary/20 selection:text-primary">
    <Navbar />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <div class="flex flex-col lg:flex-row gap-8 items-start">
        
         <!-- Sidebar (1/3) -->
         <aside class="w-full lg:w-[320px] shrink-0 space-y-6">

           <Card class="border-border/60 shadow-[0_2px_4px_rgba(0,0,0,0.02)] overflow-hidden rounded-2xl">
             <CardContent class="p-5">
               <div class="flex items-start justify-between">
                 <div class="flex items-center gap-4">
                   <Avatar class="h-14 w-14 rounded-xl border border-border shadow-sm shrink-0">
                     <AvatarImage :src="user?.avatar_url" />
                     <AvatarFallback class="bg-primary/5 text-primary font-bold text-lg">
                       {{ userInitials }}
                     </AvatarFallback>
                   </Avatar>
                   <div class="min-w-0">
                     <h2 class="font-display font-bold text-lg leading-tight truncate text-[#1E293B]" :title="user?.name">{{ user?.name }}</h2>
                     <p class="text-[11px] text-[#64748B] mt-0.5 leading-relaxed truncate">
                       {{ jobTitle }}
                     </p>
                     <!-- Detail Info Mobile -->
                    <div class="mt-4 flex flex-col gap-2 md:hidden">
                      <div v-if="user.role !== 'client' && user.role !== 'admin'" class="flex items-center gap-2 overflow-hidden">
                        <VerificationBadge :status="verificationStatus" :note="verificationNote" />
                      </div>
                      
                      <div class="flex items-center gap-2 text-slate-600 text-sm overflow-hidden">
                        <MapPin class="w-4 h-4 text-slate-400 shrink-0" />
                        <span class="truncate block w-full" :title="user.location || profile.location || 'Lokasi belum diatur'">
                            {{ user.location || profile.location || 'Lokasi belum diatur' }}
                        </span>
                      </div>
                    </div>
                   </div>
                 </div>
                 <Link :href="editRoute">
                   <Button variant="ghost" size="icon" class="h-7 w-7 rounded-lg hover:bg-slate-100 shrink-0">
                     <Edit2 class="w-3.5 h-3.5 text-[#64748B]" />
                   </Button>
                 </Link>
               </div>

                <!-- Action Button & Detail Desktop -->
                <div class="hidden md:flex flex-col items-end gap-3 shrink-0 mt-4">
                  <div v-if="user.role !== 'client' && user.role !== 'admin'" class="flex items-center justify-end w-full">
                    <VerificationBadge :status="verificationStatus" :note="verificationNote" />
                  </div>
                  <div class="flex items-center gap-2 text-slate-600 text-xs">
                    <MapPin class="w-3.5 h-3.5 text-slate-400" />
                    <span class="truncate block max-w-[200px]" :title="locationText">
                        {{ locationText }}
                    </span>
                  </div>
                </div>
             </CardContent>
           </Card>

           <div class="space-y-7 px-1">
             <div v-for="group in menuItems" :key="group.group" class="space-y-2.5">
               <h3 class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 px-3">
                 {{ group.group }}
               </h3>
               <nav class="space-y-0.5">
                 <Link 
                   v-for="item in group.items" 
                   :key="item.label"
                   :href="item.href"
                   class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-white hover:shadow-sm hover:translate-x-1 group text-[#334155]"
                 >
                   <component :is="item.icon" class="w-4 h-4 text-slate-400 group-hover:text-primary transition-colors" />
                   {{ item.label }}
                 </Link>
               </nav>
             </div>

             <div class="space-y-2.5 pt-2">
               <h3 class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 px-3">
                 Akun Sistem
               </h3>
                <button 
                   @click="logout"
                   class="w-full justify-start flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl text-destructive hover:bg-destructive/5 transition-all duration-200 hover:translate-x-1 group"
                 >
                   <LogOut class="w-4 h-4 text-destructive/70 group-hover:text-destructive" />
                   Keluar
                 </button>
             </div>

             <Button variant="outline" class="w-full justify-center rounded-xl bg-white border-border/60 h-10 font-bold text-[11px] uppercase tracking-wider shadow-sm hover:bg-slate-50 mt-4 text-[#334155]">
               Pusat Bantuan
             </Button>
           </div>
         </aside>

         <!-- Main Content (2/3) -->
         <main class="flex-1 w-full space-y-6">
           <slot />
         </main>
      </div>
    </div>
  </div>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
