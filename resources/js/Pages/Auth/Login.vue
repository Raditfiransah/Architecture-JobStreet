<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

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
  <AuthLayout mode="login">
    <Head title="Masuk ke Akun" />

    <div v-if="status" class="mb-6 p-4 rounded-xl bg-primary/10 border border-primary/20 text-sm font-semibold text-primary">
      {{ status }}
    </div>

    <div class="flex flex-col text-center mb-5">
      <h1 class="text-2xl font-display font-bold tracking-tight">Selamat Datang</h1>
      <p class="text-[11px] mt-1 text-muted-foreground leading-relaxed">Masukkan email Anda untuk masuk ke dashboard profesional.</p>
    </div>

    <form @submit.prevent="submit" class="grid gap-4">
      <div class="grid gap-1">
        <Label for="email" class="text-[10px] font-bold tracking-widest text-muted-foreground">Email</Label>
        <Input
          id="email"
          type="email"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
          placeholder="nama@email.com"
          class="h-8 text-xs bg-muted/20 border-border"
        />
        <span v-if="form.errors.email" class="text-[0.8rem] font-medium text-destructive">{{ form.errors.email }}</span>
      </div>

      <div class="grid gap-1">
        <div class="flex items-center justify-between">
          <Label for="password" class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Password</Label>
        </div>
        <Input
          id="password"
          type="password"
          v-model="form.password"
          required
          autocomplete="current-password"
          placeholder="••••••••"
          class="h-8 text-xs bg-muted/20 border-border"
        />
        <span v-if="form.errors.password" class="text-[0.8rem] font-medium text-destructive">{{ form.errors.password }}</span>
      </div>

      <div class="flex items-center justify-between py-1">
        <div class="flex items-center space-x-2">
          <Checkbox 
            id="remember" 
            :checked="form.remember" 
            @update:checked="(val) => form.remember = val"
            class="border-border/50 data-[state=checked]:bg-primary data-[state=checked]:border-primary"
          />
          <label
            for="remember"
            class="text-[10px] font-medium text-muted-foreground cursor-pointer select-none"
          >
            Ingat saya
          </label>
        </div>

        <Link
          v-if="route().has('password.request')"
          :href="route('password.request')"
          class="text-[10px] font-semibold text-primary hover:text-primary/80 transition-colors"
        >
          Lupa sandi?
        </Link>
      </div>

      <Button 
        type="submit"
        class="w-full h-8 font-bold uppercase tracking-widest text-[10px] mt-1"
        :disabled="form.processing"
      >
        <span v-if="!form.processing" class="flex items-center gap-2">
           Masuk Sekarang
           <LogIn class="w-[14px] h-[14px]" />
        </span>
        <span v-else>Memproses...</span>
      </Button>
    </form>
  </AuthLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
