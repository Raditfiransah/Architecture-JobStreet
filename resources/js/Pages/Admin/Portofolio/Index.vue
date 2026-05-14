<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  Search, 
  Eye,
  Folder,
  User,
  ArrowRight
} from "lucide-vue-next";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/Components/UI/ui/table";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import Pagination from "@/Components/Pagination.vue";
import { debounce } from "@/Utils/helpers";

const props = defineProps({
  users: Object,
  filters: Object,
});

const search = ref(props.filters.search || "");

const updateSearch = debounce(() => {
  router.get(route('admin.portofolio.index'), { search: search.value }, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  });
}, 500);

watch(search, () => updateSearch());
</script>

<template>
  <Head title="Moderasi Portofolio" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-foreground">Moderasi Portofolio</h1>
          <p class="text-muted-foreground mt-1">Kelola portofolio berdasarkan arsitek pemiliknya.</p>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-card border border-border/60 rounded-2xl p-4 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full md:max-w-md">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
          <Input 
            v-model="search" 
            placeholder="Cari nama arsitek..." 
            class="pl-10 rounded-xl border-border/60 h-11 focus:ring-primary/20"
          />
        </div>
      </div>

      <!-- Arsitek Table -->
      <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
        <Table>
          <TableHeader class="bg-muted/30">
            <TableRow>
              <TableHead class="w-[400px] font-bold text-xs uppercase tracking-wider py-4">Arsitek</TableHead>
              <TableHead class="font-bold text-xs uppercase tracking-wider">Jumlah Portofolio</TableHead>
              <TableHead class="font-bold text-xs uppercase tracking-wider">Update Terakhir</TableHead>
              <TableHead class="text-right font-bold text-xs uppercase tracking-wider">Aksi</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="user in users.data" :key="user.id" class="group hover:bg-muted/5 transition-colors">
              <TableCell class="py-4">
                <div class="flex items-center gap-3">
                  <Avatar class="h-10 w-10 rounded-xl border border-border/60">
                    <AvatarImage :src="user.avatar_url" />
                    <AvatarFallback class="bg-primary/5 text-primary font-bold text-xs">
                      {{ user.name.charAt(0) }}
                    </AvatarFallback>
                  </Avatar>
                  <div class="min-w-0">
                    <p class="font-bold text-sm text-foreground truncate">{{ user.name }}</p>
                    <p class="text-xs text-muted-foreground truncate">{{ user.email }}</p>
                  </div>
                </div>
              </TableCell>
              <TableCell>
                <div class="flex items-center gap-2">
                   <div class="px-2.5 py-1 bg-primary/10 text-primary rounded-lg text-xs font-black">
                      {{ user.portofolios_count }}
                   </div>
                   <span class="text-xs font-medium text-muted-foreground">Karya</span>
                </div>
              </TableCell>
              <TableCell class="text-sm text-muted-foreground">
                {{ user.portofolios_count > 0 ? 'Aktif' : 'Belum ada data' }}
              </TableCell>
              <TableCell class="text-right">
                 <Button asChild variant="ghost" size="sm" class="h-9 px-4 rounded-xl gap-2 hover:bg-primary/5 hover:text-primary group/btn transition-all">
                    <Link :href="route('admin.portofolio.show', user.id)" class="flex items-center gap-2">
                       <span class="font-bold text-[10px] uppercase tracking-wider">Lihat Porto</span>
                       <ArrowRight class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" />
                    </Link>
                 </Button>
              </TableCell>
            </TableRow>
            <TableRow v-if="users.data.length === 0">
               <TableCell colspan="4" class="py-20 text-center">
                  <div class="flex flex-col items-center justify-center space-y-3">
                     <div class="p-4 bg-muted/50 rounded-full">
                        <User class="w-10 h-10 text-muted-foreground/30" />
                     </div>
                     <p class="text-sm font-bold text-muted-foreground">Tidak ada arsitek ditemukan.</p>
                  </div>
               </TableCell>
            </TableRow>
          </TableBody>
        </Table>
        
        <div v-if="users.links.length > 3" class="px-8 py-4 border-t border-border/40 bg-muted/5">
           <Pagination :links="users.links" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
