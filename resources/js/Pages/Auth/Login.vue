<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: {
        type: String,
    },
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
    <GuestLayout>
        <Head title="Masuk ke Akun" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <div class="mb-8">
            <h1 class="text-2xl font-bold text-ink">Selamat Datang Kembali</h1>
            <p class="text-sm text-ink-muted mt-1">Masuk untuk mengelola profil dan lamaran Anda.</p>
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

            <div class="mt-5">
                <div class="flex items-center justify-between">
                    <InputLabel for="password" value="Password" />
                    <Link
                        v-if="route().has('password.request')"
                        :href="route('password.request')"
                        class="text-xs font-medium text-primary-500 hover:text-primary-600 transition"
                    >
                        Lupa password?
                    </Link>
                </div>
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center cursor-pointer">
                    <input
                        type="checkbox"
                        name="remember"
                        v-model="form.remember"
                        class="rounded border-[#e4ede8] text-primary-300 shadow-sm focus:ring-primary-100 transition"
                    />
                    <span class="ms-2 text-sm text-ink-muted">Ingat saya</span>
                </label>
            </div>

            <div class="mt-8 flex flex-col gap-4">
                <PrimaryButton class="w-full py-4 text-base" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Masuk Sekarang
                </PrimaryButton>

                <p class="text-center text-sm text-ink-muted">
                    Belum punya akun?
                    <Link :href="route('register')" class="font-bold text-primary-500 hover:text-primary-600 transition">
                        Daftar Gratis
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
