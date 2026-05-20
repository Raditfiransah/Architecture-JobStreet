<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  ArrowLeft, 
  Mail, 
  Phone, 
  MapPin, 
  Calendar,
  ShieldCheck,
  ShieldAlert,
  User as UserIcon,
  Briefcase,
  Building2,
  CheckCircle2
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";
import { Badge } from "@/Components/UI/ui/badge";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";

const props = defineProps({
  user: Object,
});

const getRoleLabel = (role) => {
  const roles = {
    arsitek: "Arsitek",
    perusahaan: "Perusahaan",
    client: "Client",
    admin: "Admin",
  };
  return roles[role] || role;
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
    label: 'Aktif (Email Verif)',
    textClass: 'text-emerald-600',
    dotClass: 'bg-emerald-500'
  };
};
</script>

<template>
  <Head :title="`Detail User - ${user.name}`" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex items-center gap-4">
        <Button variant="ghost" size="icon" asChild class="rounded-xl">
           <Link :href="route('admin.users.index')">
              <ArrowLeft class="w-5 h-5" />
           </Link>
        </Button>
        <div>
          <h1 class="text-2xl font-bold tracking-tight text-foreground">{{ user.name }}</h1>
          <p class="text-sm text-muted-foreground">ID Pengguna: #{{ user.id }}</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
         <!-- Profile Info -->
         <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden h-fit">
            <CardContent class="p-0">
               <div class="p-8 text-center border-b border-border/40">
                  <Avatar class="h-24 w-24 mx-auto mb-4 rounded-2xl border-4 border-background shadow-xl">
                    <AvatarImage :src="user.avatar_url" />
                    <AvatarFallback class="bg-primary/5 text-primary text-2xl font-bold">
                      {{ user.name.charAt(0) }}
                    </AvatarFallback>
                  </Avatar>
                  <h3 class="text-lg font-bold text-foreground">{{ user.name }}</h3>
                  <Badge class="mt-2 rounded-lg font-bold text-[10px] uppercase tracking-wider px-3 py-1 bg-primary/10 text-primary border-primary/20" variant="outline">
                    {{ getRoleLabel(user.role) }}
                  </Badge>
               </div>
               <div class="p-6 space-y-4">
                  <div class="flex items-center gap-3 text-sm">
                     <Mail class="w-4 h-4 text-muted-foreground" />
                     <span class="text-foreground font-medium">{{ user.email }}</span>
                  </div>
                  <div v-if="user.phone" class="flex items-center gap-3 text-sm">
                     <Phone class="w-4 h-4 text-muted-foreground" />
                     <span class="text-foreground font-medium">{{ user.phone }}</span>
                  </div>
                  <div v-if="user.location" class="flex items-center gap-3 text-sm">
                     <MapPin class="w-4 h-4 text-muted-foreground" />
                     <span class="text-foreground font-medium">{{ user.location }}</span>
                  </div>
                  <div class="flex items-center gap-3 text-sm">
                     <Calendar class="w-4 h-4 text-muted-foreground" />
                     <span class="text-muted-foreground">Terdaftar: </span>
                     <span class="text-foreground font-medium">{{ new Date(user.created_at).toLocaleDateString() }}</span>
                  </div>
                  <div class="pt-4 flex items-center justify-between border-t border-border/40">
                     <span class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Account Status</span>
                     <div class="flex items-center gap-2">
                        <div :class="[getUserStatusInfo(user).dotClass, 'w-2 h-2 rounded-full']"></div>
                        <span :class="[getUserStatusInfo(user).textClass, 'text-xs font-black uppercase tracking-wider']">
                           {{ getUserStatusInfo(user).label }}
                        </span>
                     </div>
                  </div>
               </div>
            </CardContent>
         </Card>

         <!-- Details & History -->
         <div class="lg:col-span-2 space-y-8">
            <!-- Role Specific Profile -->
            <Card v-if="user.arsitek_profile" class="border-border/60 shadow-sm rounded-2xl">
               <CardHeader class="border-b border-border/40 bg-muted/10 px-8 py-5">
                  <CardTitle class="text-md font-bold flex items-center gap-2">
                     <Briefcase class="w-5 h-5 text-primary" />
                     Detail Profil Arsitek
                  </CardTitle>
               </CardHeader>
               <CardContent class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Spesialisasi</p>
                     <p class="text-sm font-bold text-foreground">{{ user.arsitek_profile.specialization || '-' }}</p>
                  </div>
                  <div>
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Pengalaman</p>
                     <p class="text-sm font-bold text-foreground">{{ user.arsitek_profile.years_experience || 0 }} Tahun</p>
                  </div>
                  <div class="md:col-span-2">
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Biografi</p>
                     <p class="text-sm text-muted-foreground leading-relaxed">{{ user.arsitek_profile.bio || 'Tidak ada biografi.' }}</p>
                  </div>
                  <div>
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Status Verifikasi</p>
                     <Badge class="mt-1" :variant="user.arsitek_profile.verification_status === 'verified' ? 'default' : 'outline'">
                        {{ user.arsitek_profile.verification_status.toUpperCase() }}
                     </Badge>
                  </div>
               </CardContent>
            </Card>

            <Card v-if="user.company_profile" class="border-border/60 shadow-sm rounded-2xl">
               <CardHeader class="border-b border-border/40 bg-muted/10 px-8 py-5">
                  <CardTitle class="text-md font-bold flex items-center gap-2">
                     <Building2 class="w-5 h-5 text-emerald-500" />
                     Detail Profil Perusahaan
                  </CardTitle>
               </CardHeader>
               <CardContent class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Nama Perusahaan</p>
                     <p class="text-sm font-bold text-foreground">{{ user.company_profile.company_name || '-' }}</p>
                  </div>
                  <div>
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Industri</p>
                     <p class="text-sm font-bold text-foreground">{{ user.company_profile.industry || '-' }}</p>
                  </div>
                  <div class="md:col-span-2">
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Deskripsi</p>
                     <p class="text-sm text-muted-foreground leading-relaxed">{{ user.company_profile.company_desc || 'Tidak ada deskripsi.' }}</p>
                  </div>
               </CardContent>
            </Card>

            <Card v-if="user.client_profile" class="border-border/60 shadow-sm rounded-2xl">
               <CardHeader class="border-b border-border/40 bg-muted/10 px-8 py-5">
                  <CardTitle class="text-md font-bold flex items-center gap-2">
                     <UserIcon class="w-5 h-5 text-orange-500" />
                     Detail Profil Client
                  </CardTitle>
               </CardHeader>
               <CardContent class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Tipe Client</p>
                     <p class="text-sm font-bold text-foreground">{{ user.client_profile.client_type || '-' }}</p>
                  </div>
                  <div>
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Range Budget</p>
                     <p class="text-sm font-bold text-foreground">{{ user.client_profile.budget_range || '-' }}</p>
                  </div>
                  <div class="md:col-span-2">
                     <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mb-1">Alamat</p>
                     <p class="text-sm text-muted-foreground leading-relaxed">{{ user.client_profile.address || '-' }}</p>
                  </div>
               </CardContent>
            </Card>

            <!-- Audit Trail / Simple Logs Mockup -->
            <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden">
               <CardHeader class="border-b border-border/40 px-8 py-5">
                  <CardTitle class="text-md font-bold">Log Aktivitas Terakhir</CardTitle>
               </CardHeader>
               <CardContent class="p-0">
                  <div class="divide-y divide-border/40">
                     <div v-for="i in 3" :key="i" class="px-8 py-4 flex items-center justify-between hover:bg-muted/5 transition-colors">
                        <div class="flex items-center gap-4">
                           <div class="w-8 h-8 rounded-full bg-muted/50 flex items-center justify-center shrink-0">
                              <CheckCircle2 class="w-4 h-4 text-emerald-500" />
                           </div>
                           <p class="text-xs font-medium text-foreground">Berhasil melakukan login sistem</p>
                        </div>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Hanya Saja</p>
                     </div>
                  </div>
               </CardContent>
            </Card>
         </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
