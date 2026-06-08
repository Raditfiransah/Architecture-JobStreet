<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import VerificationBadge from '@/Components/Profile/VerificationBadge.vue';
import { 
    MapPin, 
    Briefcase, 
    GraduationCap,
    Code,
    FileText,
    Globe,
    Calendar,
    Plus
} from 'lucide-vue-next';

const props = defineProps({
    arsitek: {
        type: Object,
        required: true
    },
    isPublic: {
        type: Boolean,
        default: true
    },
    isAdmin: {
        type: Boolean,
        default: false
    }
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const isClient = computed(() => user.value?.role === 'client');

const profile = computed(() => props.arsitek.arsitek_profile || {});

const handleHire = () => {
    if (!user.value) {
        router.get(route('login'));
        return;
    }
    if (isClient.value) {
        router.get(route('client.proyek.create'));
    } else {
        router.get(route('home'));
    }
};
</script>

<template>
    <Head :title="`Profil Arsitek - ${arsitek.name}`" />

    <PublicLayout>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="h-32 sm:h-48 bg-gray-100"></div>
                
                <div class="px-6 sm:px-10 py-6 relative">
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8 mb-6">
                        <div class="-mt-20 sm:-mt-24 w-32 h-32 sm:w-40 sm:h-40 rounded-full border-4 border-white shadow-md bg-gray-100 overflow-hidden flex-shrink-0">
                            <img v-if="arsitek.avatar_url" :src="arsitek.avatar_url" class="w-full h-full object-cover" />
                            <div v-else class="w-full h-full bg-gray-100"></div>
                        </div>
                        
                        <div class="flex-1 text-center sm:text-left sm:pt-4">
                            <h1 class="text-3xl font-bold text-gray-900">{{ arsitek.name }}</h1>
                            <p class="text-lg text-gray-600 font-medium mt-1">{{ profile.specialization || 'Arsitek' }}</p>
                            
                            <div class="mt-3 flex flex-wrap items-center justify-center sm:justify-start gap-4">
                                <VerificationBadge :status="profile.verification_status" />
                                
                                <div v-if="profile.status_pekerjaan" class="flex items-center gap-1.5 text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                    <Briefcase class="w-4 h-4" />
                                    {{ profile.status_pekerjaan }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex-shrink-0 flex sm:flex-col gap-3">
                            <button @click="handleHire" class="px-6 py-2.5 bg-black text-white font-semibold rounded-lg shadow-sm hover:bg-neutral-800 transition flex items-center gap-2">
                                <Plus class="w-4 h-4" />
                                Posting Proyek
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- About Quick Info -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900 border-b pb-2">Informasi Umum</h3>
                        
                        <div v-if="arsitek.location" class="flex items-start gap-3">
                            <MapPin class="w-5 h-5 text-gray-400 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">Domisili</p>
                                <p class="text-sm text-gray-600">{{ arsitek.location }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <Calendar class="w-5 h-5 text-gray-400 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">Pengalaman</p>
                                <p class="text-sm text-gray-600">{{ profile.years_experience || 0 }} Tahun</p>
                            </div>
                        </div>

                        <div v-if="profile.license_number" class="flex items-start gap-3">
                            <FileText class="w-5 h-5 text-gray-400 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">Nomor Registrasi (STRA)</p>
                                <p class="text-sm text-gray-600">{{ profile.license_number }}</p>
                            </div>
                        </div>
                        
                        <div v-if="profile.external_portfolio_url" class="flex items-start gap-3">
                            <Globe class="w-5 h-5 text-gray-400 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">Situs Web / Portofolio Eksternal</p>
                                <a :href="profile.external_portfolio_url" target="_blank" rel="noopener noreferrer" class="text-sm text-blue-600 hover:underline break-all">
                                    Lihat Portofolio Eksternal
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Education -->
                    <div v-if="profile.education_institution" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-4">
                        <h3 class="font-semibold text-gray-900 border-b pb-2">Riwayat Pendidikan</h3>
                        
                        <div class="flex items-start gap-3">
                            <GraduationCap class="w-5 h-5 text-gray-400 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ profile.education_institution }}</p>
                                <p class="text-sm text-gray-600">{{ profile.degree_type }}</p>
                                <p v-if="profile.is_student" class="text-xs text-blue-600 mt-1 inline-block bg-blue-50 px-2 py-0.5 rounded">Mahasiswa Aktif</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Bio -->
                    <div v-if="profile.bio" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Tentang Saya</h3>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-wrap">{{ profile.bio }}</p>
                    </div>

                    <!-- Skills -->
                    <div v-if="profile.software_skills && profile.software_skills.length > 0" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <Code class="w-5 h-5 text-gray-500" />
                            Keahlian Software
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span 
                                v-for="(skill, idx) in profile.software_skills" 
                                :key="idx"
                                class="px-3 py-1 bg-gray-50 border border-gray-200 text-gray-700 text-sm rounded-lg"
                            >
                                {{ skill }}
                            </span>
                        </div>
                    </div>

                    <!-- Private Contacts (Only visible if logged in and not public) -->
                    <div v-if="!isPublic && (arsitek.email || arsitek.phone)" class="bg-white rounded-2xl shadow-sm border border-blue-100 p-6 bg-blue-50/30">
                        <h3 class="font-semibold text-gray-900 mb-4">Informasi Kontak (Privat)</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-if="arsitek.email" class="p-3 bg-white border border-gray-100 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Email</p>
                                <p class="text-gray-900 font-medium">{{ arsitek.email }}</p>
                            </div>
                            <div v-if="arsitek.phone" class="p-3 bg-white border border-gray-100 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Ponsel / WhatsApp</p>
                                <p class="text-gray-900 font-medium">{{ arsitek.phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
