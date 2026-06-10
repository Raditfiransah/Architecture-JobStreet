<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  Search, 
  MoreVertical, 
  CheckCircle, 
  XCircle, 
  ExternalLink,
  Filter,
  Briefcase,
  Users,
  Eye,
  Ban,
  Clock
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
  DropdownMenuTrigger 
} from "@/Components/UI/ui/dropdown-menu";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Badge } from "@/Components/UI/ui/badge";
import Pagination from "@/Components/Pagination.vue";
import { debounce } from "@/Utils/helpers";

const props = defineProps({
  lowongans: Object,
  filters: Object,
});

const search = ref(props.filters.search || "");
const status = ref(props.filters.status || "");

const updateSearch = debounce(() => {
  router.get(route('admin.lowongan.index'), { search: search.value, status: status.value }, {
    preserveState: true,
    preserveScroll: true,
  });
}, 500);

watch([search, status], () => updateSearch());

const getStatusBadge = (status) => {
  const statuses = {
    pending: { label: "Menunggu", class: "bg-orange-500/10 text-orange-500 border-orange-500/20" },
    aktif: { label: "Aktif", class: "bg-emerald-500/10 text-emerald-500 border-emerald-500/20" },
    nonaktif: { label: "Nonaktif", class: "bg-slate-500/10 text-slate-500 border-slate-500/20" },
    ditolak: { label: "Ditolak", class: "bg-rose-500/10 text-rose-500 border-rose-500/20" },
  };
  return statuses[status] || { label: status, class: "bg-muted text-muted-foreground" };
};

const handleApprove = (id) => {
  if (confirm("Setujui lowongan ini untuk dipublikasikan?")) {
    router.post(route('admin.lowongan.setujui', id));
  }
};

const handleReject = (id) => {
  if (confirm("Tolak lowongan ini?")) {
    router.post(route('admin.lowongan.tolak', id));
  }
};

const handleClose = (id) => {
  if (confirm("Tutup lowongan ini secara permanen?")) {
    router.post(route('admin.lowongan.tutup', id));
  }
};
</script>

<template>
  <Head title="Manajemen Lowongan" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-foreground">Lowongan & Lamaran</h1>
          <p class="text-muted-foreground mt-1">Moderasi lowongan kerja dan pantau lamaran masuk.</p>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-card border border-border/60 rounded-2xl p-4 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
          <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
          <Input 
            v-model="search" 
            placeholder="Cari posisi atau perusahaan..." 
            class="pl-10 rounded-xl border-border/60 h-11 focus:ring-primary/20"
          />
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
          <div class="flex items-center gap-2 px-3 py-2 bg-muted/50 border border-border/60 rounded-xl min-w-[160px]">
             <Filter class="w-4 h-4 text-muted-foreground" />
             <select v-model="status" class="bg-transparent border-0 text-sm font-bold focus:ring-0 w-full">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
                <option value="ditolak">Ditolak</option>
             </select>
          </div>
        </div>
      </div>

      <!-- Lowongan Table (desktop) / Card List (mobile) -->
      <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block">
          <Table>
            <TableHeader class="bg-muted/30">
              <TableRow>
                <TableHead class="w-[300px] font-bold text-xs uppercase tracking-wider py-4">Lowongan</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Perusahaan</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Status</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Lamaran</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Deadline</TableHead>
                <TableHead class="text-right font-bold text-xs uppercase tracking-wider">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="job in lowongans.data" :key="job.id" class="group hover:bg-muted/5 transition-colors">
                <TableCell class="py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary shrink-0 border border-primary/10 font-bold text-xs">
                      {{ job.inisial || 'LW' }}
                    </div>
                    <div class="min-w-0">
                      <p class="font-bold text-sm text-foreground truncate group-hover:text-primary transition-colors">{{ job.posisi }}</p>
                      <p class="text-[10px] text-muted-foreground uppercase font-black tracking-wider">{{ job.tipe }}</p>
                    </div>
                  </div>
                </TableCell>
                <TableCell>
                  <p class="text-sm font-bold text-foreground">{{ job.perusahaan }}</p>
                  <p class="text-xs text-muted-foreground flex items-center gap-1">
                    <Clock class="w-3 h-3" />
                    {{ new Date(job.created_at).toLocaleDateString() }}
                  </p>
                </TableCell>
                <TableCell>
                  <Badge :class="['rounded-lg font-bold text-[10px] uppercase tracking-wider px-2.5 py-1 border', getStatusBadge(job.status).class]" variant="outline">
                    {{ getStatusBadge(job.status).label }}
                  </Badge>
                </TableCell>
                <TableCell>
                  <div class="flex items-center gap-2 text-sm font-bold text-foreground">
                    <Users class="w-4 h-4 text-muted-foreground" />
                    {{ job.lamarans_count || 0 }}
                  </div>
                </TableCell>
                <TableCell class="text-xs font-medium text-muted-foreground">
                  {{ job.deadline ? new Date(job.deadline).toLocaleDateString() : '-' }}
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
                        <span class="font-medium text-xs">Lihat Detail</span>
                      </DropdownMenuItem>
                      <DropdownMenuItem v-if="job.status === 'pending'" @click="handleApprove(job.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-emerald-600 focus:text-emerald-600 focus:bg-emerald-50">
                        <CheckCircle class="w-4 h-4" />
                        <span class="font-medium text-xs">Setujui</span>
                      </DropdownMenuItem>
                      <DropdownMenuItem v-if="job.status === 'pending'" @click="handleReject(job.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-rose-600 focus:text-rose-600 focus:bg-rose-50">
                        <XCircle class="w-4 h-4" />
                        <span class="font-medium text-xs">Tolak</span>
                      </DropdownMenuItem>
                      <DropdownMenuItem v-if="job.status === 'aktif'" @click="handleClose(job.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-slate-600 focus:text-slate-600 focus:bg-slate-50">
                        <Ban class="w-4 h-4" />
                        <span class="font-medium text-xs">Tutup Lowongan</span>
                      </DropdownMenuItem>
                    </DropdownMenuContent>
                  </DropdownMenu>
                </TableCell>
              </TableRow>
              <TableRow v-if="lowongans.data.length === 0">
                <TableCell colspan="6" class="py-20 text-center">
                  <div class="flex flex-col items-center justify-center space-y-3">
                    <div class="p-4 bg-muted/50 rounded-full">
                      <Briefcase class="w-10 h-10 text-muted-foreground/30" />
                    </div>
                    <p class="text-sm font-bold text-muted-foreground">Tidak ada lowongan ditemukan.</p>
                  </div>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-border/40">
          <div v-if="lowongans.data.length === 0" class="py-16 text-center">
            <p class="text-sm font-bold text-muted-foreground">Tidak ada lowongan ditemukan.</p>
          </div>
          <div v-for="job in lowongans.data" :key="job.id" class="p-4 hover:bg-muted/5 transition-colors">
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-xl bg-primary/5 flex items-center justify-center text-primary shrink-0 border border-primary/10 font-bold text-xs">
                {{ job.inisial || 'LW' }}
              </div>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div class="min-w-0">
                    <p class="font-bold text-sm text-foreground truncate">{{ job.posisi }}</p>
                    <p class="text-xs text-muted-foreground font-bold">{{ job.perusahaan }}</p>
                  </div>
                  <div class="flex items-center gap-2 shrink-0">
                    <Badge :class="['rounded-lg font-bold text-[10px] uppercase tracking-wider px-2 py-0.5 border', getStatusBadge(job.status).class]" variant="outline">
                      {{ getStatusBadge(job.status).label }}
                    </Badge>
                    <DropdownMenu>
                      <DropdownMenuTrigger asChild>
                        <Button variant="ghost" size="icon" class="h-8 w-8 rounded-xl">
                          <MoreVertical class="w-4 h-4 text-muted-foreground" />
                        </Button>
                      </DropdownMenuTrigger>
                      <DropdownMenuContent align="end" class="w-44 rounded-xl p-2 border-border/60">
                        <DropdownMenuItem class="rounded-lg gap-2 cursor-pointer py-2">
                          <Eye class="w-4 h-4 text-primary" />
                          <span class="font-medium text-xs">Lihat Detail</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="job.status === 'pending'" @click="handleApprove(job.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-emerald-600">
                          <CheckCircle class="w-4 h-4" />
                          <span class="font-medium text-xs">Setujui</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="job.status === 'pending'" @click="handleReject(job.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-rose-600">
                          <XCircle class="w-4 h-4" />
                          <span class="font-medium text-xs">Tolak</span>
                        </DropdownMenuItem>
                        <DropdownMenuItem v-if="job.status === 'aktif'" @click="handleClose(job.id)" class="rounded-lg gap-2 cursor-pointer py-2 text-slate-600">
                          <Ban class="w-4 h-4" />
                          <span class="font-medium text-xs">Tutup</span>
                        </DropdownMenuItem>
                      </DropdownMenuContent>
                    </DropdownMenu>
                  </div>
                </div>
                <div class="flex flex-wrap items-center gap-3 mt-1.5 text-xs text-muted-foreground">
                  <span class="flex items-center gap-1"><Users class="w-3 h-3" />{{ job.lamarans_count || 0 }} lamaran</span>
                  <span>{{ job.tipe }}</span>
                  <span v-if="job.deadline">s.d. {{ new Date(job.deadline).toLocaleDateString('id-ID', { day:'numeric', month:'short' }) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="lowongans.links.length > 3" class="px-4 md:px-8 py-4 border-t border-border/40 bg-muted/5">
          <Pagination :links="lowongans.links" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
