<script setup>
import { ref } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  ArrowLeft, 
  Folder, 
  Trash2, 
  Pencil,
  Calendar,
  ExternalLink,
  ImageOff,
  Plus
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";

const props = defineProps({
  user: Object,
  portofolios: Array,
});

const handleDelete = (id) => {
  if (confirm("Hapus portofolio ini? Tindakan tidak bisa dibatalkan.")) {
    router.delete(route('admin.portofolio.destroy', id), {
      preserveScroll: true,
    });
  }
};

const thumbnailUrl = (path) => {
  if (!path) return null;
  return path.startsWith('http') ? path : `/storage/${path}`;
};
</script>

<template>
  <Head :title="`Portofolio — ${user.name}`" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div class="flex items-center gap-4">
          <Button variant="ghost" size="icon" asChild class="rounded-xl">
            <Link :href="route('admin.portofolio.index')">
              <ArrowLeft class="w-5 h-5" />
            </Link>
          </Button>
          <div class="flex items-center gap-3">
            <Avatar class="h-12 w-12 rounded-xl border border-border/60 shadow-sm">
              <AvatarImage :src="user.avatar_url" />
              <AvatarFallback class="bg-primary/5 text-primary font-bold">
                {{ user.name.charAt(0) }}
              </AvatarFallback>
            </Avatar>
            <div>
              <h1 class="text-2xl font-bold tracking-tight text-foreground">{{ user.name }}</h1>
              <p class="text-sm text-muted-foreground">{{ portofolios.length }} portofolio</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="portofolios.length === 0" class="bg-card border border-border/60 rounded-2xl py-24 text-center">
        <div class="flex flex-col items-center justify-center space-y-4">
          <div class="p-5 bg-muted/50 rounded-full">
            <Folder class="w-12 h-12 text-muted-foreground/30" />
          </div>
          <p class="text-sm font-bold text-muted-foreground">Arsitek ini belum memiliki portofolio.</p>
        </div>
      </div>

      <!-- Portofolio Grid -->
      <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="item in portofolios"
          :key="item.id"
          class="bg-card border border-border/60 rounded-2xl overflow-hidden group hover:shadow-xl hover:shadow-black/5 transition-all duration-300 flex flex-col"
        >
          <!-- Thumbnail -->
          <div class="aspect-video bg-muted relative overflow-hidden">
            <img
              v-if="thumbnailUrl(item.thumbnail)"
              :src="thumbnailUrl(item.thumbnail)"
              :alt="item.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <div v-else class="w-full h-full flex flex-col items-center justify-center gap-3 bg-muted/50">
              <ImageOff class="w-10 h-10 text-muted-foreground/30" />
              <span class="text-[11px] font-bold text-muted-foreground/40 uppercase tracking-wider">Tidak ada gambar</span>
            </div>

            <!-- Hover overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300" />
            
            <!-- Gallery count badge -->
            <div v-if="item.images?.length" class="absolute top-3 right-3 px-2 py-1 bg-black/60 backdrop-blur-sm rounded-lg text-[10px] text-white font-bold flex items-center gap-1">
              <Plus class="w-3 h-3" />
              {{ item.images.length }} foto
            </div>
          </div>

          <!-- Content -->
          <div class="p-5 flex flex-col flex-1">
            <h3 class="font-bold text-sm text-foreground line-clamp-1 group-hover:text-primary transition-colors">
              {{ item.title }}
            </h3>
            <p v-if="item.description" class="text-xs text-muted-foreground mt-1.5 line-clamp-2 leading-relaxed flex-1">
              {{ item.description }}
            </p>
            <div v-else class="flex-1" />

            <div class="flex items-center justify-between mt-4 pt-4 border-t border-border/40">
              <div class="flex items-center gap-1.5 text-[11px] font-medium text-muted-foreground">
                <Calendar class="w-3.5 h-3.5" />
                <span v-if="item.project_date">{{ new Date(item.project_date).toLocaleDateString('id-ID', { month: 'short', year: 'numeric' }) }}</span>
                <span v-else>Tanpa tanggal</span>
              </div>
              <div class="flex items-center gap-2">
                <Button
                  asChild
                  variant="ghost"
                  size="icon"
                  class="h-8 w-8 rounded-lg hover:bg-primary/10 hover:text-primary"
                >
                  <Link :href="route('admin.portofolio.edit', item.id)">
                    <Pencil class="w-3.5 h-3.5" />
                  </Link>
                </Button>
                <Button
                  variant="ghost"
                  size="icon"
                  class="h-8 w-8 rounded-lg hover:bg-rose-50 hover:text-rose-600 text-muted-foreground"
                  @click="handleDelete(item.id)"
                >
                  <Trash2 class="w-3.5 h-3.5" />
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
