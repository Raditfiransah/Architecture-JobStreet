<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  Search, 
  Trash2, 
  ExternalLink,
  Folder,
  Eye,
  User,
  Calendar
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import Pagination from "@/Components/Pagination.vue";
import { debounce } from "@/Utils/helpers";

const props = defineProps({
  portofolios: Object,
  filters: Object,
});

const search = ref(props.filters.search || "");

const updateSearch = debounce(() => {
  router.get(route('admin.portofolio.index'), { search: search.value }, {
    preserveState: true,
    preserveScroll: true,
  });
}, 500);

watch(search, () => updateSearch());

const handleDelete = (id) => {
  if (confirm("Hapus portofolio ini karena tidak sesuai ketentuan?")) {
    router.delete(route('admin.portofolio.destroy', id));
  }
};
</script>

<template>
  <Head title="Moderasi Portofolio" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-foreground">Moderasi Portofolio</h1>
          <p class="text-muted-foreground mt-1">Pantau dan kelola portofolio arsitek yang dipublikasikan.</p>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-card border border-border/60 rounded-2xl p-4 shadow-sm">
        <div class="relative w-full md:max-w-md">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
          <Input 
            v-model="search" 
            placeholder="Cari judul portofolio..." 
            class="pl-10 rounded-xl border-border/60 h-11 focus:ring-primary/20"
          />
        </div>
      </div>

      <!-- Portofolio Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <div v-for="item in portofolios.data" :key="item.id" class="bg-card border border-border/60 rounded-2xl overflow-hidden group hover:shadow-lg transition-all duration-300">
           <!-- Thumbnail Mockup -->
           <div class="aspect-video bg-muted relative overflow-hidden">
              <img v-if="item.thumbnail_url" :src="item.thumbnail_url" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
              <div v-else class="w-full h-full flex items-center justify-center bg-primary/5">
                 <Folder class="w-10 h-10 text-primary/20" />
              </div>
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-4">
                 <div class="flex gap-2 w-full">
                    <Button variant="secondary" size="sm" class="flex-1 rounded-lg h-9 font-bold text-[10px] uppercase tracking-wider">
                       <Eye class="w-3.5 h-3.5 mr-2" />
                       Preview
                    </Button>
                    <Button @click="handleDelete(item.id)" variant="destructive" size="icon" class="rounded-lg h-9 w-9">
                       <Trash2 class="w-3.5 h-3.5" />
                    </Button>
                 </div>
              </div>
           </div>
           
           <div class="p-5 space-y-3">
              <h3 class="font-bold text-sm text-foreground line-clamp-1 group-hover:text-primary transition-colors">{{ item.title }}</h3>
              <div class="flex items-center justify-between text-[11px] font-medium">
                 <div class="flex items-center gap-2 text-muted-foreground">
                    <User class="w-3.5 h-3.5" />
                    <span class="truncate max-w-[100px]">{{ item.user?.name }}</span>
                 </div>
                 <div class="flex items-center gap-1.5 text-muted-foreground/60">
                    <Calendar class="w-3.5 h-3.5" />
                    {{ new Date(item.created_at).toLocaleDateString() }}
                 </div>
              </div>
           </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="portofolios.data.length === 0" class="bg-card border border-border/60 rounded-2xl py-20 text-center">
         <div class="flex flex-col items-center justify-center space-y-3">
            <div class="p-4 bg-muted/50 rounded-full">
               <Folder class="w-10 h-10 text-muted-foreground/30" />
            </div>
            <p class="text-sm font-bold text-muted-foreground">Tidak ada portofolio ditemukan.</p>
         </div>
      </div>

      <div v-if="portofolios.links.length > 3" class="mt-8">
         <Pagination :links="portofolios.links" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
