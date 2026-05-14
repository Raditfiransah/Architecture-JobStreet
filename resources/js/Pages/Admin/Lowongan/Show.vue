<script setup>
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  ArrowLeft, 
  MapPin, 
  Calendar,
  Briefcase,
  CheckCircle,
  XCircle,
  Ban,
  Clock,
  Building2,
  DollarSign,
  FileText,
  User as UserIcon
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";
import { Badge } from "@/Components/UI/ui/badge";
import { Separator } from "@/Components/UI/ui/separator";

const props = defineProps({
  lowongan: Object,
});

const handleApprove = () => {
  if (confirm("Setujui lowongan ini?")) {
    router.post(route('admin.lowongan.setujui', props.lowongan.id));
  }
};

const handleReject = () => {
  if (confirm("Tolak lowongan ini?")) {
    router.post(route('admin.lowongan.tolak', props.lowongan.id));
  }
};

const handleClose = () => {
  if (confirm("Tutup lowongan ini?")) {
    router.post(route('admin.lowongan.tutup', props.lowongan.id));
  }
};
</script>

<template>
  <Head :title="`Detail Lowongan - ${lowongan.posisi}`" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
         <div class="flex items-center gap-4">
            <Button variant="ghost" size="icon" asChild class="rounded-xl">
               <Link :href="route('admin.lowongan.index')">
                  <ArrowLeft class="w-5 h-5" />
               </Link>
            </Button>
            <div>
               <h1 class="text-2xl font-bold tracking-tight text-foreground">{{ lowongan.posisi }}</h1>
               <p class="text-sm text-muted-foreground">{{ lowongan.perusahaan }}</p>
            </div>
         </div>
         <div class="flex items-center gap-3">
            <template v-if="lowongan.status === 'pending'">
               <Button @click="handleApprove" class="bg-emerald-600 hover:bg-emerald-700 rounded-xl gap-2 h-11 px-6 font-bold text-xs uppercase tracking-wider">
                  <CheckCircle class="w-4 h-4" />
                  Setujui
               </Button>
               <Button @click="handleReject" variant="destructive" class="rounded-xl gap-2 h-11 px-6 font-bold text-xs uppercase tracking-wider">
                  <XCircle class="w-4 h-4" />
                  Tolak
               </Button>
            </template>
            <Button v-if="lowongan.status === 'aktif'" @click="handleClose" variant="outline" class="rounded-xl gap-2 h-11 px-6 font-bold text-xs uppercase tracking-wider border-border/60">
               <Ban class="w-4 h-4 text-rose-500" />
               Tutup Lowongan
            </Button>
         </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
         <div class="lg:col-span-2 space-y-8">
            <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden">
               <CardHeader class="border-b border-border/40 bg-muted/10 px-8 py-5">
                  <CardTitle class="text-md font-bold">Deskripsi Pekerjaan</CardTitle>
               </CardHeader>
               <CardContent class="p-8 space-y-6">
                  <div class="space-y-3">
                     <h3 class="font-bold text-foreground">Tentang Posisi</h3>
                     <p class="text-sm text-muted-foreground leading-relaxed">{{ lowongan.deskripsi }}</p>
                  </div>
                  <Separator class="bg-border/30" />
                  <div class="space-y-3">
                     <h3 class="font-bold text-foreground">Persyaratan</h3>
                     <ul class="list-disc list-inside text-sm text-muted-foreground space-y-2">
                        <li v-for="(item, idx) in lowongan.syarat" :key="idx">{{ item }}</li>
                     </ul>
                  </div>
                  <Separator class="bg-border/30" />
                  <div class="space-y-3">
                     <h3 class="font-bold text-foreground">Tanggung Jawab</h3>
                     <ul class="list-disc list-inside text-sm text-muted-foreground space-y-2">
                        <li v-for="(item, idx) in lowongan.tanggung_jawab" :key="idx">{{ item }}</li>
                     </ul>
                  </div>
               </CardContent>
            </Card>

            <!-- Applicants List -->
            <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden">
               <CardHeader class="border-b border-border/40 px-8 py-5">
                  <CardTitle class="text-md font-bold flex items-center justify-between">
                     <span>Pelamar Masuk</span>
                     <Badge class="bg-primary/10 text-primary hover:bg-primary/20 border-primary/20">{{ lowongan.lamarans?.length || 0 }} Total</Badge>
                  </CardTitle>
               </CardHeader>
               <CardContent class="p-0">
                  <div v-if="lowongan.lamarans?.length > 0" class="divide-y divide-border/40">
                     <div v-for="lamaran in lowongan.lamarans" :key="lamaran.id" class="px-8 py-4 flex items-center justify-between hover:bg-muted/5 transition-colors">
                        <div class="flex items-center gap-4">
                           <div class="w-10 h-10 rounded-full bg-muted/50 flex items-center justify-center shrink-0">
                              <UserIcon class="w-5 h-5 text-muted-foreground" />
                           </div>
                           <div>
                              <p class="text-sm font-bold text-foreground">{{ lamaran.user?.name || 'Anonymous' }}</p>
                              <p class="text-[10px] text-muted-foreground uppercase font-black tracking-wider">{{ lamaran.status }}</p>
                           </div>
                        </div>
                        <div class="text-right">
                           <p class="text-[10px] font-bold text-muted-foreground uppercase">{{ new Date(lamaran.created_at).toLocaleDateString() }}</p>
                           <Link href="#" class="text-xs font-bold text-primary hover:underline mt-1 block">Lihat Detail</Link>
                        </div>
                     </div>
                  </div>
                  <div v-else class="py-12 text-center text-muted-foreground italic text-sm">
                     Belum ada pelamar untuk lowongan ini.
                  </div>
               </CardContent>
            </Card>
         </div>

         <div class="space-y-6">
            <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden">
               <CardHeader class="border-b border-border/40 px-8 py-5 bg-muted/10">
                  <CardTitle class="text-xs font-black uppercase tracking-widest text-muted-foreground">Detail Informasi</CardTitle>
               </CardHeader>
               <CardContent class="p-8 space-y-6">
                  <div class="flex items-start gap-4">
                     <div class="p-2.5 bg-primary/10 text-primary rounded-xl">
                        <DollarSign class="w-5 h-5" />
                     </div>
                     <div>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Gaji Ditawarkan</p>
                        <p class="text-sm font-bold text-foreground">{{ lowongan.gaji }}</p>
                     </div>
                  </div>
                  <div class="flex items-start gap-4">
                     <div class="p-2.5 bg-emerald-500/10 text-emerald-500 rounded-xl">
                        <MapPin class="w-5 h-5" />
                     </div>
                     <div>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Lokasi</p>
                        <p class="text-sm font-bold text-foreground">{{ lowongan.kota }}</p>
                     </div>
                  </div>
                  <div class="flex items-start gap-4">
                     <div class="p-2.5 bg-indigo-500/10 text-indigo-500 rounded-xl">
                        <Briefcase class="w-5 h-5" />
                     </div>
                     <div>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Tipe Kontrak</p>
                        <p class="text-sm font-bold text-foreground">{{ lowongan.tipe }}</p>
                     </div>
                  </div>
                  <div class="flex items-start gap-4">
                     <div class="p-2.5 bg-orange-500/10 text-orange-500 rounded-xl">
                        <Clock class="w-5 h-5" />
                     </div>
                     <div>
                        <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider mb-1">Deadline</p>
                        <p class="text-sm font-bold text-foreground">{{ lowongan.deadline ? new Date(lowongan.deadline).toLocaleDateString() : '-' }}</p>
                     </div>
                  </div>
                  
                  <Separator class="bg-border/30" />
                  
                  <div class="space-y-4">
                     <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-muted-foreground">Status Moderasi</span>
                        <Badge :variant="lowongan.status === 'aktif' ? 'default' : 'outline'">{{ lowongan.status.toUpperCase() }}</Badge>
                     </div>
                     <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-muted-foreground">Diunggah Oleh</span>
                        <div class="flex items-center gap-2">
                           <span class="text-xs font-bold text-foreground">{{ lowongan.user?.name }}</span>
                        </div>
                     </div>
                  </div>
               </CardContent>
            </Card>
            
            <div class="bg-muted/50 border border-border/40 rounded-2xl p-6 text-center space-y-4">
               <h4 class="font-bold text-sm">Butuh Review Lanjutan?</h4>
               <p class="text-xs text-muted-foreground leading-relaxed">Anda dapat menghubungi pengunggah lowongan ini untuk meminta klarifikasi lebih lanjut.</p>
               <Button variant="outline" class="w-full rounded-xl h-10 text-xs font-bold uppercase tracking-wider">
                  Kontak Pengunggah
               </Button>
            </div>
         </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
