<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { 
  MapPin, 
  Building2, 
  Calculator, 
  Clock, 
  Bookmark, 
  ExternalLink, 
  Building,
  CheckCircle2,
  ChevronRight,
  Search,
  Users,
  Briefcase
} from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";
import { Separator } from "@/Components/UI/ui/separator";

const props = defineProps({
  title: String,
  projects: Array,
  filters: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const searchQuery = ref(props.filters?.q || "");
const locationQuery = ref(props.filters?.l || "");
const selectedCategory = ref(props.filters?.c || "");

const categories = [
  'Residensial (Rumah, Villa, Apartemen)',
  'Komersial (Ruko, Kantor, Hotel, Kafe)',
  'Desain Interior',
  'Lansekap & Taman',
  'Urban Planning & Kawasan',
  'Renovasi & Sipil',
  'Lainnya'
];

const selectedProject = ref(props.projects && props.projects.length > 0 ? props.projects[0] : null);

watch(() => props.projects, (newProjects) => {
  if (newProjects?.length > 0) {
    if (!selectedProject.value || !newProjects.find(p => p.id === selectedProject.value?.id)) {
      selectedProject.value = newProjects[0];
    }
  } else {
    selectedProject.value = null;
  }
});

const handleSearch = () => {
  router.get(route("proyek.index"), { 
    q: searchQuery.value,
    l: locationQuery.value,
    c: selectedCategory.value
  }, { 
    preserveState: true,
    replace: true 
  });
};

const selectProject = (project) => {
  selectedProject.value = project;
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(value);
};
</script>

<template>
  <PublicLayout :show-search="true" :show-footer="false">
    <Head :title="title" />

    <!-- Filters header bar -->
    <div class="bg-slate-50 border-b border-slate-200 py-4 px-6 sticky top-[72px] z-30">
      <div class="max-w-[1280px] mx-auto flex flex-col md:flex-row gap-4 items-center justify-between">
        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto flex-1 max-w-3xl">
          <div class="relative flex-1">
            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <input 
              type="text" 
              placeholder="Cari proyek arsitektur..." 
              v-model="searchQuery"
              @keyup.enter="handleSearch"
              class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium"
            />
          </div>
          <div class="relative flex-1">
            <MapPin class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" />
            <input 
              type="text" 
              placeholder="Kota atau wilayah..." 
              v-model="locationQuery"
              @keyup.enter="handleSearch"
              class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all font-medium"
            />
          </div>
          <select 
            v-model="selectedCategory"
            @change="handleSearch"
            class="h-11 px-4 bg-white border border-slate-200 rounded-xl text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-slate-600 cursor-pointer"
          >
            <option value="">Semua Kategori</option>
            <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
          </select>
        </div>
        
        <Button class="rounded-xl font-bold h-11 px-6 shadow-sm w-full md:w-auto" @click="handleSearch">
          Terapkan
        </Button>
      </div>
    </div>

    <!-- Main split layout -->
    <main class="flex-1 w-full max-w-[1280px] mx-auto flex flex-col md:flex-row bg-white relative">
      
      <!-- Project List (Left Pane) -->
      <aside class="w-full md:w-80 lg:w-[420px] shrink-0 border-r border-slate-100 md:h-[calc(100vh-190px)] overflow-y-auto bg-slate-50/50">
        <div class="sticky top-0 bg-slate-50/95 backdrop-blur z-20 px-5 py-4 border-b border-slate-100 flex items-center justify-between">
          <span class="text-xs font-black uppercase tracking-widest text-slate-400">{{ projects?.length || 0 }} Proyek Aktif</span>
          <span class="text-xs font-bold text-primary">Urutan Terbaru</span>
        </div>

        <div class="p-4 space-y-3">
          <div
            v-for="project in projects"
            :key="project.id"
            @click="selectProject(project)"
            :class="[
              'px-5 py-4 rounded-2xl border transition-all cursor-pointer relative bg-white',
              selectedProject?.id === project.id 
                ? 'border-primary shadow-sm bg-primary/[0.02]' 
                : 'border-slate-100/80 shadow-sm hover:bg-slate-50/50 hover:border-slate-200'
            ]"
          >
            <div class="space-y-3">
              <div class="min-w-0">
                <h3 class="text-base font-bold text-slate-800 leading-snug hover:text-primary transition-colors">{{ project.title }}</h3>
                <p class="text-xs text-slate-400 font-semibold mt-1 flex items-center gap-1.5"><MapPin class="w-3.5 h-3.5 text-slate-400 shrink-0" /> {{ project.location }}</p>
              </div>
              
              <div class="flex items-center justify-between pt-2 border-t border-slate-100/60">
                <span class="text-sm font-extrabold text-slate-800">{{ formatCurrency(project.budget) }}</span>
                <span class="text-[10px] text-slate-400 font-semibold flex items-center gap-1"><Clock class="w-3 h-3" /> Baru saja</span>
              </div>
            </div>
          </div>
        </div>
      </aside>

      <!-- Detail (Right Pane) -->
      <section class="flex-1 bg-white md:h-[calc(100vh-190px)] overflow-y-auto px-6 lg:px-8 py-8">
        <div v-if="selectedProject" class="max-w-3xl mx-auto space-y-8 animate-in fade-in duration-300">
          <div class="border border-slate-100 rounded-3xl p-6 md:p-8 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-start gap-6 mb-6">
              <div class="flex-1 space-y-4">
                <div class="flex items-center gap-3">
                  <span class="text-[10px] font-black text-primary uppercase tracking-widest bg-primary/5 px-3 py-1 rounded-lg">{{ selectedProject.category }}</span>
                  <Badge class="bg-emerald-50 text-emerald-700 rounded-full px-2.5 py-0.5 text-[10px] font-bold border border-emerald-100 uppercase tracking-wide">Penerimaan Aktif</Badge>
                </div>
                <h1 class="text-2xl md:text-3xl font-display font-bold text-slate-800 leading-tight">{{ selectedProject.title }}</h1>
                
                <div class="flex flex-wrap items-center gap-4 pt-2">
                  <div class="flex items-center gap-2 text-primary font-extrabold">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center shrink-0"><Calculator class="w-4 h-4" /></div>
                    <span>{{ formatCurrency(selectedProject.budget) }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-slate-500 font-bold text-sm">
                    <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0"><MapPin class="w-4 h-4 text-primary" /></div>
                    <span>{{ selectedProject.location }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- CTA Bid / Application Action -->
            <div class="pt-4 border-t border-slate-100 flex flex-wrap gap-3">
              <Button asChild size="lg" class="rounded-xl px-8 h-12 font-bold flex-1 md:flex-none">
                <Link :href="route('proyek.show', selectedProject.id)">
                  Lihat & Ajukan Proposal
                  <ChevronRight class="ml-1 w-4 h-4" />
                </Link>
              </Button>
            </div>
          </div>

          <!-- Description and Specs -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
              <section>
                <div class="flex items-center gap-3 mb-4">
                  <div class="w-1.5 h-6 bg-primary rounded-full"></div>
                  <h3 class="text-lg font-bold text-slate-800">Spesifikasi Kebutuhan Proyek</h3>
                </div>
                <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line bg-slate-50/20 p-5 rounded-2xl border border-slate-50">
                  {{ selectedProject.description }}
                </p>
              </section>
            </div>

            <!-- Client Info Cards -->
            <div class="space-y-6">
              <Card class="rounded-2xl border-slate-100 overflow-hidden shadow-sm bg-white">
                <CardHeader class="pb-3 border-b border-slate-100/50">
                  <span class="text-[10px] font-black uppercase tracking-wider text-slate-400">Tentang Pemilik Proyek</span>
                </CardHeader>
                <CardContent class="p-5 space-y-4">
                  <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100"><User class="w-5 h-5 text-slate-400" /></div>
                    <div>
                      <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Klien / Pemilik</p>
                      <p class="text-sm font-bold text-slate-700 truncate max-w-[150px]">{{ selectedProject.user?.name || 'Client Web-Architect' }}</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center shrink-0 border border-slate-100"><Clock class="w-5 h-5 text-slate-400" /></div>
                    <div>
                      <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Posting</p>
                      <p class="text-sm font-bold text-slate-700">{{ new Date(selectedProject.created_at).toLocaleDateString('id-ID', { month: 'short', day: 'numeric', year: 'numeric' }) }}</p>
                    </div>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>

        <!-- Empty State (No selected project) -->
        <div v-else class="h-full flex flex-col items-center justify-center text-center p-12 space-y-6">
          <div class="w-24 h-24 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center">
             <Briefcase class="w-10 h-10 text-slate-400/55" />
          </div>
          <div class="max-w-xs space-y-2">
            <h3 class="text-xl font-bold">Pilih Proyek</h3>
            <p class="text-xs text-slate-400 leading-relaxed">Silakan pilih proyek di kolom sebelah kiri untuk melihat rincian spesifikasi desain bangunan.</p>
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
