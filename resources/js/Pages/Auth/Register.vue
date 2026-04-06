<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { 
  User, 
  Building, 
  Briefcase
} from "lucide-vue-next";
import { Label } from "@/Components/UI/ui/label";
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
  { value: 'client', label: 'Client (Pemberi Proyek)' },
];

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  });
};
</script>

<template>
  <GuestLayout :hide-logo="true">
    <Head title="Daftar Akun Baru" />

    <div class="mb-10 text-center">
      <h1 class="text-3xl font-bold text-foreground tracking-tight leading-none mb-3">Buat Akun Anda</h1>
      <p class="text-muted-foreground mt-2">Pilih kategori yang sesuai untuk memulai perjalanan Anda.</p>
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <div class="space-y-3">
        <Label class="text-xs font-bold uppercase tracking-widest text-muted-foreground">Peran</Label>
        <div class="grid grid-cols-3 gap-3">
          <div 
            v-for="roleOption in roles" 
            :key="roleOption.value"
            @click="form.role = roleOption.value"
            :class="[
              'cursor-pointer flex flex-col items-center justify-center p-4 rounded-xl border-2 transition-colors',
              form.role === roleOption.value 
                ? 'border-primary bg-primary/5' 
                : 'border-border hover:border-primary/30'
            ]"
          >
             <div 
               :class="[
                 'w-12 h-12 rounded-lg flex items-center justify-center mb-3',
                 form.role === roleOption.value 
                   ? 'bg-primary text-primary-foreground' 
                   : 'bg-muted text-muted-foreground'
               ]"
             >
                <User v-if="roleOption.value === 'arsitek'" class="w-6 h-6" />
                <Building v-else-if="roleOption.value === 'perusahaan'" class="w-6 h-6" />
                <Briefcase v-else-if="roleOption.value === 'client'" class="w-6 h-6" />
             </div>
            <span 
              :class="[
                'text-xs font-bold text-center leading-tight',
                form.role === roleOption.value ? 'text-primary' : 'text-muted-foreground'
              ]"
            >
              {{ roleOption.label }}
            </span>
          </div>
        </div>
        <InputError class="mt-2 text-center" :message="form.errors.role" />
      </div>

      <div>
        <InputLabel for="name" value="Nama Lengkap" />
        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full"
          v-model="form.name"
          required
          autofocus
          autocomplete="name"
          placeholder="Masukkan nama lengkap"
        />
        <InputError class="mt-2" :message="form.errors.name" />
      </div>

      <div>
        <InputLabel for="email" value="Email" />
        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autocomplete="username"
          placeholder="nama@email.com"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 -translate-y-2"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-2"
      >
        <div v-if="form.role === 'perusahaan'" class="mt-4 p-4 rounded-xl bg-primary/5 border border-primary/20">
          <p class="text-xs font-bold text-primary uppercase tracking-wider mb-3">Informasi Perusahaan</p>
          <div>
            <InputLabel for="company_name" value="Nama Perusahaan" />
            <TextInput
              id="company_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.company_name"
              :required="form.role === 'perusahaan'"
              autocomplete="organization"
              placeholder="Contoh: PT Arsitek Indonesia"
            />
            <InputError class="mt-2" :message="form.errors.company_name" />
          </div>
          <div class="mt-4">
            <InputLabel for="company_website" value="Website (Opsional)" />
            <TextInput
              id="company_website"
              type="url"
              class="mt-1 block w-full"
              v-model="form.company_website"
              autocomplete="url"
              placeholder="https://perusahaan.com"
            />
            <InputError class="mt-2" :message="form.errors.company_website" />
          </div>
        </div>
      </transition>

      <div class="mt-4">
        <InputLabel for="password" value="Password" />
        <TextInput
          id="password"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password"
          required
          autocomplete="new-password"
          placeholder="Minimal 8 karakter"
        />
        <InputError class="mt-2" :message="form.errors.password" />
      </div>

      <div class="mt-4">
        <InputLabel for="password_confirmation" value="Konfirmasi Password" />
        <TextInput
          id="password_confirmation"
          type="password"
          class="mt-1 block w-full"
          v-model="form.password_confirmation"
          required
          autocomplete="new-password"
          placeholder="Ulangi password"
        />
        <InputError class="mt-2" :message="form.errors.password_confirmation" />
      </div>

      <div class="mt-6">
        <label class="flex items-start cursor-pointer group">
          <input
            type="checkbox"
            name="agree_to_terms"
            v-model="form.agree_to_terms"
            class="mt-0.5 rounded border-border text-primary focus:ring-primary/50"
            required
          />
          <span class="ms-2 text-sm text-muted-foreground leading-snug">
            Saya setuju dengan 
            <Link href="#" class="text-primary font-medium hover:underline">Syarat & Ketentuan</Link> 
            serta 
            <Link href="#" class="text-primary font-medium hover:underline">Kebijakan Privasi</Link>.
          </span>
        </label>
        <InputError class="mt-2" :message="form.errors.agree_to_terms" />
      </div>

      <div class="mt-8 flex flex-col gap-4">
        <PrimaryButton class="w-full py-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Daftar Sekarang
        </PrimaryButton>

        <p class="text-center text-sm text-muted-foreground">
          Sudah punya akun?
          <Link :href="route('login')" class="font-semibold text-primary hover:text-primary">
            Masuk Disini
          </Link>
        </p>
      </div>
    </form>
  </GuestLayout>
</template>
