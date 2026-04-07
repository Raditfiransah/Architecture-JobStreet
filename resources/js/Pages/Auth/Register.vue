<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
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
  <AuthLayout mode="register">
    <Head title="Daftar Akun Baru" />

    <div class="flex flex-col space-y-2 text-center mb-4">
      <h1 class="text-3xl font-display font-bold tracking-tight">Buat Akun</h1>
      <p class="text-sm text-muted-foreground leading-relaxed">Pilih kategori yang sesuai untuk memulai perjalanan Anda.</p>
    </div>

    <form @submit.prevent="submit" class="grid gap-2 pb-8">
      <!-- Role Selection -->
      <div class="grid gap-2">
        <Label class="text-[10px] font-black uppercase tracking-[0.2em] text-muted-foreground/60 mb-1">Pilih Peran Anda</Label>
        <div class="grid grid-cols-3 gap-2">
          <div 
            v-for="roleOption in roles" 
            :key="roleOption.value"
            @click="form.role = roleOption.value"
            :class="[
              'cursor-pointer flex flex-col items-center justify-center p-3 rounded-xl border transition-all duration-300',
              form.role === roleOption.value 
                ? 'border-primary bg-primary/5 shadow-sm' 
                : 'border-border hover:border-primary/30'
            ]"
          >
             <div 
               :class="[
                 'w-10 h-10 rounded-lg flex items-center justify-center mb-2',
                 form.role === roleOption.value 
                   ? 'bg-primary text-primary-foreground' 
                   : 'bg-muted text-muted-foreground'
               ]"
             >
                <User v-if="roleOption.value === 'arsitek'" class="w-5 h-5" />
                <Building v-else-if="roleOption.value === 'perusahaan'" class="w-5 h-5" />
                <Briefcase v-else-if="roleOption.value === 'client'" class="w-5 h-5" />
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
        <div class="grid gap-2">
          <InputLabel for="name" :value="form.role === 'perusahaan' ? 'Nama Perusahaan' : 'Nama Lengkap'" class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground/60" />
          <TextInput
            id="name"
            type="text"
            class="block w-full h-10"
            v-model="form.name"
            required
            autofocus
            autocomplete="name"
            :placeholder="form.role === 'perusahaan' ? 'Contoh: PT Arsitek Indonesia' : 'Masukkan nama lengkap'"
          />
          <InputError class="mt-1" :message="form.errors.name" />
        </div>

        <div class="grid gap-2">
          <InputLabel for="email" :value="form.role === 'perusahaan' ? 'Email Perusahaan' : 'Email'" class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground/60" />
          <TextInput
            id="email"
            type="email"
            class="block w-full h-10"
            v-model="form.email"
            required
            autocomplete="username"
            placeholder="nama@email.com"
          />
          <InputError class="mt-1" :message="form.errors.email" />
        </div>

        <div class="grid gap-4">
          <div class="grid gap-2">
            <InputLabel for="password" value="Password" class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground/60" />
            <TextInput
              id="password"
              type="password"
              class="block w-full h-10"
              v-model="form.password"
              required
              autocomplete="new-password"
              placeholder="••••••••"
            />
          </div>
          <div class="grid gap-2">
            <InputLabel for="password_confirmation" value="Konfirmasi Password" class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground/60" />
            <TextInput
              id="password_confirmation"
              type="password"
              class="block w-full h-10"
              v-model="form.password_confirmation"
              required
              autocomplete="new-password"
              placeholder="Ulangi password"
            />
          </div>
        </div>
        <InputError class="mt-1" :message="form.errors.password" />
      </div>

      <div class="flex items-start space-x-2 py-2">
        <input
          type="checkbox"
          name="agree_to_terms"
          v-model="form.agree_to_terms"
          class="mt-1 rounded border-border text-primary focus:ring-primary/50"
          required
        />
        <span class="text-xs text-muted-foreground leading-relaxed">
          Saya setuju dengan 
          <Link href="#" class="text-primary font-bold hover:underline">Syarat</Link> & 
          <Link href="#" class="text-primary font-bold hover:underline">Privasi</Link>.
        </span>
      </div>

      <PrimaryButton 
        class="w-full h-10 font-bold uppercase tracking-[0.2em] text-[10px]" 
        :class="{ 'opacity-50': form.processing }" 
        :disabled="form.processing"
      >
        <span v-if="!form.processing">Daftar Sekarang</span>
        <span v-else>Memproses...</span>
      </PrimaryButton>
    </form>
  </AuthLayout>
</template>
