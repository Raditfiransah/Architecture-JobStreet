<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: '',
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
    <GuestLayout>
        <Head title="Daftar Akun Baru" />

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-ink">Bergabung Sekarang</h1>
            <p class="text-sm text-ink-muted mt-1">Mulai karir arsitektur Anda bersama kami.</p>
        </div>

        <form @submit.prevent="submit">
            <!-- Full Name -->
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

            <!-- Email -->
            <div class="mt-4">
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

            <!-- Role Selection -->
            <div class="mt-4">
                <InputLabel for="role" value="Daftar Sebagai" />
                <SelectInput
                    id="role"
                    class="mt-1 block w-full"
                    v-model="form.role"
                    :options="roles"
                    required
                    placeholder="Pilih kategori Anda"
                />
                <InputError class="mt-2" :message="form.errors.role" />
            </div>

            <!-- Company Fields (Dynamic) -->
            <transition
                enter-active-class="transition ease-out duration-200"
                enter-from-class="opacity-0 -translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 -translate-y-2"
            >
                <div v-if="form.role === 'perusahaan'" class="mt-4 p-4 rounded-xl bg-primary-50 border border-primary-100">
                    <p class="text-[11px] font-bold text-primary-600 uppercase tracking-wider mb-3">Informasi Perusahaan</p>
                    
                    <div>
                        <InputLabel for="company_name" value="Nama Perusahaan" />
                        <TextInput
                            id="company_name"
                            type="text"
                            class="mt-1 block w-full bg-white"
                            v-model="form.company_name"
                            required
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
                            class="mt-1 block w-full bg-white"
                            v-model="form.company_website"
                            autocomplete="url"
                            placeholder="https://perusahaan.com"
                        />
                        <InputError class="mt-2" :message="form.errors.company_website" />
                    </div>
                </div>
            </transition>

            <!-- Password -->
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

            <!-- Confirm Password -->
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

            <!-- Terms -->
            <div class="mt-6">
                <label class="flex items-start cursor-pointer group">
                    <input
                        type="checkbox"
                        name="agree_to_terms"
                        v-model="form.agree_to_terms"
                        class="mt-0.5 rounded border-[#e4ede8] text-primary-300 shadow-sm focus:ring-primary-100 transition"
                        required
                    />
                    <span class="ms-2 text-sm text-ink-muted leading-snug">
                        Saya setuju dengan 
                        <Link href="#" class="text-primary-500 font-medium hover:underline">Syarat & Ketentuan</Link> 
                        serta 
                        <Link href="#" class="text-primary-500 font-medium hover:underline">Kebijakan Privasi</Link>.
                    </span>
                </label>
                <InputError class="mt-2" :message="form.errors.agree_to_terms" />
            </div>

            <div class="mt-8 flex flex-col gap-4">
                <PrimaryButton class="w-full py-4 text-base" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Daftar Sekarang
                </PrimaryButton>

                <p class="text-center text-sm text-ink-muted">
                    Sudah punya akun?
                    <Link :href="route('login')" class="font-bold text-primary-500 hover:text-primary-600 transition">
                        Masuk Disini
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
