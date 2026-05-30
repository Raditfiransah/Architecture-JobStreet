<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import AvatarUploader from '@/Components/Profile/AvatarUploader.vue';
import DocumentUploader from '@/Components/Profile/DocumentUploader.vue';
import TagInput from '@/Components/Profile/TagInput.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage();
const user = computed(() => page.props.user);
const profile = computed(() => page.props.companyProfile || {});

const form = useForm({
    company_name: profile.value.company_name || user.value.name || '',
    phone: profile.value.phone || user.value.phone || '',
    company_website: profile.value.company_website || '',
    company_desc: profile.value.company_desc || '',
    industry: profile.value.industry || '',
    company_size: profile.value.company_size || '',
    location: profile.value.location || user.value.location || '',
    business_fields: profile.value.business_fields || [],
    founded_year: profile.value.founded_year || '',
    nib_number: profile.value.nib_number || '',
});

const submit = () => {
    form.put(route('perusahaan.profil.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Profil Perusahaan" />

    <ProfileLayout>
        <template #header>
            Profil Perusahaan
        </template>

        <div class="space-y-6">
            <!-- Media Section -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Identitas Perusahaan</h3>
                
                <AvatarUploader 
                    :current-avatar="user.avatar_url || profile.company_logo_url"
                    :upload-url="route('perusahaan.profil.logo')"
                    label="Logo Perusahaan"
                />
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Dokumen Legalitas</h3>
                
                <DocumentUploader
                    type="identity"
                    label="Dokumen NIB / SIUP"
                    description="Wajib diunggah untuk keperluan verifikasi status perusahaan."
                    :upload-url="route('perusahaan.profil.document')"
                    :current-document-url="profile.identity_document_url"
                />
            </div>

            <!-- Profile Info Form -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Informasi Bisnis</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="company_name" value="Nama Perusahaan" required />
                            <TextInput id="company_name" v-model="form.company_name" type="text" class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="form.errors.company_name" />
                        </div>
                        <div>
                            <InputLabel for="phone" value="Telepon Perusahaan" required />
                            <TextInput id="phone" v-model="form.phone" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.phone" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="company_desc" value="Deskripsi Perusahaan" />
                        <textarea 
                            id="company_desc" 
                            v-model="form.company_desc" 
                            rows="4" 
                            class="mt-1 border-gray-300 focus:border-brand focus:ring-brand rounded-md shadow-sm w-full"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.company_desc" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="industry" value="Industri" />
                            <TextInput id="industry" v-model="form.industry" type="text" class="mt-1 block w-full" placeholder="e.g. Konstruksi, Konsultan" />
                            <InputError class="mt-2" :message="form.errors.industry" />
                        </div>
                        <div>
                            <InputLabel for="company_size" value="Ukuran Perusahaan" required />
                            <select id="company_size" v-model="form.company_size" class="mt-1 border-gray-300 focus:border-brand focus:ring-brand rounded-md shadow-sm w-full">
                                <option value="">Pilih ukuran...</option>
                                <option value="1-10 Karyawan">1-10 Karyawan</option>
                                <option value="11-50 Karyawan">11-50 Karyawan</option>
                                <option value="51-200 Karyawan">51-200 Karyawan</option>
                                <option value="201-500 Karyawan">201-500 Karyawan</option>
                                <option value="500+ Karyawan">500+ Karyawan</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.company_size" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="location" value="Alamat Kantor Pusat" required />
                            <TextInput id="location" v-model="form.location" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>
                        <div>
                            <InputLabel for="company_website" value="Website Perusahaan" />
                            <TextInput id="company_website" v-model="form.company_website" type="url" class="mt-1 block w-full" placeholder="https://" />
                            <InputError class="mt-2" :message="form.errors.company_website" />
                        </div>
                    </div>

                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Legalitas & Spesialisasi</h3>
                        
                        <div class="mb-6">
                            <InputLabel for="business_fields" value="Bidang Usaha Layanan" required />
                            <TagInput v-model="form.business_fields" placeholder="Ketik bidang dan tekan Enter (e.g. Arsitektur, Sipil)" class="mt-1" />
                            <InputError class="mt-2" :message="form.errors.business_fields" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="nib_number" value="Nomor Induk Berusaha (NIB)" />
                                <TextInput id="nib_number" v-model="form.nib_number" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.nib_number" />
                            </div>
                            <div>
                                <InputLabel for="founded_year" value="Tahun Berdiri" required />
                                <TextInput id="founded_year" v-model="form.founded_year" type="number" class="mt-1 block w-full" placeholder="YYYY" />
                                <InputError class="mt-2" :message="form.errors.founded_year" />
                            </div>
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
