<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, onMounted } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Textarea } from "@/Components/UI/ui/textarea";
import { 
  Select, 
  SelectContent, 
  SelectItem, 
  SelectTrigger, 
  SelectValue 
} from "@/Components/UI/ui/select";
import { Card, CardContent } from "@/Components/UI/ui/card";
import { 
  Camera, 
  Check, 
  Loader2, 
  Image as ImageIcon,
  Save
} from "lucide-vue-next";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";

const props = defineProps({
  user: Object,
  arsitekProfile: Object,
  companyProfile: Object,
});

const page = usePage();
const user = computed(() => props.user || page.props.auth.user);
const role = computed(() => user.value.role);

// Dynamic form initialization based on role
const form = useForm({
  // Arsitek fields
  first_name: props.arsitekProfile?.first_name || user.value.name?.split(' ')[0] || '',
  last_name: props.arsitekProfile?.last_name || user.value.name?.split(' ').slice(1).join(' ') || '',
  status_pekerjaan: props.arsitekProfile?.status_pekerjaan || 'Student',
  location: props.arsitekProfile?.location || user.value.location || '',
  school: props.arsitekProfile?.school || '',
  degree_type: props.arsitekProfile?.degree_type || '',
  
  // Perusahaan fields
  company_name: props.companyProfile?.company_name || user.value.name || '',
  industry: props.companyProfile?.industry || '',
  company_size: props.companyProfile?.company_size || '1-10',
  website: props.companyProfile?.company_website || '',
  description: props.companyProfile?.company_desc || '',
  
  // Client fields
  name: user.value.name || '',
  phone: user.value.phone || '',
});

const isDirty = computed(() => form.isDirty);
const isSuccess = ref(false);

const updateRoute = computed(() => {
  if (role.value === 'arsitek') return route('arsitek.profil.update');
  if (role.value === 'perusahaan') return route('perusahaan.profil.update');
  return route('client.profile.update');
});

const submit = () => {
    isSuccess.value = false;
    form.put(updateRoute.value, {
        preserveScroll: true,
        onSuccess: () => {
            isSuccess.value = true;
            setTimeout(() => isSuccess.value = false, 3000);
        }
    });
};

// Cover Image / Background handling (UI Only for now)
const coverInput = ref(null);
const coverPreview = ref(null);

const triggerCoverUpload = () => coverInput.value.click();

const handleCoverChange = (e) => {
  const file = e.target.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => coverPreview.value = e.target.result;
    reader.readAsDataURL(file);
    // Actually handle upload if desired or just keep it in UI for now
  }
};

const userInitials = computed(() => {
  if (!user.value.name) return 'U';
  return user.value.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
});
</script>

<template>
  <ProfileLayout>
    <Head title="Pengaturan Profil" />

    <div class="space-y-6">
      <!-- Enhanced Header with Banner/Cover Edit -->
      <Card class="border-border/60 shadow-[0_2px_4px_rgba(0,0,0,0.02)] overflow-hidden rounded-2xl bg-white">
        <!-- Banner / Background Section -->
        <div class="h-48 w-full relative group">
           <div class="absolute inset-0 bg-slate-100 overflow-hidden">
             <!-- Placeholder or Preview -->
             <img v-if="coverPreview" :src="coverPreview" class="w-full h-full object-cover" />
             <div v-else class="w-full h-full bg-[#f8fafc] opacity-50 flex items-center justify-center">
                <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#e2e8f0_1.5px,transparent_1.5px)] [background-size:24px_24px]"></div>
                <ImageIcon class="w-12 h-12 text-slate-300" />
             </div>
           </div>

           <!-- Edit Background Button -->
           <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer" @click="triggerCoverUpload">
              <div class="bg-white/90 backdrop-blur px-4 py-2 rounded-full flex items-center gap-2 text-sm font-bold shadow-lg">
                <Camera class="w-4 h-4" />
                <span>Ganti Background</span>
              </div>
           </div>
           
           <input type="file" ref="coverInput" class="hidden" @change="handleCoverChange" accept="image/*" />
        </div>
        
        <CardContent class="p-0 relative">
           <div class="px-8 pb-10">
              <!-- Header Text (Replicating the clean hub style) -->
              <div class="pt-8 pb-2">
                  <h1 class="text-[28px] font-display font-bold text-slate-800 tracking-tight">
                    {{ role === 'perusahaan' ? 'Profil Bisnis' : 'Profil Utama' }}
                  </h1>
                  <p class="text-sm font-medium text-slate-500 mt-1">
                    {{ role === 'perusahaan' ? 'Lengkapi identitas perusahaan Anda untuk menarik klien.' : 'Lengkapi data diri dan riwayat pendidikan Anda.' }}
                  </p>
              </div>

              <!-- Form Sections -->
              <form @submit.prevent="submit" class="space-y-10 mt-8">
                 
                 <!-- ARSITEK FORM -->
                 <div v-if="role === 'arsitek'" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="space-y-2">
                       <Label for="first_name" class="text-[13px] font-bold text-slate-700">First Name<span class="text-destructive ml-0.5">*</span></Label>
                       <Input id="first_name" v-model="form.first_name" class="h-11 rounded-xl border-border/60 bg-white" />
                       <p v-if="form.errors.first_name" class="text-destructive text-xs">{{ form.errors.first_name }}</p>
                    </div>
                    <div class="space-y-2">
                       <Label for="last_name" class="text-[13px] font-bold text-slate-700">Last Name<span class="text-destructive ml-0.5">*</span></Label>
                       <Input id="last_name" v-model="form.last_name" class="h-11 rounded-xl border-border/60 bg-white" />
                       <p v-if="form.errors.last_name" class="text-destructive text-xs">{{ form.errors.last_name }}</p>
                    </div>
                    <div class="space-y-2">
                       <Label for="status_pekerjaan" class="text-[13px] font-bold text-slate-700">Employment status<span class="text-destructive ml-0.5">*</span></Label>
                       <Select v-model="form.status_pekerjaan">
                          <SelectTrigger id="status_pekerjaan" class="h-11 rounded-xl border-border/60 bg-white">
                             <SelectValue placeholder="Select status" />
                          </SelectTrigger>
                          <SelectContent class="rounded-xl p-1">
                             <SelectItem value="Student" class="rounded-lg">Student</SelectItem>
                             <SelectItem value="Freelance" class="rounded-lg">Freelance</SelectItem>
                             <SelectItem value="Full-time" class="rounded-lg">Full-time</SelectItem>
                             <SelectItem value="Seeking Opportunities" class="rounded-lg">Seeking Opportunities</SelectItem>
                          </SelectContent>
                       </Select>
                    </div>
                    <div class="space-y-2">
                       <Label for="location" class="text-[13px] font-bold text-slate-700">Location<span class="text-destructive ml-0.5">*</span></Label>
                       <Input id="location" v-model="form.location" class="h-11 rounded-xl border-border/60 bg-white" />
                    </div>
                    <div class="space-y-2">
                       <Label for="school" class="text-[13px] font-bold text-slate-700">School</Label>
                       <Input id="school" v-model="form.school" class="h-11 rounded-xl border-border/60 bg-white" />
                    </div>
                    <div class="space-y-2">
                       <Label for="degree_type" class="text-[13px] font-bold text-slate-700">Degree type</Label>
                       <Input id="degree_type" v-model="form.degree_type" class="h-11 rounded-xl border-border/60 bg-white" />
                    </div>
                 </div>

                 <!-- PERUSAHAAN FORM -->
                 <div v-else-if="role === 'perusahaan'" class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="space-y-2 md:col-span-2">
                       <Label for="company_name" class="text-[13px] font-bold text-slate-700">Nama Perusahaan<span class="text-destructive ml-0.5">*</span></Label>
                       <Input id="company_name" v-model="form.company_name" class="h-11 rounded-xl border-border/60 bg-white" />
                    </div>
                    <div class="space-y-2">
                       <Label for="industry" class="text-[13px] font-bold text-slate-700">Bidang Industri</Label>
                       <Input id="industry" v-model="form.industry" class="h-11 rounded-xl border-border/60 bg-white" placeholder="Arsitektur, Konstruksi, dll" />
                    </div>
                    <div class="space-y-2">
                       <Label for="company_size" class="text-[13px] font-bold text-slate-700">Jumlah Karyawan</Label>
                       <Select v-model="form.company_size">
                          <SelectTrigger id="company_size" class="h-11 rounded-xl border-border/60 bg-white">
                             <SelectValue placeholder="Pilih ukuran" />
                          </SelectTrigger>
                          <SelectContent class="rounded-xl p-1">
                             <SelectItem value="1-10" class="rounded-lg">1-10 Karyawan</SelectItem>
                             <SelectItem value="11-50" class="rounded-lg">11-50 Karyawan</SelectItem>
                             <SelectItem value="51-200" class="rounded-lg">51-200 Karyawan</SelectItem>
                             <SelectItem value="200+" class="rounded-lg">200+ Karyawan</SelectItem>
                          </SelectContent>
                       </Select>
                    </div>
                    <div class="space-y-2">
                       <Label for="location" class="text-[13px] font-bold text-slate-700">Lokasi Kantor</Label>
                       <Input id="location" v-model="form.location" class="h-11 rounded-xl border-border/60 bg-white" />
                    </div>
                    <div class="space-y-2">
                       <Label for="website" class="text-[13px] font-bold text-slate-700">Website</Label>
                       <Input id="website" v-model="form.website" class="h-11 rounded-xl border-border/60 bg-white" placeholder="https://..." />
                    </div>
                    <div class="space-y-2 md:col-span-2">
                       <Label for="description" class="text-[13px] font-bold text-slate-700">Tentang Perusahaan</Label>
                       <Textarea id="description" v-model="form.description" class="rounded-xl border-border/60 bg-white min-h-[120px]" />
                    </div>
                 </div>

                 <!-- CLIENT FORM -->
                 <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="space-y-2 md:col-span-2">
                       <Label for="name" class="text-[13px] font-bold text-slate-700">Nama Lengkap<span class="text-destructive ml-0.5">*</span></Label>
                       <Input id="name" v-model="form.name" class="h-11 rounded-xl border-border/60 bg-white" />
                    </div>
                    <div class="space-y-2">
                       <Label for="phone" class="text-[13px] font-bold text-slate-700">Nomor Telepon</Label>
                       <Input id="phone" v-model="form.phone" class="h-11 rounded-xl border-border/60 bg-white" />
                    </div>
                    <div class="space-y-2">
                       <Label for="location" class="text-[13px] font-bold text-slate-700">Lokasi Dominan</Label>
                       <Input id="location" v-model="form.location" class="h-11 rounded-xl border-border/60 bg-white" />
                    </div>
                 </div>

                 <!-- Footer Actions -->
                 <div class="flex items-center justify-between pt-10 border-t border-border/40">
                    <div class="flex items-center gap-2">
                       <Transition
                          enter-active-class="transition duration-300 ease-out"
                          enter-from-class="transform translate-y-2 opacity-0"
                          enter-to-class="transform translate-y-0 opacity-100"
                          leave-active-class="transition duration-200 ease-in"
                          leave-from-class="transform translate-y-0 opacity-100"
                          leave-to-class="transform translate-y-2 opacity-0"
                       >
                          <div v-if="isSuccess" class="flex items-center gap-1.5 text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full text-xs font-bold">
                             <Check class="w-3.5 h-3.5" />
                             Profil berhasil disimpan
                          </div>
                       </Transition>
                    </div>
                    
                    <div class="flex items-center gap-3">
                       <Button variant="ghost" type="button" @click="form.reset()" :disabled="form.processing || !isDirty" class="rounded-xl px-6 h-11 font-bold text-xs uppercase tracking-wider">
                          Reset
                       </Button>
                       <Button type="submit" :disabled="form.processing || !isDirty" class="rounded-xl px-8 h-11 font-bold text-xs uppercase tracking-wider relative">
                          <span :class="{ 'opacity-0': form.processing }">Simpan Perubahan</span>
                          <Loader2 v-if="form.processing" class="w-4 h-4 absolute animate-spin" />
                       </Button>
                    </div>
                 </div>
              </form>
           </div>
        </CardContent>
      </Card>
    </div>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
