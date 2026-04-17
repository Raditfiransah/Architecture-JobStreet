<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
  LayoutDashboard, 
  User, 
  Folder, 
  Briefcase, 
  FileText, 
  Users, 
  MessageCircle,
  LogOut
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";

const props = defineProps({
  role: {
    type: String,
    default: '',
  },
});

const panelTitle = computed(() => {
  if (!props.role) return 'User Panel';
  const titles = {
    arsitek: 'Arsitek',
    perusahaan: 'Perusahaan',
    client: 'Client',
    admin: 'Administrator',
  };
  return titles[props.role] || 'User Panel';
});

const isRoute = (name) => route().current(name);

const form = useForm({});
const logout = () => {
  form.post(route('logout'));
};
</script>

<template>
  <aside class="bg-card text-card-foreground w-64 min-h-screen flex-shrink-0 border-r border-border flex flex-col sticky top-0 h-screen">
    <div class="p-6">
      <Link :href="route('home')" class="flex items-center gap-2 group mb-6">
        <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-primary-foreground transition-transform group-hover:rotate-12">
           <Briefcase class="w-4 h-4" />
        </div>
        <span class="text-xl font-display font-bold tracking-tight text-primary">
          Loker<span class="text-foreground">Arsitek</span>
        </span>
      </Link>

      <div class="px-3 py-2 rounded-lg bg-muted border border-border">
        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground mb-1">Access Level</p>
        <p class="text-sm font-bold text-foreground">{{ panelTitle }}</p>
      </div>
    </div>

    <nav class="flex-1 px-3 space-y-1 overflow-y-auto mt-2">
      <!-- Arsitek Menu -->
      <template v-if="role === 'arsitek'">
        <div class="px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-muted-foreground mt-4 mb-1">Utama</div>
        <Button asChild variant="ghost" :class="[isRoute('arsitek.profile') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('arsitek.profile')">
            <LayoutDashboard class="mr-3 h-4 w-4" />
            Dashboard
          </Link>
        </Button>

        <div class="px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-muted-foreground mt-4 mb-1">Karir & Kerja</div>
        <Button asChild variant="ghost" :class="[isRoute('arsitek.portofolio.*') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('arsitek.portofolio.index')">
            <Folder class="mr-3 h-4 w-4" />
            Portofolio
          </Link>
        </Button>
        <Button asChild variant="ghost" :class="[isRoute('arsitek.lamaran.*') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('arsitek.lamaran.index')">
             <Briefcase class="mr-3 h-4 w-4" />
            Lamaran Kerja
          </Link>
        </Button>
      </template>

      <!-- Perusahaan Menu -->
      <template v-else-if="role === 'perusahaan'">
        <div class="px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-muted-foreground mt-4 mb-1">Manajemen</div>
        <Button asChild variant="ghost" :class="[isRoute('perusahaan.profile') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('perusahaan.profile')">
            <LayoutDashboard class="mr-3 h-4 w-4" />
            Dashboard
          </Link>
        </Button>
        <Button asChild variant="ghost" :class="[isRoute('perusahaan.lowongan.*') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('perusahaan.lowongan.index')">
            <Briefcase class="mr-3 h-4 w-4" />
            Kelola Lowongan
          </Link>
        </Button>
        <Button asChild variant="ghost" :class="[isRoute('perusahaan.pelamar.*') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('perusahaan.pelamar.all')">
            <Users class="mr-3 h-4 w-4" />
            Kandidat Masuk
          </Link>
        </Button>
      </template>

      <!-- Client Menu -->
      <template v-else-if="role === 'client'">
        <Button asChild variant="ghost" :class="[isRoute('client.profile') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('client.profile')">
            <LayoutDashboard class="mr-3 h-4 w-4" />
            Dashboard
          </Link>
        </Button>
        <Button asChild variant="ghost" :class="[isRoute('client.proyek.*') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('client.proyek.index')">
            <Folder class="mr-3 h-4 w-4" />
            Kelola Proyek
          </Link>
        </Button>
      </template>

      <!-- Admin Menu -->
      <template v-else-if="role === 'admin'">
        <Button asChild variant="ghost" :class="[isRoute('admin.dashboard') ? 'bg-primary/10 text-primary' : 'text-muted-foreground hover:text-foreground hover:bg-muted']" class="w-full justify-start rounded-lg font-medium mb-1">
          <Link :href="route('admin.dashboard')">
            <LayoutDashboard class="mr-3 h-4 w-4" />
            Dashboard
          </Link>
        </Button>
        <Button asChild variant="ghost" class="w-full justify-start rounded-lg font-medium text-muted-foreground mb-1 hover:bg-muted hover:text-foreground">
          <Link href="#">
            <Users class="mr-3 h-4 w-4" />
            Kelola User
          </Link>
        </Button>
      </template>
    </nav>

    <div class="px-6 py-6 border-t border-border">
      <Button asChild variant="outline" class="w-full justify-start rounded-lg font-medium text-muted-foreground mb-2">
        <Link :href="route('info.index')">
           <MessageCircle class="mr-3 h-4 w-4" />
          Info Hub
        </Link>
      </Button>

      <Button @click="logout" variant="ghost" class="w-full justify-start rounded-lg font-medium text-destructive hover:bg-destructive/10 hover:text-destructive">
        <LogOut class="mr-3 h-4 w-4" />
        Keluar
      </Button>
      
      <div class="mt-6 flex items-center gap-3 px-1">
        <div class="w-2 h-2 rounded-full bg-primary"></div>
        <span class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">System Online</span>
      </div>
    </div>
  </aside>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
