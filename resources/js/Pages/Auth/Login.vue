<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Checkbox } from "@/Components/UI/ui/checkbox";
import { LogIn, ChevronRight } from "lucide-vue-next";

defineProps({
  status: String,
});

const form = useForm({
  email: '',
  password: '',
  remember: false,
});

const submit = () => {
  form.post(route('login'), {
    onFinish: () => form.reset('password'),
  });
};
</script>

<template>
  <GuestLayout :hide-logo="true">
    <Head title="Masuk ke Akun" />

    <div v-if="status" class="mb-6 p-4 rounded-xl bg-primary/10 border border-primary/20 text-sm font-semibold text-primary">
      {{ status }}
    </div>

    <div class="mb-10 text-center md:text-left">
      <h1 class="text-3xl font-display font-bold text-foreground tracking-tight leading-none mb-3">Selamat Datang Kembali</h1>
      <p class="text-sm text-muted-foreground leading-relaxed">Masuk untuk mengelola profil profesional dan lamaran arsitektur Anda.</p>
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <div class="space-y-2">
        <Label for="email" class="text-xs font-bold uppercase tracking-widest text-muted-foreground">Email</Label>
        <Input
          id="email"
          type="email"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
          placeholder="nama@email.com"
          class="h-12 bg-muted/20 border-border"
        />
        <InputError :message="form.errors.email" />
      </div>

      <div class="space-y-2">
        <div class="flex items-center justify-between">
          <Label for="password" class="text-xs font-bold uppercase tracking-widest text-muted-foreground">Password</Label>
          <Link
            v-if="route().has('password.request')"
            :href="route('password.request')"
            class="text-xs font-semibold text-primary hover:text-primary/80"
          >
            Lupa password?
          </Link>
        </div>
        <Input
          id="password"
          type="password"
          v-model="form.password"
          required
          autocomplete="current-password"
          placeholder="••••••••"
          class="h-12 bg-muted/20 border-border"
        />
        <InputError :message="form.errors.password" />
      </div>

      <div class="flex items-center space-x-2 py-2">
        <Checkbox 
          id="remember" 
          :checked="form.remember" 
          @update:checked="(val) => form.remember = val"
          class="border-border/50 data-[state=checked]:bg-primary data-[state=checked]:border-primary"
        />
        <label
          for="remember"
          class="text-sm font-medium text-muted-foreground cursor-pointer select-none"
        >
          Ingat saya di perangkat ini
        </label>
      </div>

      <div class="pt-4 space-y-6">
        <Button 
          type="submit"
          class="w-full h-12 font-semibold"
          :disabled="form.processing"
        >
          Masuk Sekarang
          <LogIn class="ml-2 w-5 h-5" />
        </Button>

        <div class="relative py-4">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-border"></div>
          </div>
          <div class="relative flex justify-center text-xs uppercase tracking-widest font-bold text-muted-foreground/40">
            <span class="bg-card px-4">Atau</span>
          </div>
        </div>

         <p class="text-center text-sm font-medium text-muted-foreground">
           Belum punya akun profesional?
           <Link :href="route('register')" class="font-semibold text-primary hover:text-primary/80 inline-flex items-center gap-1 group/reg">
             Daftar Gratis
             <ChevronRight class="w-4 h-4 group-hover/reg:translate-x-0.5" />
           </Link>
         </p>
      </div>
    </form>
  </GuestLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
