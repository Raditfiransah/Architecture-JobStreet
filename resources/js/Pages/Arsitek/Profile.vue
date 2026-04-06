<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
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
  Pencil,
  ArrowRight
} from "lucide-vue-next";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";

const props = defineProps({
  user: Object,
  arsitekProfile: Object,
});

const form = useForm({
  first_name: props.arsitekProfile?.first_name || props.user.name.split(' ')[0] || '',
  last_name: props.arsitekProfile?.last_name || props.user.name.split(' ').slice(1).join(' ') || '',
  status_pekerjaan: props.arsitekProfile?.status_pekerjaan || 'Student',
  location: props.arsitekProfile?.location || '',
  school: props.arsitekProfile?.school || '',
  degree_type: props.arsitekProfile?.degree_type || '',
});

const isDirty = computed(() => form.isDirty);
const isSuccess = ref(false);

const submit = () => {
    isSuccess.value = false;
    form.put(route('arsitek.profil.update'), {
        preserveScroll: true,
        onSuccess: () => {
            isSuccess.value = true;
            setTimeout(() => isSuccess.value = false, 3000);
        }
    });
};

const userInitials = computed(() => {
  return props.user.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
});
</script>

<template>
  <ProfileLayout>
    <Head title="Profil Saya" />

    <div class="space-y-6">
      <!-- Banner & Profile Illustration -->
      <Card class="border-border/60 shadow-[0_2px_4px_rgba(0,0,0,0.02)] overflow-hidden rounded-2xl bg-white">
        <div class="px-8 pt-8 pb-2">
            <h1 class="text-[28px] font-display font-bold text-slate-800 tracking-tight">Profil Utama</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Lengkapi data diri dan riwayat pendidikan Anda.</p>
        </div>
        <CardContent class="p-0 relative">
           <div class="px-8 pb-10 pt-6">
              <!-- Form Sections -->
              <form @submit.prevent="submit" class="space-y-10">
                 <!-- Main Info -->
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="space-y-2">
                       <Label for="first_name" class="text-[13px] font-bold text-slate-700">First Name<span class="text-destructive ml-0.5">*</span></Label>
                       <Input 
                          id="first_name" 
                          v-model="form.first_name" 
                          class="h-11 rounded-xl border-border/60 bg-white focus:ring-primary/10" 
                       />
                       <p v-if="form.errors.first_name" class="text-destructive text-xs">{{ form.errors.first_name }}</p>
                    </div>

                    <div class="space-y-2">
                       <Label for="last_name" class="text-[13px] font-bold text-slate-700">Last Name<span class="text-destructive ml-0.5">*</span></Label>
                       <Input 
                          id="last_name" 
                          v-model="form.last_name" 
                          class="h-11 rounded-xl border-border/60 bg-white focus:ring-primary/10" 
                       />
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
                       <p v-if="form.errors.status_pekerjaan" class="text-destructive text-xs">{{ form.errors.status_pekerjaan }}</p>
                    </div>

                    <div class="space-y-2">
                       <Label for="location" class="text-[13px] font-bold text-slate-700">Location<span class="text-destructive ml-0.5">*</span></Label>
                       <Input 
                          id="location" 
                          v-model="form.location" 
                          placeholder="e.g. Malang, East Java, Indonesia"
                          class="h-11 rounded-xl border-border/60 bg-white focus:ring-primary/10" 
                       />
                       <p v-if="form.errors.location" class="text-destructive text-xs">{{ form.errors.location }}</p>
                    </div>

                    <div class="space-y-2 md:col-span-2 lg:col-span-1">
                       <Label for="school" class="text-[13px] font-bold text-slate-700">School<span class="text-destructive ml-0.5">*</span></Label>
                       <Input 
                          id="school" 
                          v-model="form.school" 
                          placeholder="e.g. Politeknik Negeri Malang"
                          class="h-11 rounded-xl border-border/60 bg-white focus:ring-primary/10" 
                       />
                    </div>

                    <div class="space-y-2 lg:col-span-1">
                       <Label for="degree_type" class="text-[13px] font-bold text-slate-700">Degree type</Label>
                       <Input 
                          id="degree_type" 
                          v-model="form.degree_type" 
                          placeholder="e.g. Bachelor of Architecture"
                          class="h-11 rounded-xl border-border/60 bg-white focus:ring-primary/10" 
                       />
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
                             Profil berhasil diperbarui
                          </div>
                       </Transition>
                    </div>
                    
                    <div class="flex items-center gap-3">
                       <Button variant="ghost" type="button" @click="form.reset()" :disabled="form.processing || !isDirty" class="rounded-xl px-6 h-11 font-bold text-xs uppercase tracking-wider">
                          Cancel
                       </Button>
                       <Button type="submit" :disabled="form.processing || !isDirty" class="rounded-xl px-8 h-11 font-bold text-xs uppercase tracking-wider relative">
                          <span :class="{ 'opacity-0': form.processing }">Save Profile</span>
                          <Loader2 v-if="form.processing" class="w-4 h-4 absolute animate-spin" />
                       </Button>
                    </div>
                 </div>
              </form>
           </div>
        </CardContent>
      </Card>

      <!-- Preview Footer -->
      <div class="bg-primary text-primary-foreground rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl shadow-primary/10">
         <div class="space-y-1 text-center md:text-left">
            <h3 class="text-lg font-display font-bold">Ingin Terlihat Menonjol?</h3>
            <p class="text-sm text-primary-foreground/70">Tambahkan portofolio terbaikmu untuk menarik perhatian biro arsitektur.</p>
         </div>
         <Button asChild variant="secondary" @click="form.post(route('arsitek.profil.preview'))" size="lg" class="rounded-xl h-12 px-6 font-bold text-xs uppercase tracking-wider whitespace-nowrap">
            <Link href="#">
               Lihat Pratinjau Profil
               <ArrowRight class="ml-2 w-4 h-4" />
            </Link>
         </Button>
      </div>
    </div>
  </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
