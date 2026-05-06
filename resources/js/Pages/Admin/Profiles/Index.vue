<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  Search, 
  MoreVertical, 
  CheckCircle2, 
  XCircle, 
  ExternalLink,
  Filter,
  Building2,
  UserCircle,
  Clock,
  CheckCircle
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
import { Badge } from "@/Components/UI/ui/badge";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
  profiles: Object,
  filters: Object,
});

const type = ref(props.filters.type || "company");

watch(type, () => {
  router.get(route('admin.profiles.index'), { type: type.value }, {
    preserveState: true,
    preserveScroll: true,
  });
});

const getStatusBadge = (status) => {
  const statuses = {
    pending: { label: "Pending", class: "bg-orange-500/10 text-orange-500 border-orange-500/20" },
    verified: { label: "Terverifikasi", class: "bg-emerald-500/10 text-emerald-500 border-emerald-500/20" },
    rejected: { label: "Ditolak", class: "bg-rose-500/10 text-rose-500 border-rose-500/20" },
  };
  return statuses[status] || { label: status, class: "bg-muted text-muted-foreground" };
};

const handleVerify = (id) => {
  if (confirm("Verifikasi profil ini?")) {
    router.post(route('admin.profiles.verify', { type: type.value, id }));
  }
};

const handleReject = (id) => {
  const note = prompt("Alasan penolakan:");
  if (note) {
    router.post(route('admin.profiles.reject', { type: type.value, id }), { note });
  }
};
</script>

<template>
  <Head title="Moderasi Profil" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-foreground">Moderasi Profil</h1>
          <p class="text-muted-foreground mt-1">Verifikasi dan validasi profil pengguna.</p>
        </div>
      </div>

      <!-- Tabs / Type Switcher -->
      <div class="flex items-center gap-2 p-1.5 bg-muted/30 border border-border/60 rounded-2xl w-fit">
        <button 
          @click="type = 'company'"
          :class="[type === 'company' ? 'bg-white shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground', 'flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-300']"
        >
          <Building2 class="w-4 h-4" />
          Perusahaan
        </button>
        <button 
          @click="type = 'arsitek'"
          :class="[type === 'arsitek' ? 'bg-white shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground', 'flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-300']"
        >
          <UserCircle class="w-4 h-4" />
          Arsitek
        </button>
        <button 
          @click="type = 'client'"
          :class="[type === 'client' ? 'bg-white shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground', 'flex items-center gap-2 px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-300']"
        >
          <UserCircle class="w-4 h-4" />
          Client
        </button>
      </div>

      <!-- Profiles Table -->
      <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
        <Table>
          <TableHeader class="bg-muted/30">
            <TableRow>
              <TableHead class="w-[300px] font-bold text-xs uppercase tracking-wider py-4">Profil Info</TableHead>
              <TableHead class="font-bold text-xs uppercase tracking-wider">Status</TableHead>
              <TableHead class="font-bold text-xs uppercase tracking-wider">Identitas</TableHead>
              <TableHead class="font-bold text-xs uppercase tracking-wider">Verifikasi</TableHead>
              <TableHead class="text-right font-bold text-xs uppercase tracking-wider">Aksi</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="profile in profiles.data" :key="profile.id" class="group hover:bg-muted/5 transition-colors">
              <TableCell class="py-4">
                <div class="flex items-center gap-3">
                  <Avatar class="h-10 w-10 rounded-xl border border-border/60">
                    <AvatarImage :src="profile.company_logo_url || profile.user?.avatar_url" />
                    <AvatarFallback class="bg-primary/5 text-primary font-bold text-xs">
                      {{ (profile.company_name || profile.user?.name || 'P').charAt(0) }}
                    </AvatarFallback>
                  </Avatar>
                  <div class="min-w-0">
                    <p class="font-bold text-sm text-foreground truncate">{{ profile.company_name || profile.user?.name }}</p>
                    <p class="text-xs text-muted-foreground truncate">{{ profile.industry || profile.specialization || 'Profil User' }}</p>
                  </div>
                </div>
              </TableCell>
              <TableCell>
                <Badge :class="['rounded-lg font-bold text-[10px] uppercase tracking-wider px-2.5 py-1 border', getStatusBadge(profile.verification_status).class]" variant="outline">
                  {{ getStatusBadge(profile.verification_status).label }}
                </Badge>
              </TableCell>
              <TableCell>
                 <div class="flex items-center gap-2">
                    <a v-if="profile.identity_document_url" :href="profile.identity_document_url" target="_blank" class="flex items-center gap-1.5 text-xs font-bold text-primary hover:underline">
                       <ExternalLink class="w-3.5 h-3.5" />
                       Lihat Dokumen
                    </a>
                    <span v-else class="text-xs text-muted-foreground font-medium italic">Belum Diunggah</span>
                 </div>
              </TableCell>
              <TableCell>
                 <div v-if="profile.verified_at" class="flex items-center gap-1.5 text-xs text-muted-foreground font-medium">
                    <CheckCircle class="w-3.5 h-3.5 text-emerald-500" />
                    {{ new Date(profile.verified_at).toLocaleDateString() }}
                 </div>
                 <div v-else class="flex items-center gap-1.5 text-xs text-muted-foreground font-medium italic">
                    <Clock class="w-3.5 h-3.5 text-orange-400" />
                    Belum Diverifikasi
                 </div>
              </TableCell>
              <TableCell class="text-right">
                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                   <Button 
                    v-if="profile.verification_status !== 'verified'"
                    @click="handleVerify(profile.id)"
                    variant="ghost" 
                    size="sm" 
                    class="h-9 px-3 rounded-xl gap-2 text-emerald-600 hover:bg-emerald-50 hover:text-emerald-700"
                   >
                      <CheckCircle2 class="w-4 h-4" />
                      <span class="font-bold text-[10px] uppercase tracking-wider">Setujui</span>
                   </Button>
                   <Button 
                    v-if="profile.verification_status !== 'rejected'"
                    @click="handleReject(profile.id)"
                    variant="ghost" 
                    size="sm" 
                    class="h-9 px-3 rounded-xl gap-2 text-rose-600 hover:bg-rose-50 hover:text-rose-700"
                   >
                      <XCircle class="w-4 h-4" />
                      <span class="font-bold text-[10px] uppercase tracking-wider">Tolak</span>
                   </Button>
                </div>
              </TableCell>
            </TableRow>
            <TableRow v-if="profiles.data.length === 0">
               <TableCell colspan="5" class="py-20 text-center">
                  <div class="flex flex-col items-center justify-center space-y-3">
                     <div class="p-4 bg-muted/50 rounded-full">
                        <Filter class="w-10 h-10 text-muted-foreground/30" />
                     </div>
                     <p class="text-sm font-bold text-muted-foreground">Tidak ada profil dalam kategori ini.</p>
                  </div>
               </TableCell>
            </TableRow>
          </TableBody>
        </Table>
        
        <div v-if="profiles.links.length > 3" class="px-8 py-4 border-t border-border/40 bg-muted/5">
           <Pagination :links="profiles.links" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
