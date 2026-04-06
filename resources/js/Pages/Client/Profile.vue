<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Card, CardContent } from "@/Components/UI/ui/card";
import { 
  Camera, 
  Check, 
  Loader2, 
  User,
  ArrowRight,
  Briefcase
} from "lucide-vue-next";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";

const props = defineProps({
  user: Object,
});

const form = useForm({
  name: props.user.name || '',
  location: props.user.location || '',
  phone: props.user.phone || '',
});

const isDirty = computed(() => form.isDirty);
const isSuccess = ref(false);

const submit = () => {
    isSuccess.value = false;
    form.put(route('client.profile.update'), { 
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
      <Card class="border-border/60 shadow-sm overflow-hidden rounded-2xl bg-white">
        <!-- Banner -->
        <div class="h-48 w-full bg-[#f8fafc] relative flex items-center justify-center border-b border-border/40">
           <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#e2e8f0_1.5px,transparent_1.5px)] [background-size:24px_24px]"></div>
           <div class="relative z-10 flex items-center gap-8 text-slate-300">
              <User class="w-24 h-24" />
              <div class="flex flex-col gap-2">
                 <div class="h-4 w-40 bg-slate-200 rounded"></div>
                 <div class="h-4 w-32 bg-slate-200 rounded"></div>
              </div>
           </div>
           <div class="absolute right-12 bottom-0 h-40 w-64 opacity-50 select-none pointer-events-none hidden md:block">
              <img src="https://illustrations.popsy.co/slate/tech-life.svg" class="h-full w-full object-contain object-bottom" alt="Illustration" />
           </div>
        </div>
        
        <CardContent class="p-0 relative">
           <div class="px-8 pb-10">
              <!-- Avatar -->
              <div class="relative -mt-12 mb-8 flex justify-center lg:justify-start">
                 <div class="relative group">
                    <Avatar class="h-28 w-28 rounded-full border-4 border-white shadow-md">
                       <AvatarImage :src="user?.avatar_url" />
                       <AvatarFallback class="bg-primary/5 text-primary text-3xl font-bold font-display uppercase">
                          {{ userInitials }}
                       </AvatarFallback>
                    </Avatar>
                    <Button variant="secondary" size="icon" class="absolute bottom-0 right-0 h-9 w-9 rounded-full shadow-lg border border-white">
                       <Camera class="w-4 h-4" />
                    </Button>
                 </div>
              </div>

              <form @submit.prevent="submit" class="space-y-10">
                 <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="space-y-2 md:col-span-2">
                       <Label for="name" class="text-[13px] font-bold text-slate-700">Display Name<span class="text-destructive ml-0.5">*</span></Label>
                       <Input 
                          id="name" 
                          v-model="form.name" 
                          class="h-11 rounded-xl border-border/60 bg-white focus:ring-primary/10" 
                       />
                       <p v-if="form.errors.name" class="text-destructive text-xs">{{ form.errors.name }}</p>
                    </div>

                    <div class="space-y-2">
                       <Label for="location" class="text-[13px] font-bold text-slate-700">Location</Label>
                       <Input 
                          id="location" 
                          v-model="form.location" 
                          placeholder="e.g. Malang, East Java"
                          class="h-11 rounded-xl border-border/60 bg-white focus:ring-primary/10" 
                       />
                    </div>

                    <div class="space-y-2">
                       <Label for="phone" class="text-[13px] font-bold text-slate-700">Phone number</Label>
                       <Input 
                          id="phone" 
                          v-model="form.phone" 
                          placeholder="+62 ..."
                          class="h-11 rounded-xl border-border/60 bg-white focus:ring-primary/10" 
                       />
                    </div>
                 </div>

                 <div class="flex items-center justify-between pt-10 border-t border-border/40">
                    <div class="flex items-center gap-2">
                       <Transition
                          enter-active-class="transition duration-300 ease-out"
                          enter-from-class="transform translate-y-2 opacity-0"
                          enter-to-class="transform translate-y-0 opacity-100"
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

      <!-- CTA -->
      <div class="bg-primary text-primary-foreground rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl shadow-primary/10">
         <div class="space-y-1 text-center md:text-left">
            <h3 class="text-lg font-display font-bold text-white">Butuh Arsitek Profesional?</h3>
            <p class="text-sm text-primary-foreground/70">Mulai posting proyek Anda dan dapatkan proposal terbaik.</p>
         </div>
         <Button asChild variant="secondary" size="lg" class="rounded-xl h-12 px-6 font-bold text-xs uppercase tracking-wider whitespace-nowrap bg-white text-primary hover:bg-slate-50">
            <Link :href="route('client.proyek.create')">
               Posting Proyek Baru
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
