<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  Users, 
  Briefcase, 
  UserCircle, 
  FileText, 
  Building2,
  TrendingUp,
  ArrowUpRight,
  ChevronRight
} from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";

const props = defineProps({
  stats: Object,
});

const statCards = [
  { 
    title: "Total Users", 
    value: props.stats.total_users, 
    icon: Users, 
    color: "bg-blue-500",
    description: "Seluruh pengguna terdaftar",
    link: route('admin.users.index')
  },
  { 
    title: "Perusahaan", 
    value: props.stats.total_companies, 
    icon: Building2, 
    color: "bg-emerald-500",
    description: "Profil perusahaan aktif",
    link: route('admin.profiles.index', { type: 'company' })
  },
  { 
    title: "Arsitek", 
    value: props.stats.total_arsiteks, 
    icon: UserCircle, 
    color: "bg-indigo-500",
    description: "Arsitek terverifikasi",
    link: route('admin.profiles.index', { type: 'arsitek' })
  },
  { 
    title: "Lowongan", 
    value: props.stats.total_lowongan, 
    icon: Briefcase, 
    color: "bg-orange-500",
    description: "Lowongan kerja aktif",
    link: route('admin.lowongan.index')
  },
  { 
    title: "Lamaran", 
    value: props.stats.total_lamaran, 
    icon: FileText, 
    color: "bg-rose-500",
    description: "Total lamaran masuk",
    link: route('admin.lowongan.index')
  },
];
</script>

<template>
  <Head title="Admin Dashboard" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-foreground">Admin Overview</h1>
          <p class="text-muted-foreground mt-1">Pusat kontrol dan monitoring sistem LokerArsitek.</p>
        </div>
        <div class="flex items-center gap-3">
          <Button variant="outline" class="rounded-xl font-bold text-xs uppercase tracking-wider">
            Download Report
          </Button>
          <Button class="rounded-xl font-bold text-xs uppercase tracking-wider shadow-lg shadow-primary/20">
            System Status
          </Button>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
        <Card v-for="stat in statCards" :key="stat.title" class="overflow-hidden border-border/60 shadow-sm group hover:shadow-md transition-all duration-300">
          <CardContent class="p-0">
            <div class="p-6">
              <div class="flex items-center justify-between mb-4">
                <div :class="[stat.color, 'p-2.5 rounded-xl text-white shadow-lg shadow-current/20 group-hover:scale-110 transition-transform duration-300']">
                  <component :is="stat.icon" class="w-5 h-5" />
                </div>
                <div class="flex items-center gap-1 text-[10px] font-bold text-emerald-500 bg-emerald-500/10 px-2 py-0.5 rounded-full">
                  <TrendingUp class="w-3 h-3" />
                  +12%
                </div>
              </div>
              <div>
                <p class="text-sm font-medium text-muted-foreground">{{ stat.title }}</p>
                <h3 class="text-3xl font-bold text-foreground mt-1">{{ stat.value }}</h3>
                <p class="text-[11px] text-muted-foreground mt-1.5 flex items-center gap-1">
                  {{ stat.description }}
                </p>
              </div>
            </div>
            <Link :href="stat.link" class="flex items-center justify-between px-6 py-3 bg-muted/30 border-t border-border/40 hover:bg-muted/50 transition-colors group/link">
              <span class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground group-hover/link:text-primary">Kelola Data</span>
              <ArrowUpRight class="w-3.5 h-3.5 text-muted-foreground group-hover/link:text-primary transition-transform group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5" />
            </Link>
          </CardContent>
        </Card>
      </div>

      <!-- Quick Actions & Recent Activity -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
        <Card class="lg:col-span-2 border-border/60 shadow-sm rounded-2xl overflow-hidden">
          <CardHeader class="border-b border-border/40 bg-muted/10 px-8 py-6">
            <div class="flex items-center justify-between">
               <CardTitle class="text-lg font-bold">Aktivitas Sistem Terbaru</CardTitle>
               <Button variant="ghost" size="sm" class="text-xs font-bold text-primary">Lihat Semua</Button>
            </div>
          </CardHeader>
          <CardContent class="p-0">
            <div class="divide-y divide-border/40">
              <div v-for="i in 5" :key="i" class="px-8 py-5 flex items-center justify-between hover:bg-muted/5 transition-colors">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 rounded-full bg-muted/50 flex items-center justify-center shrink-0">
                    <Users class="w-5 h-5 text-muted-foreground" />
                  </div>
                  <div>
                    <p class="text-sm font-bold text-foreground">Pendaftaran User Baru</p>
                    <p class="text-xs text-muted-foreground mt-0.5">Andi Pratama baru saja mendaftar sebagai Arsitek.</p>
                  </div>
                </div>
                <div class="text-right">
                  <p class="text-[10px] font-bold text-muted-foreground uppercase">2 Menit Lalu</p>
                  <ChevronRight class="w-4 h-4 text-muted-foreground/30 mt-1 ml-auto" />
                </div>
              </div>
            </div>
          </CardContent>
        </Card>

        <div class="space-y-6">
          <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden">
            <CardHeader class="border-b border-border/40 bg-muted/10 px-8 py-6">
              <CardTitle class="text-lg font-bold">Quick Actions</CardTitle>
            </CardHeader>
            <CardContent class="p-6 space-y-3">
              <Button variant="outline" class="w-full justify-start rounded-xl h-12 px-4 gap-3 border-border/60 hover:bg-muted/50">
                <ShieldCheck class="w-5 h-5 text-primary" />
                <span class="font-bold text-xs uppercase tracking-wider">Verifikasi Profil Massal</span>
              </Button>
              <Button variant="outline" class="w-full justify-start rounded-xl h-12 px-4 gap-3 border-border/60 hover:bg-muted/50">
                <Monitor class="w-5 h-5 text-emerald-500" />
                <span class="font-bold text-xs uppercase tracking-wider">Bersihkan Cache Sistem</span>
              </Button>
              <Button variant="outline" class="w-full justify-start rounded-xl h-12 px-4 gap-3 border-border/60 hover:bg-muted/50">
                <FileText class="w-5 h-5 text-orange-500" />
                <span class="font-bold text-xs uppercase tracking-wider">Generate Laporan Bulanan</span>
              </Button>
            </CardContent>
          </Card>

          <div class="bg-primary text-primary-foreground rounded-2xl p-8 relative overflow-hidden shadow-xl shadow-primary/20">
            <div class="relative z-10">
              <h4 class="text-xl font-bold mb-2">Pusat Bantuan Admin</h4>
              <p class="text-xs font-medium opacity-80 leading-loose mb-6">Butuh bantuan teknis atau panduan penggunaan dashboard? Hubungi tim support.</p>
              <Button variant="secondary" class="w-full rounded-xl font-bold text-[11px] uppercase tracking-wider h-11 bg-white text-primary hover:bg-slate-50">
                Hubungi Support
              </Button>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
