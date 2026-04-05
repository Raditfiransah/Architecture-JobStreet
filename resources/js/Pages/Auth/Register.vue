<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { 
    User, 
    Building2, 
    Briefcase,
    CheckCircle2
} from "lucide-vue-next";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'arsitek', // Default to arsitek
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

        <div class="mb-10 text-center">
            <h1 class="text-3xl font-extrabold tracking-tight text-ink">Buat Akun Anda</h1>
            <p class="text-ink-muted mt-2">Pilih kategori yang sesuai untuk memulai perjalanan Anda.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Role Selection Cards -->
            <div class="grid grid-cols-3 gap-3 mb-10">
                <div 
                    v-for="roleOption in roles" 
                    :key="roleOption.value"
                    @click="form.role = roleOption.value"
                    :class="[
                        'relative cursor-pointer group flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all duration-300 active:scale-95',
                        form.role === roleOption.value 
                            ? 'border-primary bg-primary/5 shadow-lg shadow-primary/10' 
                            : 'border-border/50 bg-white hover:border-primary/30 hover:bg-muted/30'
                    ]"
                >
                    <div 
                        :class="[
                            'w-12 h-12 rounded-xl flex items-center justify-center mb-3 transition-colors duration-300',
                            form.role === roleOption.value 
                                ? 'bg-primary text-white' 
                                : 'bg-muted text-muted-foreground group-hover:bg-primary/20 group-hover:text-primary'
                        ]"
                    >
                        <User v-if="roleOption.value === 'arsitek'" class="w-6 h-6" />
                        <Building2 v-else-if="roleOption.value === 'perusahaan'" class="w-6 h-6" />
                        <Briefcase v-else-if="roleOption.value === 'client'" class="w-6 h-6" />
                    </div>
                    
                    <span 
                        :class="[
                            'text-[12px] font-bold text-center leading-tight',
                            form.role === roleOption.value ? 'text-primary' : 'text-muted-foreground group-hover:text-foreground'
                        ]"
                    >
                        {{ roleOption.label }}
                    </span>

                    <!-- Check Badge -->
                    <div 
                        v-if="form.role === roleOption.value"
                        class="absolute -top-1.5 -right-1.5 bg-primary text-white rounded-full p-0.5 shadow-sm animate-in zoom-in duration-200"
                    >
                        <CheckCircle2 class="w-4 h-4" />
                    </div>
                </div>
            </div>
            
            <InputError class="mt-2 text-center" :message="form.errors.role" />

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
                <PrimaryButton class="w-full py-4 text-base shadow-lg shadow-primary/20" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
