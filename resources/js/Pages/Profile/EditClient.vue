<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import AvatarUploader from '@/Components/Profile/AvatarUploader.vue';
import TagInput from '@/Components/Profile/TagInput.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import FlashMessage from '@/Components/Public/FlashMessage.vue';

const page = usePage();
const user = computed(() => page.props.user);
const profile = computed(() => page.props.clientProfile || {});

const form = useForm({
    name: user.value.name || '',
    email: user.value.email || '',
    phone: user.value.phone || '',
    address: profile.value.address || user.value.location || '',
    client_type: profile.value.client_type || '',
    project_interests: profile.value.project_interests || [],
    budget_range: profile.value.budget_range || '',
});

const submit = () => {
    form.put(route('client.profil.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Profil Anda" />

    <ProfileLayout>
        <template #header>
            Profil Pengguna
        </template>

        <div class="space-y-6">
            <FlashMessage />

            <!-- Media Section -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Foto Profil</h3>
                
                <AvatarUploader 
                    :current-avatar="user.avatar_url"
                    :upload-url="route('client.profil.avatar')"
                />
            </div>

            <!-- Profile Info Form -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Informasi Dasar</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="name" value="Nama Lengkap / Instansi" required />
                            <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>
                        <div>
                            <InputLabel for="email" value="Email" required />
                            <TextInput id="email" v-model="form.email" type="email" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="phone" value="Nomor Handphone" required />
                            <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" placeholder="ex: 081234567890" />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>
                        <div>
                            <InputLabel for="client_type" value="Tipe Client" required />
                            <select id="client_type" v-model="form.client_type" class="mt-1 border-gray-300 focus:border-brand focus:ring-brand rounded-md shadow-sm w-full">
                                <option value="">Pilih tipe...</option>
                                <option value="Individu">Individu / Pribadi</option>
                                <option value="Perusahaan">Perusahaan Berkait / Badan Usaha</option>
                                <option value="Pemerintah">Pemerintah / Institusi Publik</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.client_type" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="address" value="Alamat / Domisili" required />
                        <textarea 
                            id="address" 
                            v-model="form.address" 
                            rows="2" 
                            class="mt-1 border-gray-300 focus:border-brand focus:ring-brand rounded-md shadow-sm w-full"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.address" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="budget_range" value="Range Anggaran Proyek (Rata-rata)" />
                            <select id="budget_range" v-model="form.budget_range" class="mt-1 border-gray-300 focus:border-brand focus:ring-brand rounded-md shadow-sm w-full">
                                <option value="">Pilih range anggaran...</option>
                                <option value="<50jt">&lt;50 Juta</option>
                                <option value="50-200jt">50 - 200 Juta</option>
                                <option value="200-500jt">200 - 500 Juta</option>
                                <option value="500jt-1M">500 Juta - 1 Miliar</option>
                                <option value=">1M">&gt; 1 Miliar</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.budget_range" />
                        </div>
                        <div>
                            <InputLabel for="project_interests" value="Minat Kategori Proyek" />
                            <TagInput v-model="form.project_interests" placeholder="Ketik kategori (e.g. Rumah Tinggal, Kantor)" class="mt-1" />
                            <InputError class="mt-2" :message="form.errors.project_interests" />
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
