<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { 
  MapPin, 
  GraduationCap, 
  Clock, 
  User, 
  ExternalLink, 
  ChevronRight,
  Search,
  CheckCircle,
  MessageSquare
} from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";
import { Separator } from "@/Components/UI/ui/separator";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";

const props = defineProps({
  arsiteks: Object, // Paginated object
  filters: Object,
});

const searchQuery = ref(props.filters?.search || "");
const locationQuery = ref(props.filters?.location || "");

const page = usePage();
const user = computed(() => page.props.auth.user);

const architects = computed(() => props.arsiteks.data);
const selectedArsitek = ref(architects.value && architects.value.length > 0 ? architects.value[0] : null);

watch(() => architects.value, (newArsiteks) => {
  if (newArsiteks?.length > 0) {
    if (!selectedArsitek.value || !newArsiteks.find(a => a.id === selectedArsitek.value?.id)) {
      selectedArsitek.value = newArsiteks[0];
    }
  } else {
    selectedArsitek.value = null;
  }
});

const selectArsitek = (arsitek) => {
  selectedArsitek.value = arsitek;
};

const userInitials = (name) => {
  return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
};

const handleContact = () => {
    if (!user.value) {
        router.get(route('login'));
        return;
    }
    // Logic for contact/hire
};
</script>

<template>
  <PublicLayout :show-search="true" :show-footer="false">
    <Head title="Cari Arsitek" />

    <main class="flex-1 w-full max-w-[1280px] mx-auto flex flex-col md:flex-row bg-white">
      <!-- Architect List -->
      <aside class="w-full md:w-80 lg:w-96 shrink-0 border-r border-border overflow-y-auto">
        <div class="sticky top-0 bg-white/95 backdrop-blur z-20 px-4 py-3 border-b border-border flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-widest text-muted-foreground">{{ arsiteks.total || 0 }} Arsitek</span>
          <Button variant="ghost" size="sm" class="text-xs h-7 px-2 font-medium text-primary">Direktori</Button>
        </div>

        <div class="p-3 space-y-2">
          <div
            v-for="arsitek in architects"
            :key="arsitek.id"
            @click="selectArsitek(arsitek)"
            :class="[
              'p-4 rounded-xl border transition-all cursor-pointer group',
              selectedArsitek?.id === arsitek.id 
                ? 'bg-primary/5 border-primary shadow-sm' 
                : 'bg-white border-transparent hover:bg-slate-50 hover:border-border'
            ]"
          >
            <div class="flex gap-4">
               <Avatar class="h-12 w-12 rounded-lg border border-border shadow-sm shrink-0">
                  <AvatarImage :src="arsitek.user?.avatar_url" />
                  <AvatarFallback class="bg-primary/5 text-primary font-bold text-sm">
                     {{ userInitials(arsitek.user?.name || 'A') }}
                  </AvatarFallback>
               </Avatar>
               <div class="min-w-0 flex-1">
                  <h3 class="text-sm font-bold text-[#1E293B] leading-tight mb-1 truncate group-hover:text-primary transition-colors">
                     {{ arsitek.user?.name }}
                  </h3>
                  <p class="text-[11px] text-slate-500 mb-2 truncate">
                     {{ arsitek.status_pekerjaan || 'Arsitek Student' }}
                  </p>
                  <div class="flex items-center gap-1.5 text-[10px] text-slate-400 font-medium">
                     <MapPin class="w-3 h-3" />
                     {{ arsitek.location || 'Indonesia' }}
                  </div>
               </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- Detail View -->
      <section class="flex-1 bg-slate-50/30 overflow-y-auto px-6 lg:px-10 py-10">
        <div v-if="selectedArsitek" class="max-w-3xl mx-auto space-y-8">
          <Card class="border-border/60 shadow-sm overflow-hidden rounded-2xl bg-white">
             <!-- Banner Mockup -->
             <div class="h-40 w-full bg-[#f8fafc] border-b border-border/40 relative">
                <div class="absolute inset-0 opacity-5 bg-[radial-gradient(#e2e8f0_1.5px,transparent_1.5px)] [background-size:24px_24px]"></div>
                <div class="absolute right-8 bottom-0 h-32 w-48 opacity-30">
                   <img src="https://illustrations.popsy.co/slate/remote-work.svg" class="h-full w-full object-contain object-bottom" alt="Illustration" />
                </div>
             </div>

             <CardContent class="p-0 relative">
                <div class="px-8 pb-10">
                   <!-- Avatar -->
                   <div class="relative -mt-12 mb-6">
                      <Avatar class="h-24 w-24 rounded-2xl border-4 border-white shadow-xl">
                         <AvatarImage :src="selectedArsitek.user?.avatar_url" />
                         <AvatarFallback class="bg-primary/5 text-primary text-2xl font-bold">
                            {{ userInitials(selectedArsitek.user?.name || 'A') }}
                         </AvatarFallback>
                      </Avatar>
                   </div>

                   <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                      <div class="space-y-1.5">
                         <div class="flex items-center gap-2">
                            <h1 class="text-2xl font-display font-bold text-[#1E293B]">{{ selectedArsitek.user?.name }}</h1>
                            <CheckCircle class="w-4 h-4 text-primary" />
                         </div>
                         <p class="text-sm font-medium text-slate-500 flex items-center gap-2">
                            {{ selectedArsitek.status_pekerjaan || 'Arsitek Student' }}
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            {{ selectedArsitek.school || 'Politeknik Negeri Malang' }}
                         </p>
                         <div class="flex items-center gap-4 text-xs text-slate-400 pt-1">
                            <span class="flex items-center gap-1.5"><MapPin class="w-3.5 h-3.5" /> {{ selectedArsitek.location || 'Malang, Indonesia' }}</span>
                         </div>
                      </div>

                      <div class="flex items-center gap-3 shrink-0">
                         <Button @click="handleContact" class="rounded-xl px-10 h-12 font-bold text-xs uppercase tracking-wider">
                            Hire Architect
                         </Button>
                         <Button variant="outline" size="icon" class="rounded-xl w-12 h-12 border-border/60 hover:bg-slate-50">
                            <MessageSquare class="w-5 h-5 text-slate-500" />
                         </Button>
                      </div>
                   </div>
                </div>
             </CardContent>
          </Card>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
             <!-- Bio & Info -->
             <div class="lg:col-span-2 space-y-8">
                <section class="bg-white rounded-2xl p-8 border border-border/60 shadow-sm">
                   <div class="flex items-center gap-3 mb-6">
                      <div class="w-1 h-6 bg-primary rounded-full"></div>
                      <h3 class="text-lg font-bold text-[#1E293B]">Biografi & Pengalaman</h3>
                   </div>
                   <p class="text-sm text-slate-500 leading-relaxed font-medium">
                      {{ selectedArsitek.bio || 'Belum ada deskripsi biografi.' }}
                   </p>
                </section>

                <section class="bg-white rounded-2xl p-8 border border-border/60 shadow-sm">
                   <div class="flex items-center gap-3 mb-6">
                      <div class="w-1 h-6 bg-primary rounded-full"></div>
                      <h3 class="text-lg font-bold text-[#1E293B]">Edukasi</h3>
                   </div>
                   <div class="flex items-start gap-4">
                      <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center shrink-0 border border-border/40">
                         <GraduationCap class="w-6 h-6 text-primary" />
                      </div>
                      <div>
                         <p class="font-bold text-sm text-[#1E293B]">{{ selectedArsitek.school || 'Politeknik Negeri Malang' }}</p>
                         <p class="text-xs text-slate-500 mt-0.5">{{ selectedArsitek.degree_type || 'Architecture Student' }}</p>
                      </div>
                   </div>
                </section>
             </div>

             <!-- Sidebar Meta -->
             <div class="space-y-6">
                <Card class="rounded-2xl border-border/60 shadow-sm">
                   <CardHeader class="pb-3 px-6 pt-6">
                      <CardTitle class="text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Statistik Profil</CardTitle>
                   </CardHeader>
                   <CardContent class="px-6 pb-6 space-y-5">
                      <div class="flex items-center gap-4">
                         <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center border border-border/40">
                            <Clock class="w-5 h-5 text-primary" />
                         </div>
                         <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Bergabung</p>
                            <p class="text-sm font-bold text-[#1E293B]">April 2024</p>
                         </div>
                      </div>
                      <Separator class="bg-border/30" />
                      <Button variant="outline" class="w-full rounded-xl font-bold text-[10px] uppercase tracking-wider border-border/60 h-10 hover:bg-slate-50 text-[#334155]">
                         Lihat Portofolio Lengkap
                      </Button>
                   </CardContent>
                </Card>

                <div class="bg-primary text-primary-foreground rounded-2xl p-8 relative overflow-hidden shadow-xl shadow-primary/10">
                   <h4 class="text-lg font-bold mb-3 relative z-10">Mulai Proyekmu?</h4>
                   <p class="text-xs font-bold mb-8 opacity-70 leading-loose relative z-10 tracking-wide">Hire Arsitek profesional untuk mewujudkan desain impian Anda.</p>
                   <Button variant="secondary" class="w-full rounded-xl font-bold text-[11px] uppercase tracking-wider h-11 bg-white text-primary hover:bg-slate-50 relative z-10">
                      Hubungi Sekarang
                   </Button>
                </div>
             </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="h-full flex flex-col items-center justify-center text-center p-12 space-y-6">
          <div class="w-24 h-24 bg-white shadow-sm border border-border/60 rounded-full flex items-center justify-center">
             <User class="w-10 h-10 text-slate-200" />
          </div>
          <div class="max-w-xs space-y-2">
            <h3 class="text-xl font-bold text-[#1E293B]">Pilih Arsitek</h3>
            <p class="text-sm text-slate-400 font-medium leading-relaxed">Silakan pilih arsitek di sebelah kiri untuk melihat detail profil.</p>
          </div>
        </div>
      </section>
    </main>
  </PublicLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
