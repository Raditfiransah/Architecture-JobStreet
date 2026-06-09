<script setup>
import { ref, watch } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  Filter,
  Building2,
  UserCircle,
  Clock,
  CheckCircle,
  ChevronRight,
  BadgeCheck,
  XCircle,
  AlertTriangle,
} from "lucide-vue-next";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/Components/UI/ui/table";
import { Badge } from "@/Components/UI/ui/badge";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
  profiles: Object,
  pendingSubmissions: Array,
  filters: Object,
});

const type = ref(props.filters.type || "company");

watch(type, () => {
  router.get(route('admin.profiles.index'), { type: type.value }, {
    preserveState: true,
    preserveScroll: true,
  });
});

const statusConfig = (status) => {
  const map = {
    pending:    { label: "Menunggu Verifikasi", class: "bg-orange-500/10 text-orange-500 border-orange-500/20", icon: Clock },
    verified:   { label: "Terverifikasi",        class: "bg-emerald-500/10 text-emerald-500 border-emerald-500/20", icon: BadgeCheck },
    rejected:   { label: "Ditolak",              class: "bg-rose-500/10 text-rose-500 border-rose-500/20", icon: XCircle },
    unverified: { label: "Belum Diverifikasi",   class: "bg-muted text-muted-foreground border-border", icon: AlertTriangle },
  };
  return map[status] || map["unverified"];
};

const displayName = (profile) =>
  profile.company_name ||
  (profile.first_name ? `${profile.first_name} ${profile.last_name ?? ""}`.trim() : profile.user?.name) ||
  "—";

const docCount = (profile) => {
  const fields = [
    profile.identity_document_url,
    profile.license_document_url,
    profile.npwp_document_url,
    profile.akta_document_url,
    profile.siup_document_url,
    profile.pic_document_url,
    profile.domicile_document_url,
    profile.project_ownership_document_url,
  ];
  return fields.filter(Boolean).length;
};

const roleLabel = (type) => {
  const map = {
    company: "Perusahaan",
    arsitek: "Arsitek",
    client: "Client",
  };
  return map[type] || type;
};
</script>

<template>
  <Head title="Moderasi Profil" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div>
        <h1 class="text-3xl font-bold tracking-tight text-foreground">Moderasi Profil</h1>
        <p class="text-muted-foreground mt-1">Klik nama pengguna untuk melihat dokumen dan melakukan verifikasi.</p>
      </div>

      <!-- Pengajuan Verifikasi Terbaru (New Table) -->
      <div v-if="pendingSubmissions && pendingSubmissions.length > 0" class="space-y-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-2">
            <div class="p-2 bg-orange-500/10 rounded-lg">
              <Clock class="w-5 h-5 text-orange-500" />
            </div>
            <h2 class="text-xl font-bold tracking-tight text-foreground">Pengajuan Verifikasi Terbaru</h2>
          </div>
          <Badge variant="outline" class="bg-orange-500/10 text-orange-500 border-orange-500/20 font-bold px-3 py-1">
            {{ pendingSubmissions.length }} Menunggu
          </Badge>
        </div>

        <div class="bg-card border-2 border-orange-100 rounded-2xl shadow-sm overflow-hidden">
          <Table>
            <TableHeader class="bg-orange-50/50">
              <TableRow>
                <TableHead class="font-bold text-xs uppercase tracking-wider py-4">Profil & Peran</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Update Terakhir</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Dokumen</TableHead>
                <TableHead class="w-10" />
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="submission in pendingSubmissions"
                :key="submission.id + submission.type"
                class="group hover:bg-orange-50/30 transition-colors cursor-pointer border-l-4 border-l-transparent hover:border-l-orange-500"
                @click="$inertia.visit(route('admin.profiles.show', { type: submission.type, profile: submission.id }))"
              >
                <TableCell class="py-4">
                  <div class="flex items-center gap-3">
                    <Avatar class="h-10 w-10 rounded-xl border border-border/60">
                      <AvatarImage :src="submission.company_logo_url || submission.user?.avatar_url" />
                      <AvatarFallback class="bg-primary/5 text-primary font-bold text-xs">
                        {{ displayName(submission).charAt(0) }}
                      </AvatarFallback>
                    </Avatar>
                    <div>
                      <p class="font-bold text-sm text-foreground group-hover:text-primary transition-colors">
                        {{ displayName(submission) }}
                      </p>
                      <Badge variant="outline" class="text-[10px] px-1.5 py-0 h-4 mt-1 font-medium bg-muted text-muted-foreground uppercase tracking-tight">
                        {{ roleLabel(submission.type) }}
                      </Badge>
                    </div>
                  </div>
                </TableCell>
                <TableCell>
                  <div class="text-xs text-muted-foreground font-medium flex items-center gap-1.5">
                    <Clock class="w-3 h-3" />
                    {{ new Date(submission.updated_at).toLocaleString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }) }}
                  </div>
                </TableCell>
                <TableCell>
                  <span class="text-sm font-semibold text-foreground">
                    {{ docCount(submission) }}
                  </span>
                  <span class="text-xs text-muted-foreground ml-1">dokumen</span>
                </TableCell>
                <TableCell class="text-right pr-4">
                  <ChevronRight class="w-4 h-4 text-muted-foreground/40 group-hover:text-primary transition-colors" />
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
        </div>
      </div>

      <!-- Tabs / Type Switcher: scrollable on mobile -->
      <div class="overflow-x-auto">
        <div class="flex items-center gap-2 p-1.5 bg-muted/30 border border-border/60 rounded-2xl w-fit min-w-max">
          <button 
            @click="type = 'company'"
            :class="[type === 'company' ? 'bg-white shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground', 'flex items-center gap-2 px-4 sm:px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-300 whitespace-nowrap']"
          >
            <Building2 class="w-4 h-4" />
            Perusahaan
          </button>
          <button 
            @click="type = 'arsitek'"
            :class="[type === 'arsitek' ? 'bg-white shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground', 'flex items-center gap-2 px-4 sm:px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-300 whitespace-nowrap']"
          >
            <UserCircle class="w-4 h-4" />
            Arsitek
          </button>
          <button 
            @click="type = 'client'"
            :class="[type === 'client' ? 'bg-white shadow-sm text-primary' : 'text-muted-foreground hover:text-foreground', 'flex items-center gap-2 px-4 sm:px-6 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-300 whitespace-nowrap']"
          >
            <UserCircle class="w-4 h-4" />
            Client
          </button>
        </div>
      </div>

      <!-- Profiles Table (desktop) / Card List (mobile) -->
      <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
        <!-- Desktop Table -->
        <div class="hidden md:block">
          <Table>
            <TableHeader class="bg-muted/30">
              <TableRow>
                <TableHead class="w-[320px] font-bold text-xs uppercase tracking-wider py-4">Profil</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Status</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Dokumen</TableHead>
                <TableHead class="font-bold text-xs uppercase tracking-wider">Tanggal Verifikasi</TableHead>
                <TableHead class="w-10" />
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow
                v-for="profile in profiles.data"
                :key="profile.id"
                class="group hover:bg-muted/10 transition-colors cursor-pointer"
                @click="$inertia.visit(route('admin.profiles.show', { type, profile: profile.id }))"
              >
                <TableCell class="py-4">
                  <div class="flex items-center gap-3">
                    <Avatar class="h-10 w-10 rounded-xl border border-border/60">
                      <AvatarImage :src="profile.company_logo_url || profile.user?.avatar_url" />
                      <AvatarFallback class="bg-primary/5 text-primary font-bold text-xs">
                        {{ displayName(profile).charAt(0) }}
                      </AvatarFallback>
                    </Avatar>
                    <div class="min-w-0">
                      <p class="font-bold text-sm text-foreground truncate group-hover:text-primary transition-colors">
                        {{ displayName(profile) }}
                      </p>
                      <p class="text-xs text-muted-foreground truncate">
                        {{ profile.industry || profile.specialization || profile.user?.email || '—' }}
                      </p>
                    </div>
                  </div>
                </TableCell>
                <TableCell>
                  <Badge
                    variant="outline"
                    :class="['rounded-lg font-bold text-[10px] uppercase tracking-wider px-2.5 py-1 border flex items-center gap-1.5 w-fit', statusConfig(profile.verification_status).class]"
                  >
                    <component :is="statusConfig(profile.verification_status).icon" class="w-3 h-3" />
                    {{ statusConfig(profile.verification_status).label }}
                  </Badge>
                </TableCell>
                <TableCell>
                  <span class="text-sm font-semibold text-foreground">{{ docCount(profile) }}</span>
                  <span class="text-xs text-muted-foreground ml-1">dokumen</span>
                </TableCell>
                <TableCell>
                  <div v-if="profile.verified_at" class="flex items-center gap-1.5 text-xs text-muted-foreground font-medium">
                    <CheckCircle class="w-3.5 h-3.5 text-emerald-500" />
                    {{ new Date(profile.verified_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                  </div>
                  <div v-else class="flex items-center gap-1.5 text-xs text-muted-foreground italic">
                    <Clock class="w-3.5 h-3.5 text-orange-400" />
                    Belum diverifikasi
                  </div>
                </TableCell>
                <TableCell class="text-right pr-4">
                  <ChevronRight class="w-4 h-4 text-muted-foreground/40 group-hover:text-primary transition-colors" />
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
        </div>

        <!-- Mobile Card List -->
        <div class="md:hidden divide-y divide-border/40">
          <div v-if="profiles.data.length === 0" class="py-16 text-center">
            <p class="text-sm font-bold text-muted-foreground">Tidak ada profil dalam kategori ini.</p>
          </div>
          <div
            v-for="profile in profiles.data"
            :key="profile.id"
            class="p-4 hover:bg-muted/5 transition-colors cursor-pointer"
            @click="$inertia.visit(route('admin.profiles.show', { type, profile: profile.id }))"
          >
            <div class="flex items-start gap-3">
              <Avatar class="h-10 w-10 rounded-xl border border-border/60 shrink-0">
                <AvatarImage :src="profile.company_logo_url || profile.user?.avatar_url" />
                <AvatarFallback class="bg-primary/5 text-primary font-bold text-xs">{{ displayName(profile).charAt(0) }}</AvatarFallback>
              </Avatar>
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-2">
                  <div class="min-w-0">
                    <p class="font-bold text-sm text-foreground truncate">{{ displayName(profile) }}</p>
                    <p class="text-xs text-muted-foreground truncate">{{ profile.industry || profile.specialization || profile.user?.email || '—' }}</p>
                  </div>
                  <ChevronRight class="w-4 h-4 text-muted-foreground/40 shrink-0 mt-0.5" />
                </div>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                  <Badge
                    variant="outline"
                    :class="['rounded-lg font-bold text-[10px] uppercase tracking-wider px-2 py-0.5 border flex items-center gap-1 w-fit', statusConfig(profile.verification_status).class]"
                  >
                    <component :is="statusConfig(profile.verification_status).icon" class="w-3 h-3" />
                    {{ statusConfig(profile.verification_status).label }}
                  </Badge>
                  <span class="text-xs text-muted-foreground">{{ docCount(profile) }} dok</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div v-if="profiles.links.length > 3" class="px-4 md:px-8 py-4 border-t border-border/40 bg-muted/5">
          <Pagination :links="profiles.links" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
