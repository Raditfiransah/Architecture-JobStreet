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
import FlashMessage from '@/Components/Public/FlashMessage.vue';

const page = usePage();
const user = computed(() => page.props.user);
const profile = computed(() => page.props.arsitekProfile || {});

const form = useForm({
    first_name: profile.value.first_name || '',
    last_name: profile.value.last_name || '',
    bio: profile.value.bio || '',
    specialization: profile.value.specialization || '',
    years_experience: profile.value.years_experience || 0,
    status_pekerjaan: profile.value.status_pekerjaan || '',
    location: profile.value.location || user.value.location || '',
    is_student: !!profile.value.is_student,
    education_institution: profile.value.education_institution || '',
    degree_type: profile.value.degree_type || '',
    software_skills: profile.value.software_skills || [],
    license_number: profile.value.license_number || '',
    external_portfolio_url: profile.value.external_portfolio_url || '',
    preferences: profile.value.preferences || [],
});

const submit = () => {
    form.put(route('arsitek.profil.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Edit Profil Arsitek" />

    <ProfileLayout>
        <template #header>
            Profil Arsitek
        </template>

        <div class="space-y-6">
            <FlashMessage />

            <!-- Media Section -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Media Pribadi</h3>
                
                <AvatarUploader 
                    :current-avatar="user.avatar_url"
                    :upload-url="route('arsitek.profil.avatar')"
                />
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Informasi Dokumen</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <DocumentUploader
                        type="identity"
                        label="Dokumen Identitas (KTP/Passport)"
                        description="Wajib untuk verifikasi identitas (Internal saja)"
                        :upload-url="route('arsitek.profil.document')"
                        :current-document-url="profile.identity_document_url"
                    />

                    <DocumentUploader
                        type="license"
                        label="Sertifikat/Lisensi Arsitek"
                        description="Wajib untuk mendapatkan label terverifikasi (cth: STRA)"
                        :upload-url="route('arsitek.profil.document')"
                        :current-document-url="profile.license_document_url"
                    />
                </div>
            </div>

            <!-- Profile Info Form -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-semibold mb-4">Informasi Dasar</h3>
                
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="first_name" value="Nama Depan" required />
                            <TextInput id="first_name" v-model="form.first_name" type="text" class="mt-1 block w-full" autofocus />
                            <InputError class="mt-2" :message="form.errors.first_name" />
                        </div>
                        <div>
                            <InputLabel for="last_name" value="Nama Belakang" />
                            <TextInput id="last_name" v-model="form.last_name" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.last_name" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="bio" value="Bio Singkat" />
                        <textarea 
                            id="bio" 
                            v-model="form.bio" 
                            rows="3" 
                            class="mt-1 border-gray-300 focus:border-brand focus:ring-brand rounded-md shadow-sm w-full"
                        ></textarea>
                        <InputError class="mt-2" :message="form.errors.bio" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="specialization" value="Spesialisasi" required />
                            <TextInput id="specialization" v-model="form.specialization" type="text" class="mt-1 block w-full" placeholder="e.g. Arsitektur Lanskap" />
                            <InputError class="mt-2" :message="form.errors.specialization" />
                        </div>
                        <div>
                            <InputLabel for="years_experience" value="Tahun Pengalaman" required />
                            <TextInput id="years_experience" v-model="form.years_experience" type="number" min="0" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.years_experience" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <InputLabel for="status_pekerjaan" value="Status Pekerjaan" />
                            <select id="status_pekerjaan" v-model="form.status_pekerjaan" class="mt-1 border-gray-300 focus:border-brand focus:ring-brand rounded-md shadow-sm w-full">
                                <option value="">Pilih status...</option>
                                <option value="Freelance">Freelance</option>
                                <option value="Full-time">Full-time</option>
                                <option value="Mencari Pekerjaan">Mencari Pekerjaan</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.status_pekerjaan" />
                        </div>
                        <div>
                            <InputLabel for="location" value="Domisili" />
                            <TextInput id="location" v-model="form.location" type="text" class="mt-1 block w-full" />
                            <InputError class="mt-2" :message="form.errors.location" />
                        </div>
                    </div>

                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Pendidikan</h3>
                        <label class="flex items-center mb-4">
                            <input type="checkbox" v-model="form.is_student" class="rounded border-gray-300 text-brand shadow-sm focus:ring-brand" />
                            <span class="ml-2 text-sm text-gray-600">Saya masih berstatus sebagai mahasiswa</span>
                        </label>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="degree_type" value="Gelar" />
                                <TextInput id="degree_type" v-model="form.degree_type" type="text" class="mt-1 block w-full" placeholder="e.g. S.Ars" />
                                <InputError class="mt-2" :message="form.errors.degree_type" />
                            </div>
                            <div class="md:col-span-2">
                                <InputLabel for="education_institution" value="Institusi Pendidikan" />
                                <TextInput id="education_institution" v-model="form.education_institution" type="text" class="mt-1 block w-full" placeholder="e.g. Institut Teknologi Bandung" />
                                <InputError class="mt-2" :message="form.errors.education_institution" />
                            </div>
                        </div>
                    </div>

                    <div class="border-t pt-6 mt-6">
                        <h3 class="text-lg font-semibold mb-4">Keterampilan & Tautan</h3>
                        
                        <div class="mb-6">
                            <InputLabel for="software_skills" value="Keahlian Software" />
                            <TagInput v-model="form.software_skills" placeholder="Ketik software dan tekan Enter (e.g. AutoCAD, SketchUp)" class="mt-1" />
                            <InputError class="mt-2" :message="form.errors.software_skills" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="license_number" value="Nomor Lisensi (STRA)" />
                                <TextInput id="license_number" v-model="form.license_number" type="text" class="mt-1 block w-full" />
                                <InputError class="mt-2" :message="form.errors.license_number" />
                            </div>
                            <div>
                                <InputLabel for="external_portfolio_url" value="Tautan Portofolio Eksternal" />
                                <TextInput id="external_portfolio_url" v-model="form.external_portfolio_url" type="url" class="mt-1 block w-full" placeholder="https://" />
                                <InputError class="mt-2" :message="form.errors.external_portfolio_url" />
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
