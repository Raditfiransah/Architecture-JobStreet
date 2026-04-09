<script setup>
import { Link } from "@inertiajs/vue3";
import { 
  Facebook, 
  Instagram, 
  Twitter, 
  Linkedin, 
  Github,
  Briefcase,
  ArrowUpRight
} from "lucide-vue-next";
import { Separator } from "@/Components/UI/ui/separator";

const footerSections = [
  {
    title: "Platform",
    links: [
      { name: "Lowongan Kerja", route: "lowongan.index" },
      { name: "Hire Architect", route: "arsitek.index" },
      { name: "Proyek", route: "proyek.index" },
      { name: "Info Hub", route: "info.index" },
    ]
  },
  {
    title: "Perusahaan",
    links: [
      { name: "Tentang Kami", route: "home" },
      { name: "Pasang Lowongan", route: "perusahaan.profile" },
      { name: "Pricelist", route: "home" },
      { name: "Karir", route: "home" },
    ]
  },
  {
    title: "Bantuan",
    links: [
      { name: "Pusat Bantuan", route: "home" },
      { name: "Kontak", route: "home" },
      { name: "Panduan", route: "home" },
      { name: "FAQ", route: "home" },
    ]
  }
];
</script>

<template>
  <footer class="bg-background border-t py-16 overflow-hidden">
    <div class="max-w-[1280px] mx-auto px-4 md:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-8 lg:gap-12">
        <div class="lg:col-span-3 space-y-8">
          <Link :href="route('home')" class="flex items-center gap-3 group">
            <div class="w-10 h-10 rounded-lg bg-primary flex items-center justify-center text-primary-foreground group-hover:rotate-12 transition-transform duration-300">
              <Briefcase class="w-6 h-6" />
            </div>
            <span class="text-2xl font-display font-bold tracking-tight text-primary">
              Loker<span class="text-foreground">Arsitek</span>
            </span>
          </Link>
          <p class="text-muted-foreground text-base max-w-md leading-relaxed">
            Platform karir dan kolaborasi terpadu untuk arsitek, desainer interior, dan profesional industri bangunan di Indonesia.
          </p>
          <div class="flex items-center gap-4">
            <a v-for="icon in [Facebook, Instagram, Twitter, Linkedin]" :key="icon" href="#" class="w-10 h-10 rounded-lg border border-border bg-card flex items-center justify-center text-muted-foreground hover:border-primary/30 hover:bg-primary/5 hover:text-primary transition-colors">
              <component :is="icon" class="w-5 h-5" />
            </a>
          </div>
        </div>

        <div v-for="section in footerSections" :key="section.title" class="lg:col-span-1">
          <h4 class="text-foreground font-display font-bold uppercase tracking-widest text-xs mb-6">{{ section.title }}</h4>
          <ul class="space-y-3">
            <li v-for="link in section.links" :key="link.name">
              <Link :href="route(link.route)" class="group flex items-center text-muted-foreground hover:text-primary transition-colors text-sm font-medium">
                {{ link.name }}
                <ArrowUpRight class="w-3 h-3 ml-1 opacity-0 group-hover:opacity-100 transition-opacity" />
              </Link>
            </li>
          </ul>
        </div>
      </div>

      <Separator class="my-12 bg-border/50" />

      <div class="flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-muted-foreground font-medium">
        <div class="flex items-center gap-2">
          <span>&copy; 2026</span>
          <span class="font-display font-bold text-foreground">Loker Arsitek Indonesia.</span>
          <span>Semua hak dilindungi.</span>
        </div>
        <div class="flex items-center gap-8">
          <a href="#" class="hover:text-primary transition-colors">Kebijakan Privasi</a>
          <a href="#" class="hover:text-primary transition-colors">Syarat & Ketentuan</a>
          <a href="#" class="hover:text-primary transition-colors">Sitemap</a>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
