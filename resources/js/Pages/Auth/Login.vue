<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Checkbox } from "@/Components/UI/ui/checkbox";
import { LogIn, ArrowRight } from "lucide-vue-next";

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

        <div v-if="status" class="mb-6 p-4 rounded-xl bg-primary/10 border border-primary/20 text-sm font-bold text-primary animate-in fade-in slide-in-from-top-4 duration-500">
            {{ status }}
        </div>

        <div class="mb-10 text-center md:text-left">
            <h1 class="text-3xl font-display font-black text-foreground tracking-tight leading-none mb-3">Selamat Datang Kembali</h1>
            <p class="text-[15px] font-medium text-muted-foreground leading-relaxed">Masuk untuk mengelola profil profesional dan lamaran arsitektur Anda.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="email" class="text-xs font-black uppercase tracking-widest text-muted-foreground/80">Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@email.com"
                    class="h-12 rounded-xl bg-muted/30 border-border/50 focus:bg-background focus:border-primary/50 transition-all duration-300"
                />
                <InputError :message="form.errors.email" />
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <Label for="password" class="text-xs font-black uppercase tracking-widest text-muted-foreground/80">Password</Label>
                    <Link
                        v-if="route().has('password.request')"
                        :href="route('password.request')"
                        class="text-xs font-bold text-primary hover:text-primary/80 transition-colors"
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
                    class="h-12 rounded-xl bg-muted/30 border-border/50 focus:bg-background focus:border-primary/50 transition-all duration-300"
                />
                <InputError :message="form.errors.password" />
            </div>

            <div class="flex items-center space-x-2 py-2">
                <Checkbox 
                    id="remember" 
                    :checked="form.remember" 
                    @update:checked="(val) => form.remember = val"
                    class="rounded-md border-border/50 data-[state=checked]:bg-primary data-[state=checked]:border-primary"
                />
                <label
                    for="remember"
                    class="text-sm font-bold text-muted-foreground cursor-pointer select-none"
                >
                    Ingat saya di perangkat ini
                </label>
            </div>

            <div class="pt-4 space-y-6">
                <Button 
                    type="submit"
                    class="w-full h-14 rounded-2xl text-base font-bold shadow-xl shadow-primary/20 group"
                    :disabled="form.processing"
                >
                    Masuk Sekarang
                    <LogIn class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" />
                </Button>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-border/50"></div>
                    </div>
                    <div class="relative flex justify-center text-xs uppercase tracking-widest font-black text-muted-foreground/40">
                        <span class="bg-card px-4">Atau</span>
                    </div>
                </div>

                <p class="text-center text-sm font-medium text-muted-foreground">
                    Belum punya akun profesional?
                    <Link :href="route('register')" class="font-black text-primary hover:text-primary/80 transition-colors inline-flex items-center gap-1 group/reg">
                        Daftar Gratis
                        <ArrowRight class="w-4 h-4 group-hover/reg:translate-x-0.5 transition-transform" />
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
