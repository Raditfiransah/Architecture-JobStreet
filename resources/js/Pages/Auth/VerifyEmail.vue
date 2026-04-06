<script setup>
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  status: String,
  email: String,
});

const form = useForm({
  code: '',
});

const resendForm = useForm({});

const verificationStatus = computed(() => props.status);

const submit = () => {
  form.post(route('otp.verify'));
};

const resendOtp = () => {
  resendForm.post(route('otp.resend'));
};
</script>

<template>
  <GuestLayout>
    <Head title="Verifikasi Email" />

    <div class="mb-6 text-center">
      <div class="w-16 h-16 bg-primary/10 text-primary rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
      </div>
      <h1 class="text-2xl font-bold text-foreground">Verifikasi Email Anda</h1>
      <p class="text-sm text-muted-foreground mt-2 leading-relaxed">
        Kode verifikasi 6-digit telah dikirim ke <br>
        <span class="font-semibold text-foreground">{{ email }}</span>
      </p>
    </div>

    <div v-if="verificationStatus" class="mb-4 p-3 rounded-lg bg-primary/10 border border-primary/20 text-sm font-semibold text-primary">
      {{ verificationStatus }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="code" value="Kode Verifikasi" class="text-center" />
        <TextInput
          id="code"
          type="text"
          class="mt-1 block w-full text-center text-3xl tracking-[0.5em] font-mono py-4"
          v-model="form.code"
          required
          autofocus
          maxlength="6"
          placeholder="000000"
          inputmode="numeric"
          pattern="[0-9]*"
        />
        <InputError class="mt-2 text-center" :message="form.errors.code" />
      </div>

      <div class="mt-8">
        <PrimaryButton class="w-full py-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
          Verifikasi Kode
        </PrimaryButton>
      </div>
    </form>

    <div class="mt-8 pt-6 border-t border-border flex flex-col gap-4">
      <div class="flex items-center justify-between text-sm">
        <span class="text-muted-foreground">Tidak menerima kode?</span>
        <button
          @click="resendOtp"
          class="font-semibold text-primary hover:text-primary/80 transition disabled:opacity-50"
          :disabled="resendForm.processing"
        >
          Kirim Ulang
        </button>
      </div>

      <Link
        :href="route('logout')"
        method="post"
        as="button"
        class="text-sm text-muted-foreground hover:text-foreground underline transition text-center"
      >
        Keluar dan Kembali ke Login
      </Link>
    </div>
  </GuestLayout>
</template>
