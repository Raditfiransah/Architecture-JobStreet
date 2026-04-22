<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { 
  Building2, 
  Plus, 
  Search, 
  Filter, 
  MoreVertical, 
  FileEdit, 
  Trash2, 
  ArrowUpRight,
  Clock,
  CheckCircle2,
  XCircle
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent } from "@/Components/UI/ui/card";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from "@/Components/UI/ui/dropdown-menu";
import EmptyState from '@/Components/EmptyState.vue';

const props = defineProps({
  projects: {
    type: Array,
    default: () => []
  }
});

const getStatusColor = (status) => {
  switch (status?.toLowerCase()) {
    case 'aktif': return 'bg-blue-100 text-blue-700 border-blue-200';
    case 'selesai': return 'bg-green-100 text-green-700 border-green-200';
    case 'ditutup': return 'bg-slate-100 text-slate-700 border-slate-200';
    default: return 'bg-yellow-100 text-yellow-700 border-yellow-200';
  }
};
</script>

<template>
  <ProfileLayout>
    <Head title="Kelola Proyek" />

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
      <div>
        <h1 class="text-2xl font-display font-bold text-foreground">Kelola Proyek</h1>
        <p class="text-sm text-muted-foreground">Lihat dan pantau status semua proyek yang telah Anda publikasikan.</p>
      </div>
      <Button asChild class="rounded-xl font-bold shadow-sm">
        <Link :href="route('client.proyek.create')">
          <Plus class="w-4 h-4 mr-2" />
          Buat Proyek Baru
        </Link>
      </Button>
    </div>

    <!-- Filters & Search placeholder -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
      <div class="relative flex-1">
        <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
        <input 
          type="text" 
          placeholder="Cari proyek..." 
          class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
        />
      </div>
      <Button variant="outline" class="rounded-xl border-slate-200 text-slate-600 font-semibold text-sm">
        <Filter class="w-4 h-4 mr-2" />
        Filter
      </Button>
    </div>

    <!-- Project List -->
    <div v-if="projects.length > 0" class="space-y-4">
      <Card v-for="project in projects" :key="project.id" class="border-none shadow-sm rounded-2xl overflow-hidden hover:shadow-md transition-shadow duration-300">
        <CardContent class="p-6">
          <div class="flex items-start justify-between">
            <div class="flex gap-4">
              <div class="w-12 h-12 rounded-xl bg-primary/5 flex items-center justify-center text-primary shrink-0">
                <Building2 class="w-6 h-6" />
              </div>
              <div class="min-w-0">
                <Link :href="route('client.proyek.show', project.id)" class="hover:text-primary transition-colors">
                  <h3 class="font-bold text-lg leading-tight truncate">{{ project.title }}</h3>
                </Link>
                <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1 text-xs text-slate-500 font-medium">
                  <span class="flex items-center gap-1.5 line-clamp-1">
                    <Clock class="w-3.5 h-3.5" />
                    Dibuat: {{ project.created_at_formatted || 'Baru saja' }}
                  </span>
                  <span v-if="project.proposal_count" class="flex items-center gap-1.5">
                    <ArrowUpRight class="w-3.5 h-3.5" />
                    {{ project.proposal_count }} Proposal Masuk
                  </span>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <div :class="['px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border transition-colors', getStatusColor(project.status)]">
                {{ project.status || 'Aktif' }}
              </div>
              
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <Button variant="ghost" size="icon" class="h-8 w-8 rounded-lg text-slate-400">
                    <MoreVertical class="w-4 h-4" />
                  </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" class="rounded-xl border-slate-100 shadow-xl p-1 w-40">
                  <DropdownMenuItem asChild class="rounded-lg cursor-pointer">
                    <Link :href="route('client.proyek.show', project.id)" class="flex items-center">
                      <ArrowUpRight class="mr-2 h-4 w-4" /> Detail Proyek
                    </Link>
                  </DropdownMenuItem>
                  <DropdownMenuItem asChild class="rounded-lg cursor-pointer">
                    <Link :href="route('client.proyek.edit', project.id)" class="flex items-center">
                      <FileEdit class="mr-2 h-4 w-4" /> Edit Proyek
                    </Link>
                  </DropdownMenuItem>
                  <DropdownMenuItem class="rounded-lg text-destructive cursor-pointer hover:bg-destructive/5 hover:text-destructive">
                    <Trash2 class="mr-2 h-4 w-4" /> Hapus Proyek
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
            </div>
          </div>
        </CardContent>
      </Card>
    </div>

    <!-- Empty State -->
    <div v-else class="bg-white border border-slate-100 rounded-2xl p-16 shadow-sm text-center">
      <EmptyState 
        title="Belum ada proyek" 
        description="Anda belum memiliki proyek yang aktif. Mulailah dengan membuat proyek pertama Anda untuk menarik minat arsitek profesional." 
        actionText="Posting Proyek Pertama"
        :actionUrl="route('client.proyek.create')"
      />
    </div>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
