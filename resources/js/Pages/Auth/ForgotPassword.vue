<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
  status: String,
});

const form = useForm({
  email: '',
});

const submit = () => {
  form.post(route('password.email'));
};
</script>

<template>
  <GuestLayout>
    <Head title="Lupa Password" />

    <div class="mb-6">
      <h1 class="text-2xl font-bold text-foreground">Lupa Password?</h1>
      <p class="text-sm text-muted-foreground mt-2 leading-relaxed">
        Jangan khawatir. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
      </p>
    </div>

    <div v-if="status" class="mb-4 p-3 rounded-lg bg-primary/10 border border-primary/20 text-sm font-semibold text-primary">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" />
        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
          placeholder="nama@email.com"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="mt-8 flex flex-col gap-4">
        <PrimaryButton class="w-full py-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Kirim Tautan Reset
        </PrimaryButton>

        <Link :href="route('login')" class="text-center text-sm font-medium text-muted-foreground hover:text-foreground">
          Kembali ke Halaman Masuk
        </Link>
      </div>
    </form>
  </GuestLayout>
</template>
