<script setup>
import { ref, computed } from 'vue';
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { 
  User, 
  Briefcase, 
  LogOut, 
  MapPin, 
  FileText, 
  Target, 
  Edit2,
  History,
  Building2,
  LayoutDashboard,
  ShieldCheck,
  Menu,
  X
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import { Card, CardContent } from "@/Components/UI/ui/card";
import Navbar from "@/Components/Public/Navbar.vue";
import VerificationBadge from '@/Components/Profile/VerificationBadge.vue';
import FlashMessage from "@/Components/Public/FlashMessage.vue";

const page = usePage();
const user = computed(() => page.props.auth.user || {});
const profile = computed(() => user.value.profile || {});

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
    return user.value.location || 'Administrator';
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
        { label: 'Dashboard', icon: LayoutDashboard, href: route('arsitek.dashboard'), active: 'arsitek.dashboard' },
      ]},
      { group: 'Kelola Portofolio & Lamaran', items: [
        { label: 'Portofolio', icon: Briefcase, href: route('arsitek.portofolio.index'), active: 'arsitek.portofolio.*' },
        { label: 'Aktivitas Lamaran', icon: History, href: route('arsitek.lamaran.index'), active: 'arsitek.lamaran.*' },
        { label: 'Proposal Proyek', icon: FileText, href: route('arsitek.proposal.index'), active: 'arsitek.proposal.*' },
      ]},
      { group: 'Mengelola Akun', items: [
        { label: 'Verifikasi', icon: ShieldCheck, href: route('arsitek.verifikasi.index'), active: 'arsitek.verifikasi.*' },
      ]}
    ];
  }
  
  if (role === 'perusahaan') {
    return [
      { group: 'Utama', items: [
        { label: 'Dashboard', icon: LayoutDashboard, href: route('perusahaan.dashboard'), active: 'perusahaan.dashboard' },
      ]},
      { group: 'Manajemen Rekrutmen', items: [
        { label: 'Kelola Lowongan', icon: Briefcase, href: route('perusahaan.lowongan.index'), active: 'perusahaan.lowongan.*' },
        { label: 'Kandidat', icon: Target, href: route('perusahaan.pelamar.all'), active: 'perusahaan.pelamar.*' },
      ]},
      { group: 'Mengelola Akun', items: [
        { label: 'Verifikasi', icon: ShieldCheck, href: route('perusahaan.verifikasi.index'), active: 'perusahaan.verifikasi.*' },
      ]}
    ];
  }
  
  if (role === 'client') {
    return [
      { group: 'Utama', items: [
        { label: 'Dashboard', icon: LayoutDashboard, href: route('client.dashboard'), active: 'client.dashboard' },
      ]},
      { group: 'Kelola Proyek', items: [
        { label: 'Proyek', icon: Briefcase, href: route('client.proyek.index'), active: 'client.proyek.*' },
      ]},
      { group: 'Mengelola Akun', items: [
        { label: 'Verifikasi', icon: ShieldCheck, href: route('client.verifikasi.index'), active: 'client.verifikasi.*' },
      ]}
    ];
  }

  return [];
});

const form = useForm({});
const logout = () => form.post(route('logout'));

// Mobile drawer state
const drawerOpen = ref(false);
const openDrawer = () => { drawerOpen.value = true; };
const closeDrawer = () => { drawerOpen.value = false; };
</script>

<template>
  <div class="min-h-screen bg-[#F3F6F9] font-sans antialiased text-[#1E293B] selection:bg-primary/20 selection:text-primary">
    <Navbar />

    <!-- Mobile top bar with hamburger -->
    <div class="lg:hidden sticky top-16 z-30 bg-[#F3F6F9] border-b border-border/40 px-4 py-3 flex items-center gap-3">
      <Button variant="outline" size="sm" class="rounded-lg gap-2 h-9 px-3 bg-white border-border/60" @click="openDrawer">
        <Menu class="w-4 h-4" />
        <span class="text-sm font-medium">Menu</span>
      </Button>
      <div class="flex items-center gap-2 min-w-0">
        <Avatar class="h-7 w-7 rounded-lg border border-border shrink-0">
          <AvatarImage :src="user?.avatar_url" />
          <AvatarFallback class="bg-primary/5 text-primary font-bold text-xs">{{ userInitials }}</AvatarFallback>
        </Avatar>
        <span class="text-sm font-semibold truncate text-[#1E293B]">{{ user?.name }}</span>
      </div>
    </div>

    <!-- Mobile Drawer Overlay -->
    <transition
      enter-active-class="transition-opacity duration-300"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition-opacity duration-200"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="drawerOpen"
        class="fixed inset-0 bg-black/50 z-40 lg:hidden"
        @click="closeDrawer"
      />
    </transition>

    <!-- Mobile Drawer -->
    <transition
      enter-active-class="transition-transform duration-300 ease-out"
      enter-from-class="-translate-x-full"
      enter-to-class="translate-x-0"
      leave-active-class="transition-transform duration-200 ease-in"
      leave-from-class="translate-x-0"
      leave-to-class="-translate-x-full"
    >
      <aside
        v-if="drawerOpen"
        class="fixed inset-y-0 left-0 z-50 w-[300px] bg-[#F3F6F9] overflow-y-auto lg:hidden shadow-2xl"
      >
        <!-- Drawer header -->
        <div class="flex items-center justify-between p-4 border-b border-border/40 bg-white sticky top-0">
          <span class="font-bold text-[#1E293B]">Menu Navigasi</span>
          <Button variant="ghost" size="icon" class="h-8 w-8 rounded-lg" @click="closeDrawer">
            <X class="w-4 h-4" />
          </Button>
        </div>

        <div class="p-4 space-y-6">
          <!-- Profile card in drawer -->
          <Card class="border-border/60 shadow-sm rounded-2xl">
            <CardContent class="p-4">
              <div class="flex items-center gap-3">
                <Avatar class="h-12 w-12 rounded-xl border border-border shadow-sm shrink-0">
                  <AvatarImage :src="user?.avatar_url" />
                  <AvatarFallback class="bg-primary/5 text-primary font-bold">{{ userInitials }}</AvatarFallback>
                </Avatar>
                <div class="min-w-0 flex-1">
                  <h2 class="font-bold text-sm leading-tight truncate text-[#1E293B]">{{ user?.name }}</h2>
                  <p class="text-xs text-[#64748B] mt-0.5 truncate">{{ jobTitle }}</p>
                  <div v-if="user.role !== 'admin'" class="mt-1.5">
                    <VerificationBadge :status="verificationStatus" :note="verificationNote" />
                  </div>
                </div>
                <Link :href="editRoute" @click="closeDrawer">
                  <Button variant="ghost" size="icon" class="h-7 w-7 rounded-lg">
                    <Edit2 class="w-3.5 h-3.5 text-[#64748B]" />
                  </Button>
                </Link>
              </div>
              <div class="flex items-center gap-2 mt-3 text-xs text-slate-500">
                <MapPin class="w-3.5 h-3.5 text-slate-400 shrink-0" />
                <span class="truncate">{{ locationText }}</span>
              </div>
            </CardContent>
          </Card>

          <!-- Navigation items -->
          <div class="space-y-5">
            <div v-for="group in menuItems" :key="group.group" class="space-y-1.5">
              <h3 class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 px-2">{{ group.group }}</h3>
              <nav class="space-y-0.5">
                <Link
                  v-for="item in group.items"
                  :key="item.label"
                  :href="item.href"
                  @click="closeDrawer"
                  :class="[
                    'flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl transition-all',
                    route().current(item.active)
                      ? 'bg-white shadow-sm text-primary'
                      : 'text-[#334155] hover:bg-white hover:shadow-sm'
                  ]"
                >
                  <component :is="item.icon" :class="['w-4 h-4', route().current(item.active) ? 'text-primary' : 'text-slate-400']" />
                  {{ item.label }}
                </Link>
              </nav>
            </div>

            <div class="space-y-1.5 pt-2 border-t border-border/40">
              <h3 class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 px-2 pt-3">Akun Sistem</h3>
              <button
                @click="logout"
                class="w-full flex items-center gap-3 px-3 py-2.5 text-sm font-medium rounded-xl text-destructive hover:bg-destructive/5 transition-all"
              >
                <LogOut class="w-4 h-4 text-destructive/70" />
                Keluar
              </button>
            </div>
          </div>
        </div>
      </aside>
    </transition>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 lg:py-10">
      <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start">
        
        <!-- Desktop Sidebar -->
        <aside class="hidden lg:block w-full lg:w-[320px] shrink-0 space-y-6">
          <Card class="border-border/60 shadow-[0_2px_4px_rgba(0,0,0,0.02)] overflow-hidden rounded-2xl">
            <CardContent class="p-5">
              <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                  <Avatar class="h-14 w-14 rounded-xl border border-border shadow-sm shrink-0">
                    <AvatarImage :src="user?.avatar_url" />
                    <AvatarFallback class="bg-primary/5 text-primary font-bold text-lg">{{ userInitials }}</AvatarFallback>
                  </Avatar>
                  <div class="min-w-0">
                    <h2 class="font-display font-bold text-lg leading-tight truncate text-[#1E293B]" :title="user?.name">{{ user?.name }}</h2>
                    <p class="text-[11px] text-[#64748B] mt-0.5 leading-relaxed truncate">{{ jobTitle }}</p>
                  </div>
                </div>
                <Link :href="editRoute">
                  <Button variant="ghost" size="icon" class="h-7 w-7 rounded-lg hover:bg-slate-100 shrink-0">
                    <Edit2 class="w-3.5 h-3.5 text-[#64748B]" />
                  </Button>
                </Link>
              </div>
              <div class="flex flex-col items-end gap-3 shrink-0 mt-4">
                <div v-if="user.role !== 'admin'" class="flex items-center justify-end w-full">
                  <VerificationBadge :status="verificationStatus" :note="verificationNote" />
                </div>
                <div class="flex items-center gap-2 text-slate-600 text-xs">
                  <MapPin class="w-3.5 h-3.5 text-slate-400" />
                  <span class="truncate block max-w-[200px]" :title="locationText">{{ locationText }}</span>
                </div>
              </div>
            </CardContent>
          </Card>

          <div class="space-y-7 px-1">
            <div v-for="group in menuItems" :key="group.group" class="space-y-2.5">
              <h3 class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 px-3">{{ group.group }}</h3>
              <nav class="space-y-0.5">
                <Link
                  v-for="item in group.items"
                  :key="item.label"
                  :href="item.href"
                  :class="[
                    'flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-white hover:shadow-sm hover:translate-x-1 group',
                    route().current(item.active) ? 'bg-white shadow-sm text-primary' : 'text-[#334155]'
                  ]"
                >
                  <component :is="item.icon" :class="['w-4 h-4 group-hover:text-primary transition-colors', route().current(item.active) ? 'text-primary' : 'text-slate-400']" />
                  {{ item.label }}
                </Link>
              </nav>
            </div>

            <div class="space-y-2.5 pt-2">
              <h3 class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 px-3">Akun Sistem</h3>
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

        <!-- Main Content -->
        <main class="flex-1 w-full min-w-0 space-y-6">
          <FlashMessage />
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
