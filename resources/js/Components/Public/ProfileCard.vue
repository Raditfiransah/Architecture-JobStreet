<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { 
  User, 
  MapPin, 
  Briefcase, 
  Pencil, 
  Settings, 
  History,
  Building2,
  Target
} from "lucide-vue-next";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import { Button } from "@/Components/UI/ui/button";

const props = defineProps({
  user: {
    type: Object,
    required: true
  }
});

const isArsitek = computed(() => props.user.role === 'arsitek');
const isPerusahaan = computed(() => props.user.role === 'perusahaan');
const isClient = computed(() => props.user.role === 'client');

// Extract specific profiles if available, or fallback to user
// Inertia serializes Eloquent relations as snake_case keys (arsitek_profile / company_profile)
// Auth shared props passes the profile as generic 'profile' key
const arsitekProfile = computed(() => 
    props.user.arsitek_profile || props.user.arsitekProfile || props.user.profile || {}
);
const companyProfile = computed(() => 
    props.user.company_profile || props.user.companyProfile || props.user.profile || {}
);

const fullName = computed(() => {
  if (isArsitek.value) {
    if (arsitekProfile.value.first_name && arsitekProfile.value.last_name) {
      return `${arsitekProfile.value.first_name} ${arsitekProfile.value.last_name}`;
    }
  } else if (isPerusahaan.value) {
    return companyProfile.value.company_name || props.user.name;
  }
  return props.user.name;
});

const jobTitle = computed(() => {
  if (isArsitek.value) {
    if (arsitekProfile.value.is_student) return `Student at ${arsitekProfile.value.education_institution || 'Unspecified'}`;
    return arsitekProfile.value.status_pekerjaan || 'Arsitek Profesional';
  } else if (isPerusahaan.value) {
    return companyProfile.value.industry || 'Perusahaan';
  }
  return 'Client Terdaftar';
});

const locationText = computed(() => {
  if (isArsitek.value) return arsitekProfile.value.location || 'Lokasi belum diatur';
  if (isPerusahaan.value) return companyProfile.value.location || 'Lokasi belum diatur';
  return props.user.location || 'Lokasi belum diatur';
});

const editRoute = computed(() => {
  return route(props.user.role + '.profil.edit');
});
</script>

<template>
  <div class="w-full max-w-[320px] bg-card rounded-xl overflow-hidden border border-border mt-2 shadow-lg">
    <div class="p-5 pb-4 border-b border-border">
      <div class="flex items-start justify-between">
        <div class="flex items-center gap-4">
          <Link :href="route(props.user.role + '.profile')" class="block">
            <Avatar class="h-16 w-16 hover:opacity-90 transition-opacity">
              <AvatarImage :src="user.avatar_url" :alt="fullName" />
              <AvatarFallback class="bg-primary/10 text-primary text-lg font-bold">
                {{ fullName.charAt(0).toUpperCase() }}
              </AvatarFallback>
            </Avatar>
          </Link>
          <div class="flex flex-col">
            <Link :href="route(props.user.role + '.profile')" class="hover:text-primary transition-colors">
              <h3 class="text-base font-bold text-foreground leading-tight line-clamp-1" :title="fullName">{{ fullName }}</h3>
            </Link>
            <p class="text-xs text-muted-foreground mt-0.5 line-clamp-1">{{ jobTitle }}</p>
            <div class="flex items-center text-[11px] text-muted-foreground mt-1.5">
              <MapPin class="w-3 h-3 mr-1 shrink-0" />
              <span class="line-clamp-1">{{ locationText }}</span>
            </div>
          </div>
        </div>
        <Link :href="editRoute" class="p-2 hover:bg-muted rounded-lg transition-colors text-muted-foreground hover:text-primary shrink-0">
          <Pencil class="w-4 h-4" />
        </Link>
      </div>
    </div>

    <!-- DYNAMIC MIDDLE SECTION based on role -->
    <div class="px-5 py-4 bg-muted/20 space-y-3 border-b border-border">
      <!-- ARSITEK INFO -->
      <template v-if="isArsitek">
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1">
            <span class="text-[10px] uppercase tracking-wider font-semibold text-muted-foreground">Keahlian</span>
            <p class="text-sm font-medium text-foreground line-clamp-1">{{ arsitekProfile.degree_type || '-' }}</p>
          </div>
          <div class="space-y-1">
            <span class="text-[10px] uppercase tracking-wider font-semibold text-muted-foreground">Edukasi</span>
            <p class="text-sm font-medium text-foreground line-clamp-1">{{ arsitekProfile.education_institution || '-' }}</p>
          </div>
        </div>
      </template>

      <!-- PERUSAHAAN INFO -->
      <template v-else-if="isPerusahaan">
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1">
            <span class="text-[10px] uppercase tracking-wider font-semibold text-muted-foreground">Skala</span>
            <p class="text-sm font-medium text-foreground line-clamp-1">{{ companyProfile.company_size || '-' }}</p>
          </div>
          <div class="space-y-1">
            <span class="text-[10px] uppercase tracking-wider font-semibold text-muted-foreground">Website</span>
            <p class="text-sm font-medium text-foreground line-clamp-1">{{ companyProfile.company_website || '-' }}</p>
          </div>
        </div>
      </template>

      <!-- CLIENT INFO -->
      <template v-else>
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-1">
            <span class="text-[10px] uppercase tracking-wider font-semibold text-muted-foreground">No. Telepon</span>
            <p class="text-sm font-medium text-foreground line-clamp-1">{{ user.phone || '-' }}</p>
          </div>
          <div class="space-y-1">
            <span class="text-[10px] uppercase tracking-wider font-semibold text-muted-foreground">Email Valid</span>
            <p class="text-sm font-medium text-foreground line-clamp-1">{{ user.email_verified_at ? 'Ya' : 'Belum' }}</p>
          </div>
        </div>
      </template>
    </div>

    <!-- DYNAMIC BOTTOM LINKS based on role -->
    <div class="p-2 space-y-1">
      <!-- Perusahaan Links -->
      <template v-if="isPerusahaan">
        <Link :href="route('perusahaan.lowongan.index')" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-primary/5 group transition-colors">
          <Briefcase class="w-4 h-4 mr-3 text-muted-foreground group-hover:text-primary transition-colors" />
          <span class="text-sm font-semibold text-foreground/90 group-hover:text-primary">Kelola Lowongan</span>
        </Link>
        <Link :href="route('perusahaan.pelamar.all')" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-primary/5 group transition-colors">
          <Target class="w-4 h-4 mr-3 text-muted-foreground group-hover:text-primary transition-colors" />
          <span class="text-sm font-semibold text-foreground/90 group-hover:text-primary">Kelola Kandidat</span>
        </Link>
      </template>

      <!-- Client Links -->
      <template v-else-if="isClient">
        <Link :href="route('client.proyek.index')" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-primary/5 group transition-colors">
          <Building2 class="w-4 h-4 mr-3 text-muted-foreground group-hover:text-primary transition-colors" />
          <span class="text-sm font-semibold text-foreground/90 group-hover:text-primary">Kelola Proyek</span>
        </Link>
      </template>

      <!-- Arsitek Links -->
      <template v-else>
        <Link :href="route('arsitek.portofolio.index')" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-primary/5 group transition-colors">
          <Briefcase class="w-4 h-4 mr-3 text-muted-foreground group-hover:text-primary transition-colors" />
          <span class="text-sm font-semibold text-foreground/90 group-hover:text-primary">Kelola Portofolio</span>
        </Link>
        <Link :href="route('arsitek.lamaran.index')" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-primary/5 group transition-colors">
          <History class="w-4 h-4 mr-3 text-muted-foreground group-hover:text-primary transition-colors" />
          <span class="text-sm font-semibold text-foreground/90 group-hover:text-primary">Aktivitas Lamaran</span>
        </Link>
      </template>

      <!-- Shared Link: Settings -->
      <Link :href="editRoute" class="flex items-center px-4 py-2.5 rounded-lg hover:bg-primary/5 group transition-colors">
        <Settings class="w-4 h-4 mr-3 text-muted-foreground group-hover:text-primary transition-colors" />
        <span class="text-sm font-semibold text-foreground/90 group-hover:text-primary">Pengaturan Profil</span>
      </Link>
    </div>
  </div>
</template>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>
