<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  Search, 
  MoreVertical, 
  ShieldAlert, 
  ShieldCheck, 
  Trash2, 
  Eye,
  Filter,
  UserCheck,
  UserX,
  RefreshCcw
} from "lucide-vue-next";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/Components/UI/ui/table";
import { 
  DropdownMenu, 
  DropdownMenuContent, 
  DropdownMenuItem, 
  DropdownMenuTrigger,
  DropdownMenuSeparator
} from "@/Components/UI/ui/dropdown-menu";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Badge } from "@/Components/UI/ui/badge";
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
const role = ref(props.filters.role || "");

const updateSearch = debounce(() => {
  router.get(route('admin.users.index'), { search: search.value, role: role.value }, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  });
}, 500);

watch([search, role], () => {
  updateSearch();
});

const getRoleBadge = (role) => {
  const roles = {
    arsitek: { label: "Arsitek", variant: "default", class: "bg-indigo-500 hover:bg-indigo-600" },
    perusahaan: { label: "Perusahaan", variant: "secondary", class: "bg-emerald-500 hover:bg-emerald-600 text-white" },
    client: { label: "Client", variant: "outline", class: "border-orange-500 text-orange-500 hover:bg-orange-50" },
  };
  return roles[role] || { label: role, variant: "outline", class: "" };
};

const getUserStatusInfo = (user) => {
  if (!user.is_active) {
    return {
      label: 'Suspended',
      textClass: 'text-rose-600',
      dotClass: 'bg-rose-500'
    };
  }
  
  if (!user.email_verified_at) {
    return {
      label: 'Belum Verifikasi',
      textClass: 'text-amber-600',
      dotClass: 'bg-amber-500'
    };
  }
  
  const isDocumentVerified = 
    (user.role === 'arsitek' && user.arsitek_profile?.verification_status === 'verified') ||
    (user.role === 'perusahaan' && user.company_profile?.verification_status === 'verified');
    
  if (isDocumentVerified) {
    return {
      label: 'Aktif & Terverifikasi (Dokumen)',
      textClass: 'text-blue-600',
      dotClass: 'bg-blue-500'
    };
  }
  
  return {
    label: 'Aktif',
    textClass: 'text-emerald-600',
    dotClass: 'bg-emerald-500'
  };
};

const handleSuspend = (id) => {
  if (confirm("Apakah Anda yakin ingin menonaktifkan user ini?")) {
    router.post(route('admin.users.suspend', id));
  }
};

const handleActivate = (id) => {
  router.post(route('admin.users.aktifkan', id));
};

const handleDelete = (id) => {
  if (confirm("PERINGATAN: Menghapus user akan menghapus semua data terkait. Lanjutkan?")) {
    router.delete(route('admin.users.destroy', id));
  }
};
</script>

<template>
  <Head title="Manajemen User" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-foreground">Manajemen User</h1>
          <p class="text-muted-foreground mt-1">Kelola dan pantau seluruh pengguna terdaftar.</p>
        </div>
        <div class="flex items-center gap-3">
          <Button variant="outline" class="rounded-xl font-bold text-xs uppercase tracking-wider gap-2">
            <RefreshCcw class="w-4 h-4" />
            Refresh Data
          </Button>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-card border border-border/60 rounded-2xl p-4 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
          <Input 
            v-model="search" 
            placeholder="Cari nama atau email..." 
            class="pl-10 rounded-xl border-border/60 h-11 focus:ring-primary/20"
          />
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
          <div class="flex items-center gap-2 px-3 py-2 bg-muted/50 border border-border/60 rounded-xl min-w-[160px]">
             <Filter class="w-4 h-4 text-muted-foreground" />
             <select v-model="role" class="bg-transparent border-0 text-sm font-bold focus:ring-0 w-full">
                <option value="">Semua Role</option>
                <option value="arsitek">Arsitek</option>
                <option value="perusahaan">Perusahaan</option>
                <option value="client">Client</option>
             </select>
          </div>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
        <Table>
          <TableHeader class="bg-muted/30">
            <TableRow>
              <TableHead class="w-[300px] font-bold text-xs uppercase tracking-wider py-4">User Info</TableHead>
              <TableHead class="font-bold text-xs uppercase tracking-wider">Role</TableHead>
              <TableHead class="font-bold text-xs uppercase tracking-wider">Status</TableHead>
              <TableHead class="font-bold text-xs uppercase tracking-wider">Tgl Pendaftaran</TableHead>
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
                <Badge :class="['rounded-lg font-bold text-[10px] uppercase tracking-wider px-2.5 py-1', getRoleBadge(user.role).class]" :variant="getRoleBadge(user.role).variant">
                  {{ getRoleBadge(user.role).label }}
                </Badge>
              </TableCell>
              <TableCell>
                <div class="flex items-center gap-2">
                  <div :class="[getUserStatusInfo(user).dotClass, 'w-2 h-2 rounded-full']"></div>
                  <span :class="[getUserStatusInfo(user).textClass, 'text-xs font-bold uppercase tracking-wider']">
                    {{ getUserStatusInfo(user).label }}
                  </span>
                </div>
              </TableCell>
              <TableCell class="text-sm text-muted-foreground">
                {{ new Date(user.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
              </TableCell>
              <TableCell class="text-right">
                <DropdownMenu>
                  <DropdownMenuTrigger asChild>
                    <Button variant="ghost" size="icon" class="rounded-xl group-hover:bg-muted transition-colors">
                      <MoreVertical class="w-4 h-4 text-muted-foreground" />
                    </Button>
                  </DropdownMenuTrigger>
                  <DropdownMenuContent align="end" class="w-48 rounded-xl p-2 border-border/60">
                    <DropdownMenuItem class="rounded-lg gap-2 cursor-pointer py-2">
                      <Eye class="w-4 h-4 text-primary" />
                      <span class="font-medium text-xs">Detail Profil</span>
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem v-if="user.is_active" @click="handleSuspend(user.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-rose-600 focus:text-rose-600 focus:bg-rose-50">
                      <ShieldAlert class="w-4 h-4" />
                      <span class="font-medium text-xs">Suspend User</span>
                    </DropdownMenuItem>
                    <DropdownMenuItem v-else @click="handleActivate(user.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-emerald-600 focus:text-emerald-600 focus:bg-emerald-50">
                      <ShieldCheck class="w-4 h-4" />
                      <span class="font-medium text-xs">Aktifkan Kembali</span>
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="handleDelete(user.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-rose-600 focus:text-rose-600 focus:bg-rose-50">
                      <Trash2 class="w-4 h-4" />
                      <span class="font-medium text-xs">Hapus User</span>
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </TableCell>
            </TableRow>
            <TableRow v-if="users.data.length === 0">
               <TableCell colspan="5" class="py-20 text-center">
                  <div class="flex flex-col items-center justify-center space-y-3">
                     <div class="p-4 bg-muted/50 rounded-full">
                        <Users class="w-10 h-10 text-muted-foreground/30" />
                     </div>
                     <p class="text-sm font-bold text-muted-foreground">Tidak ada user ditemukan.</p>
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
