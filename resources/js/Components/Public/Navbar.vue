<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { 
  Search, 
  MapPin, 
  User, 
  Menu, 
  X, 
  LogOut, 
  Home,
  Briefcase,
  LayoutDashboard,
  Settings,
  Folder,
  Users
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/Components/UI/ui/dropdown-menu";
import ProfileCard from "./ProfileCard.vue";

const props = defineProps({
  showSearch: {
    type: Boolean,
    default: false,
  },
  transparent: {
    type: Boolean,
    default: false,
  },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const mobileOpen = ref(false);
const isScrolled = ref(false);

const searchQuery = ref(page.props.filters?.q || "");
const locationQuery = ref(page.props.filters?.l || "");

const handleSearch = () => {
  if (route().current('arsitek.index')) {
    router.get(route("arsitek.index"), { 
      search: searchQuery.value,
      location: locationQuery.value 
    }, { 
      preserveState: true,
      replace: true 
    });
  } else {
    router.get(route("lowongan.index"), { 
      q: searchQuery.value,
      l: locationQuery.value 
    }, { 
      preserveState: true,
      replace: true 
    });
  }
};

const handleScroll = () => {
  isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const navLinks = computed(() => {
  const baseLinks = [
    { name: "Lowongan Kerja", route: "lowongan.index" },
    { name: "Hire Architect", route: "arsitek.index" },
  ];

  if (user.value) {
    return [
      ...baseLinks,
      { name: "Proyek", route: "proyek.index" },
      { name: "Info Hub", route: "info.index" },
    ];
  }

  return baseLinks;
});

const dropdownLinks = computed(() => {
  if (!user.value) return [];

  const links = {
    admin: [
      { name: "Dashboard", href: route('admin.dashboard'), icon: LayoutDashboard },
      { name: "Moderasi Lowongan", href: route('admin.lowongan.index'), icon: Briefcase },
      { name: "Moderasi Proyek", href: route('admin.proyek.index'), icon: Folder },
      { name: "Kelola User", href: route('admin.users.index'), icon: Users },
      { name: "Profil & Akun", href: route('admin.profil.edit'), icon: User },
    ],
    perusahaan: [
      { name: "Dashboard", href: route('perusahaan.dashboard'), icon: LayoutDashboard },
      { name: "Lowongan Saya", href: route('perusahaan.lowongan.index'), icon: Briefcase },
      { name: "Daftar Pelamar", href: route('perusahaan.pelamar.all'), icon: Users },
      { name: "Profil Perusahaan", href: route('perusahaan.profil.edit'), icon: User },
      { name: "Pengaturan", href: route('perusahaan.pengaturan.index'), icon: Settings },
    ],
    arsitek: [
      { name: "Dashboard", href: route('arsitek.dashboard'), icon: LayoutDashboard },
      { name: "Portofolio", href: route('arsitek.portofolio.index'), icon: Folder },
      { name: "Lamaran Saya", href: route('arsitek.lamaran.index'), icon: Briefcase },
      { name: "Profil Arsitek", href: route('arsitek.profil.edit'), icon: User },
      { name: "Pengaturan", href: route('arsitek.pengaturan.index'), icon: Settings },
    ],
    client: [
      { name: "Dashboard", href: route('client.dashboard'), icon: LayoutDashboard },
      { name: "Proyek Saya", href: route('client.proyek.index'), icon: Folder },
      { name: "Profil Client", href: route('client.profil.edit'), icon: User },
      { name: "Pengaturan", href: route('client.pengaturan.index'), icon: Settings },
    ],
  };

  return links[user.value.role] || [];
});
</script>

<template>
  <header 
    :class="[
      'sticky top-0 z-50 border-b bg-background/95 backdrop-blur',
      transparent && !isScrolled && !showSearch ? 'bg-transparent border-transparent' : 'border-border'
    ]"
  >
    <div class="max-w-[1280px] mx-auto px-4 h-16 flex items-center justify-between">
      <div class="flex items-center gap-8">
        <Link :href="route('home')" class="flex items-center gap-2.5 group">
           <div class="w-9 h-9 rounded-lg bg-primary flex items-center justify-center text-primary-foreground group-hover:rotate-12 transition-transform duration-300">
             <Briefcase class="w-5 h-5" />
           </div>
          <span class="text-2xl font-display font-bold tracking-tight text-primary">
            Loker<span class="text-foreground">Arsitek</span>
          </span>
        </Link>

        <nav class="hidden lg:flex items-center gap-1">
          <Link 
            v-for="link in navLinks" 
            :key="link.name" 
            :href="route(link.route)"
            :class="[
              'px-4 py-2 rounded-lg text-sm font-medium transition-colors',
              route().current(link.route)
                ? 'bg-primary/10 text-primary'
                : 'text-muted-foreground hover:text-foreground hover:bg-muted'
            ]"
          >
            {{ link.name }}
          </Link>
        </nav>
      </div>

      <div class="flex items-center gap-4">
        <template v-if="!user">
          <Button variant="ghost" asChild class="hidden sm:flex font-medium rounded-lg">
            <Link :href="route('login')">Masuk</Link>
          </Button>
          <Button asChild class="font-medium rounded-lg px-6">
            <Link :href="route('register')">Daftar</Link>
          </Button>
        </template>
        
        <template v-else>
          <DropdownMenu>
            <DropdownMenuTrigger asChild>
              <Button variant="ghost" class="h-10 w-10 rounded-full p-0 border border-border/50 hover:border-primary/30 overflow-hidden">
                <Avatar class="h-10 w-10">
                  <AvatarImage :src="user.avatar_url" :alt="user.name" />
                  <AvatarFallback class="bg-primary/10 text-primary font-bold">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </AvatarFallback>
                </Avatar>
              </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent class="w-80 mt-2 rounded-xl border-border/50 p-0 overflow-hidden" align="end">
              <div class="p-4 bg-background">
                <p class="font-bold text-foreground">{{ user.name }}</p>
                <p class="text-xs text-muted-foreground">{{ user.email }}</p>
                <DropdownMenuSeparator class="my-3" />
                <Link :href="route(user.role === 'admin' ? 'admin.dashboard' : user.role + '.profil.edit')" class="flex items-center py-2 text-sm font-medium hover:text-primary transition-colors">
                  <User class="mr-3 h-4 w-4" />
                  <span>Profil Saya</span>
                </Link>
                <Link v-if="user.role === 'client'" :href="route('client.proyek.create')" class="flex items-center py-2 text-sm font-medium hover:text-primary transition-colors">
                  <Briefcase class="mr-3 h-4 w-4" />
                  <span>Posting Proyek Baru</span>
                </Link>
                <Link :href="route('logout')" method="post" as="button" class="flex items-center w-full py-2 text-sm font-medium text-destructive mt-1 hover:text-destructive/80 transition-colors">
                  <LogOut class="mr-3 h-4 w-4" />
                  <span>Keluar</span>
                </Link>
              </div>
            </DropdownMenuContent>
          </DropdownMenu>
        </template>

        <Button 
          variant="ghost" 
          size="icon" 
          class="lg:hidden rounded-full" 
          @click="mobileOpen = !mobileOpen"
        >
          <Menu v-if="!mobileOpen" class="w-6 h-6" />
          <X v-else class="w-6 h-6" />
        </Button>
      </div>
    </div>

    <!-- Search Area -->
    <transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0 -translate-y-4"
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-4"
    >
      <div v-if="showSearch" class="bg-background border-t border-border py-3 shadow-sm backdrop-blur">
        <div class="max-w-[1280px] mx-auto px-4">
          <div class="max-w-2xl mx-auto relative flex items-center bg-muted/20 rounded-xl border border-border focus-within:border-primary/50 transition-all p-1.5 gap-1.5">
            <div class="flex-1 flex items-center px-4 py-1.5">
              <Search class="w-4 h-4 text-muted-foreground mr-3 shrink-0" />
              <Input 
                v-model="searchQuery" 
                type="text" 
                @keyup.enter="handleSearch"
                placeholder="Cari lowongan..." 
                class="border-none shadow-none focus-visible:ring-0 px-0 bg-transparent h-8 py-0 text-sm placeholder:text-muted-foreground/60 w-full" 
              />
            </div>
            
            <div class="w-px h-6 bg-border shrink-0"></div>
            
            <div class="flex-1 flex items-center px-4 py-1.5">
              <MapPin class="w-4 h-4 text-muted-foreground mr-3 shrink-0" />
              <Input 
                v-model="locationQuery" 
                type="text" 
                @keyup.enter="handleSearch"
                placeholder="Lokasi..." 
                class="border-none shadow-none focus-visible:ring-0 px-0 bg-transparent h-8 py-0 text-sm placeholder:text-muted-foreground/60 w-full" 
              />
            </div>
            
            <Button 
              @click="handleSearch" 
              size="icon"
              class="rounded-lg w-10 h-10 shrink-0 font-medium"
              title="Cari"
            >
              <Search class="w-4 h-4" />
            </Button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Mobile Menu Overlay -->
    <transition 
      enter-active-class="transition duration-300 ease-out" 
      enter-from-class="opacity-0 -translate-y-10" 
      enter-to-class="opacity-100 translate-y-0"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100 translate-y-0"
      leave-to-class="opacity-0 -translate-y-10"
    >
      <div v-if="mobileOpen" class="lg:hidden absolute top-full left-0 w-full bg-background border-t border-border p-6 space-y-6 z-[60]">
        <div class="grid gap-2">
          <Link 
            v-for="link in navLinks" 
            :key="link.name" 
            :href="route(link.route)"
            class="p-4 rounded-xl text-lg font-bold text-foreground hover:bg-primary/5 hover:text-primary"
            @click="mobileOpen = false"
          >
            {{ link.name }}
          </Link>
        </div>
        
        <div v-if="!user" class="pt-6 border-t border-border flex flex-col gap-3">
          <Button variant="outline" asChild size="lg" class="rounded-xl h-12 font-medium">
            <Link :href="route('login')">Masuk</Link>
          </Button>
          <Button asChild size="lg" class="rounded-xl h-12 font-medium">
            <Link :href="route('register')">Daftar Gratis</Link>
          </Button>
        </div>
      </div>
    </transition>
  </header>
</template>

<style scoped>
.font-display {
  font-family: 'Poppins', sans-serif;
}
</style>
