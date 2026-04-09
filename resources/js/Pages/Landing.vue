<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Search, ChevronRight, Activity, MapPin } from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Badge } from "@/Components/UI/ui/badge";

const page = usePage();
const user = computed(() => page.props.auth.user);

const searchQuery = ref("");
const locationQuery = ref("");

const handleSearch = () => {
  router.get(route("lowongan.index"), { 
    q: searchQuery.value,
    l: locationQuery.value 
  });
};

const popularTags = [
  "Arsitek Residensial",
  "BIM Specialist",
  "Interior Designer",
  "Urban Planner",
  "Fresh Graduate",
  "Senior Architect"
];
</script>

<template>
  <PublicLayout :transparent-navbar="true">
    <Head>
      <title>Loker Arsitek — Platform Karir Arsitek #1 di Indonesia</title>
      <meta
        name="description"
        content="Temukan lowongan kerja, proyek freelance, dan portofolio digital untuk arsitek Indonesia. Bergabung dengan 1.200+ arsitek terverifikasi."
      />
    </Head>

    <main class="relative min-h-[90vh] flex flex-col items-center justify-center">
      <div class="w-full max-w-5xl px-6 py-20 text-center space-y-12">
        <div class="space-y-6">
          <h1 class="text-4xl md:text-6xl font-display font-bold text-foreground tracking-tight leading-tight">
            Bangun Karir Impianmu sebagai <br />
            <span class="text-primary italic relative">
              Arsitek Profesional
              <svg class="absolute -bottom-2 left-0 w-full h-3 text-primary/30" viewBox="0 0 100 10" preserveAspectRatio="none">
                <path d="M0 5 Q 25 0, 50 5 T 100 5" fill="none" stroke="currentColor" stroke-width="4" />
              </svg>
            </span>
          </h1>
          <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto leading-relaxed font-display font-semibold opacity-90">
            Temukan ribuan lowongan kerja, proyek kolaboratif, dan kembangkan portofolio digitalmu di komunitas arsitek terbesar di Indonesia.
          </p>
        </div>

        <div class="w-full max-w-2xl mx-auto">
          <div class="bg-card/80 backdrop-blur border-border/50 border-2 rounded-xl p-1.5 shadow-sm relative group focus-within:border-primary/40 transition-all">
            <form @submit.prevent="handleSearch" class="flex flex-row items-center gap-1.5">
              <div class="flex-[1.2] flex items-center px-4">
                <Search class="w-5 h-5 text-muted-foreground shrink-0" />
                <Input
                  v-model="searchQuery"
                  type="text"
                  placeholder="Cari posisi atau keahlian..."
                  class="border-0 bg-transparent text-base h-12 shadow-none focus-visible:ring-0 placeholder:text-muted-foreground/50 font-medium w-full"
                />
              </div>
              
              <div class="w-px h-8 bg-border shrink-0"></div>
              
              <div class="flex-1 flex items-center px-4">
                <MapPin class="w-5 h-5 text-muted-foreground shrink-0" />
                <Input
                  v-model="locationQuery"
                  type="text"
                  placeholder="Lokasi..."
                  class="border-0 bg-transparent text-base h-12 shadow-none focus-visible:ring-0 placeholder:text-muted-foreground/50 font-medium w-full"
                />
              </div>
              
              <Button
                type="submit"
                size="lg"
                class="rounded-lg px-8 h-12 font-bold shrink-0"
              >
                Cari
                <Search class="ml-2 w-4 h-4" />
              </Button>
            </form>
          </div>

          <div class="mt-10 flex flex-wrap items-center justify-center gap-2.5">
            <span class="text-xs font-bold uppercase tracking-widest text-muted-foreground mr-2">Populer:</span>
            <Link
              v-for="tag in popularTags"
              :key="tag"
              :href="route('lowongan.index', { q: tag })"
            >
              <Badge variant="outline" class="px-5 py-2 rounded-full border-border/50 bg-background/50 hover:bg-primary/5 hover:text-primary hover:border-primary/30 cursor-pointer font-semibold text-sm">
                {{ tag }}
              </Badge>
            </Link>
          </div>
        </div>
      </div>
    </main>
  </PublicLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Poppins', sans-serif;
}
</style>
