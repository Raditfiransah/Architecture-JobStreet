<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import InputError from '@/Components/InputError.vue';
import { 
  User, 
  Building, 
  Briefcase
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Checkbox } from "@/Components/UI/ui/checkbox";
const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'arsitek',
  company_name: '',
  company_website: '',
  agree_to_terms: false,
});

const roles = [
  { value: 'arsitek', label: 'Arsitek' },
  { value: 'perusahaan', label: 'Perusahaan' },
  { value: 'client', label: 'Client' },
];

import { computed } from 'vue';

const isFormValid = computed(() => {
  return form.name.trim() !== '' &&
         form.email.trim() !== '' &&
         form.password !== '' &&
         form.password_confirmation !== '' &&
         form.agree_to_terms === true;
});

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <AuthLayout mode="register">
    <Head title="Daftar Akun Baru" />

    <div class="flex flex-col text-center mb-3">
      <h1 class="text-2xl font-display font-bold tracking-tight">Buat Akun</h1>
      <p class="text-[11px] mt-1 text-muted-foreground leading-relaxed">Pilih kategori yang sesuai untuk memulai perjalanan Anda.</p>
    </div>

    <form @submit.prevent="submit" class="grid gap-4 pb-4">
      <!-- Role Selection -->
      <div class="grid gap-2">
        <Label class="text-[10px] font-bold tracking-widest text-muted-foreground mb-1 uppercase">Pilih Peran Anda</Label>
        <div class="grid grid-cols-3 gap-2">
          <div 
            v-for="roleOption in roles" 
            :key="roleOption.value"
            @click="form.role = roleOption.value"
            :class="[
              'cursor-pointer flex flex-col items-center justify-center p-2 rounded-xl border transition-all duration-300',
              form.role === roleOption.value 
                ? 'border-primary bg-primary/5 shadow-sm' 
                : 'border-border hover:border-primary/30'
            ]"
          >
             <div 
               :class="[
                 'w-8 h-8 rounded-lg flex items-center justify-center mb-1',
                 form.role === roleOption.value 
                   ? 'bg-primary text-primary-foreground' 
                   : 'bg-muted text-muted-foreground'
               ]"
             >
                <User v-if="roleOption.value === 'arsitek'" class="w-4 h-4" />
                <Building v-else-if="roleOption.value === 'perusahaan'" class="w-4 h-4" />
                <Briefcase v-else-if="roleOption.value === 'client'" class="w-4 h-4" />
             </div>
            <span 
              :class="[
                'text-[10px] font-bold text-center leading-tight uppercase tracking-wider',
                form.role === roleOption.value ? 'text-primary' : 'text-muted-foreground'
              ]"
            >
              {{ roleOption.label }}
            </span>
          </div>
        </div>
        <InputError class="mt-1 text-center" :message="form.errors.role" />
      </div>

      <div class="grid gap-4">
        <div class="grid gap-1">
          <Label for="name" class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">
            {{ form.role === 'perusahaan' ? 'Nama Perusahaan' : 'Nama Lengkap' }}
          </Label>
          <Input
            id="name"
            type="text"
            class="h-8 text-xs bg-muted/20 border-border"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
            :placeholder="form.role === 'perusahaan' ? 'Contoh: PT Arsitek Indonesia' : 'Masukkan nama lengkap'"
          />
          <InputError class="mt-1" :message="form.errors.name" />
        </div>

        <div class="grid gap-1">
          <Label for="email" class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">
            {{ form.role === 'perusahaan' ? 'Email Perusahaan' : 'Email' }}
          </Label>
          <Input
            id="email"
            type="email"
            class="h-8 text-xs bg-muted/20 border-border"
            v-model="form.email"
            required
            autocomplete="username"
            placeholder="nama@email.com"
          />
          <InputError class="mt-1" :message="form.errors.email" />
        </div>

        <div class="grid gap-4">
          <div class="grid gap-1">
            <Label for="password" class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Password</Label>
            <Input
              id="password"
              type="password"
              class="h-8 text-xs bg-muted/20 border-border"
              v-model="form.password"
              required
              autocomplete="new-password"
              placeholder="••••••••"
            />
          </div>
          <div class="grid gap-1">
            <Label for="password_confirmation" class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Konfirmasi Password</Label>
            <Input
              id="password_confirmation"
              type="password"
              class="h-8 text-xs bg-muted/20 border-border"
              v-model="form.password_confirmation"
              required
              autocomplete="new-password"
              placeholder="Ulangi password"
            />
          </div>
        </div>
        <InputError class="mt-1" :message="form.errors.password" />
      </div>

      <div class="flex items-center space-x-2 py-1">
        <Checkbox
          id="agree_to_terms"
          v-model="form.agree_to_terms"
          class="border-border/50 data-[state=checked]:bg-primary data-[state=checked]:border-primary"
          required
        />
        <label for="agree_to_terms" class="text-[10px] text-muted-foreground leading-relaxed cursor-pointer select-none">
          Saya setuju dengan 
          <Link href="#" class="text-primary font-bold hover:underline">Syarat</Link> & 
          <Link href="#" class="text-primary font-bold hover:underline">Privasi</Link>.
        </label>
      </div>

      <Button 
        type="submit"
        class="w-full h-8 font-bold uppercase tracking-widest text-[10px] mt-1" 
        :disabled="form.processing || !isFormValid"
      >
        <span v-if="!form.processing">Daftar Sekarang</span>
        <span v-else>Memproses...</span>
      </Button>
    </form>
  </AuthLayout>
</template>
