<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import AvatarUploader from '@/Components/Profile/AvatarUploader.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FlashMessage from '@/Components/Public/FlashMessage.vue';

const page = usePage();
const user = computed(() => page.props.user);

const form = useForm({
    name: user.value.name || '',
    email: user.value.email || '',
    phone: user.value.phone || '',
    location: user.value.location || 'Super Admin', // using location for Jabatan per SKPL
});

const submit = () => {
    form.put(route('admin.profil.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Profil Admin" />

    <ProfileLayout>
        <template #header>
            Profil Administrator
        </template>

        <div class="space-y-6">
            <FlashMessage />

            <!-- Media Section -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Foto Personal</h3>
                
                <AvatarUploader 
                    :current-avatar="user.avatar_url"
                    :upload-url="route('admin.profil.avatar')"
                />
            </div>

            <!-- Profile Info Form -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Informasi Akun</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="name" value="Nama Lengkap" required />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="email" value="Alamat Email" required />
                            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="phone" value="Telp/WhatsApp" />
                            <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>
                        <div>
                            <InputLabel for="location" value="Jabatan Administratif" required />
                            <select id="location" v-model="form.location" class="mt-1 border-gray-300 focus:border-brand focus:ring-brand rounded-md shadow-sm w-full">
                                <option value="Super Admin">Super Admin</option>
                                <option value="Moderator">Moderator</option>
                                <option value="Content Manager">Content Manager</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Simpan Perubahan
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </ProfileLayout>
</template>
