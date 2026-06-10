<script setup>
import { ref, computed } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Button } from "@/Components/UI/ui/button";
import { Key, Bell, Trash2, ShieldAlert } from 'lucide-vue-next';

const page = usePage();
const user = computed(() => page.props.user || page.props.auth?.user || {});
const role = computed(() => user.value?.role);

// Form Password
const passwordForm = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    const routeName = `${role.value}.pengaturan.password`;
    passwordForm.put(route(routeName), {
        preserveScroll: true,
        onSuccess: () => passwordForm.reset(),
        onError: () => {
            if (passwordForm.errors.password) {
                passwordForm.reset('password', 'password_confirmation');
            }
            if (passwordForm.errors.current_password) {
                passwordForm.reset('current_password');
            }
        }
    });
};

// Form Notifikasi (hanya Arsitek)
const notificationForm = useForm({
    email_review: true,
    email_proposal: true,
    in_app: true,
});

const updateNotifications = () => {
    notificationForm.put(route('arsitek.pengaturan.notifikasi'), {
        preserveScroll: true,
    });
};

// Form Hapus Akun (hanya Arsitek)
const confirmingUserDeletion = ref(false);
const deleteForm = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    deleteForm.reset();
};

const deleteUser = () => {
    deleteForm.delete(route('arsitek.pengaturan.delete'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => deleteForm.reset(),
    });
};
</script>

<template>
    <Head title="Pengaturan Akun" />

    <ProfileLayout>
        <template #header>
            Pengaturan Akun
        </template>

        <div class="space-y-8 max-w-4xl">
            <!-- Header section -->
            <div class="flex flex-col gap-1">
                <h1 class="text-2xl font-bold tracking-tight text-slate-900 font-display">Pengaturan Keamanan & Akun</h1>
                <p class="text-sm text-slate-500">Kelola kata sandi, preferensi notifikasi, dan pengaturan privasi akun Anda.</p>
            </div>

            <!-- Password Update Card -->
            <div class="bg-white rounded-2xl shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden transition-all duration-200 hover:shadow-[0_4px_16px_rgba(0,0,0,0.06)]">
                <div class="p-6 sm:p-8 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-primary/10 text-primary rounded-xl">
                            <Key class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 font-display">Ganti Kata Sandi</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Pastikan akun Anda menggunakan kata sandi yang kuat untuk menjaga keamanan.</p>
                        </div>
                    </div>
                </div>
                
                <form @submit.prevent="updatePassword" class="p-6 sm:p-8 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-3">
                            <InputLabel for="current_password" value="Kata Sandi Saat Ini" required />
                            <TextInput
                                id="current_password"
                                ref="currentPasswordInput"
                                v-model="passwordForm.current_password"
                                type="password"
                                class="mt-1 block w-full bg-slate-50/50 focus:bg-white transition-colors"
                                autocomplete="current-password"
                                placeholder="••••••••"
                            />
                            <InputError :message="passwordForm.errors.current_password" class="mt-2" />
                        </div>

                        <div class="md:col-span-3 border-t border-dashed border-slate-100 pt-4"></div>

                        <div class="md:col-span-3">
                            <InputLabel for="password" value="Kata Sandi Baru" required />
                            <TextInput
                                id="password"
                                ref="passwordInput"
                                v-model="passwordForm.password"
                                type="password"
                                class="mt-1 block w-full bg-slate-50/50 focus:bg-white transition-colors"
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                            />
                            <InputError :message="passwordForm.errors.password" class="mt-2" />
                        </div>

                        <div class="md:col-span-3">
                            <InputLabel for="password_confirmation" value="Konfirmasi Kata Sandi Baru" required />
                            <TextInput
                                id="password_confirmation"
                                v-model="passwordForm.password_confirmation"
                                type="password"
                                class="mt-1 block w-full bg-slate-50/50 focus:bg-white transition-colors"
                                autocomplete="new-password"
                                placeholder="Ulangi kata sandi baru"
                            />
                            <InputError :message="passwordForm.errors.password_confirmation" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-4 border-t border-slate-100">
                        <transition
                            enter-active-class="transition ease-out duration-300"
                            enter-from-class="opacity-0"
                            enter-to-class="opacity-100"
                            leave-active-class="transition ease-in duration-200"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="passwordForm.recentlySuccessful" class="text-sm text-emerald-600 font-medium flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Berhasil disimpan.
                            </p>
                        </transition>

                        <Button 
                            type="submit" 
                            :disabled="passwordForm.processing"
                            class="ml-auto rounded-xl px-5 h-10 shadow-sm"
                        >
                            Perbarui Kata Sandi
                        </Button>
                    </div>
                </form>
            </div>

            <!-- Notification preferences (Only for Arsitek) -->
            <div v-if="role === 'arsitek'" class="bg-white rounded-2xl shadow-[0_2px_8px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden transition-all duration-200 hover:shadow-[0_4px_16px_rgba(0,0,0,0.06)]">
                <div class="p-6 sm:p-8 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-primary/10 text-primary rounded-xl">
                            <Bell class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-slate-900 font-display">Preferensi Notifikasi</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Atur bagaimana dan kapan Anda ingin menerima notifikasi dari platform.</p>
                        </div>
                    </div>
                </div>

                <form @submit.prevent="updateNotifications" class="p-6 sm:p-8 space-y-6">
                    <div class="space-y-4">
                        <label class="flex items-start gap-3.5 p-3.5 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group">
                            <input 
                                type="checkbox" 
                                v-model="notificationForm.email_review"
                                class="mt-1 rounded border-slate-300 text-primary focus:ring-primary h-4.5 w-4.5" 
                            />
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">Notifikasi Status Lamaran</span>
                                <span class="text-xs text-slate-500">Kirim email ketika lamaran pekerjaan Anda ditinjau atau diperbarui statusnya oleh perusahaan.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3.5 p-3.5 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group">
                            <input 
                                type="checkbox" 
                                v-model="notificationForm.email_proposal"
                                class="mt-1 rounded border-slate-300 text-primary focus:ring-primary h-4.5 w-4.5" 
                            />
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">Notifikasi Proposal Proyek</span>
                                <span class="text-xs text-slate-500">Kirim email ketika ada tanggapan atau undangan proposal proyek baru dari client.</span>
                            </div>
                        </label>

                        <label class="flex items-start gap-3.5 p-3.5 rounded-xl hover:bg-slate-50 transition-colors cursor-pointer group">
                            <input 
                                type="checkbox" 
                                v-model="notificationForm.in_app"
                                class="mt-1 rounded border-slate-300 text-primary focus:ring-primary h-4.5 w-4.5" 
                            />
                            <div class="flex flex-col">
                                <span class="text-sm font-semibold text-slate-900 group-hover:text-primary transition-colors">Notifikasi Dalam Aplikasi</span>
                                <span class="text-xs text-slate-500">Tampilkan balon merah dan pop-up notifikasi langsung di sudut kanan atas menu profil Anda.</span>
                            </div>
                        </label>
                    </div>

                    <div class="flex items-center justify-between gap-4 pt-4 border-t border-slate-100">
                        <transition
                            enter-active-class="transition ease-out duration-300"
                            enter-from-class="opacity-0"
                            enter-to-class="opacity-100"
                            leave-active-class="transition ease-in duration-200"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="notificationForm.recentlySuccessful" class="text-sm text-emerald-600 font-medium flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                Preferensi diperbarui.
                            </p>
                        </transition>

                        <Button 
                            type="submit" 
                            :disabled="notificationForm.processing"
                            class="ml-auto rounded-xl px-5 h-10 shadow-sm"
                        >
                            Simpan Preferensi
                        </Button>
                    </div>
                </form>
            </div>

            <!-- Danger Zone (Only for Arsitek) -->
            <div v-if="role === 'arsitek'" class="bg-red-50/30 rounded-2xl border border-red-100 overflow-hidden">
                <div class="p-6 sm:p-8 border-b border-red-100/60 bg-red-50/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2.5 bg-red-100 text-red-600 rounded-xl">
                            <Trash2 class="w-5 h-5" />
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-red-950 font-display">Zona Bahaya</h2>
                            <p class="text-xs text-red-700/80 mt-0.5">Tindakan berikut bersifat permanen dan tidak dapat dibatalkan.</p>
                        </div>
                    </div>
                </div>

                <div class="p-6 sm:p-8 space-y-5">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="space-y-1">
                            <h3 class="text-sm font-bold text-slate-900">Hapus Akun Permanen</h3>
                            <p class="text-xs text-slate-500 max-w-xl">Semua data portofolio, lamaran, dan identitas Anda akan dihapus secara permanen dari basis data kami.</p>
                        </div>
                        <Button 
                            type="button" 
                            variant="destructive"
                            class="rounded-xl px-5 h-10 shadow-sm shrink-0 font-semibold"
                            @click="confirmUserDeletion"
                        >
                            Hapus Akun Saya
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom modal for delete confirmation -->
        <div v-if="confirmingUserDeletion" class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <!-- Modal backdrop -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="closeModal"></div>

            <!-- Modal Content -->
            <div class="relative bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100 z-10 overflow-hidden animate-in fade-in zoom-in duration-200">
                <div class="flex items-start gap-4">
                    <div class="p-3 bg-red-100 text-red-600 rounded-xl shrink-0">
                        <ShieldAlert class="w-6 h-6" />
                    </div>
                    <div class="space-y-2">
                        <h3 class="text-lg font-bold text-slate-950 font-display">Apakah Anda benar-benar yakin?</h3>
                        <p class="text-sm text-slate-500 leading-relaxed">
                            Setelah akun Anda dihapus, semua data profil, portofolio, lamaran, dan proposal Anda akan dihapus secara permanen. Silakan masukkan kata sandi Anda untuk mengonfirmasi.
                        </p>
                    </div>
                </div>

                <form @submit.prevent="deleteUser" class="mt-6 space-y-4">
                    <div>
                        <InputLabel for="delete_password" value="Kata Sandi Konfirmasi" class="sr-only" />
                        <TextInput
                            id="delete_password"
                            v-model="deleteForm.password"
                            type="password"
                            class="block w-full bg-slate-50 focus:bg-white transition-colors"
                            placeholder="Masukkan kata sandi Anda"
                            @keyup.enter="deleteUser"
                            required
                            autofocus
                        />
                        <InputError :message="deleteForm.errors.password" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3">
                        <Button 
                            type="button" 
                            variant="outline"
                            class="rounded-xl px-4 h-10 border-slate-200"
                            @click="closeModal"
                        >
                            Batal
                        </Button>
                        <Button 
                            type="submit" 
                            variant="destructive"
                            class="rounded-xl px-5 h-10 font-semibold shadow-sm"
                            :disabled="deleteForm.processing"
                        >
                            Ya, Hapus Akun
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </ProfileLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
