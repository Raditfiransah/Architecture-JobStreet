<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import VerificationBadge from '@/Components/Profile/VerificationBadge.vue';
import { 
    MapPin, 
    Briefcase, 
    Users,
    FileText,
    Globe,
    Calendar
} from 'lucide-vue-next';

const props = defineProps({
    perusahaan: {
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

const profile = computed(() => props.perusahaan.companyProfile || props.perusahaan.company_profile || {});
</script>

<template>
    <Head :title="`Profil Perusahaan - ${perusahaan.name}`" />

    <PublicLayout>
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Header Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Banner -->
                <div class="h-32 sm:h-48 bg-gradient-to-r from-gray-200 to-gray-300 overflow-hidden">
                    <img
                        v-if="perusahaan.banner_url"
                        :src="perusahaan.banner_url"
                        class="w-full h-full object-cover"
                        alt="Background profil perusahaan"
                    />
                </div>
                
                <div class="px-6 sm:px-10 py-6 relative">
                    <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 sm:gap-8 -mt-20 sm:-mt-24 mb-6">
                        <div class="w-32 h-32 sm:w-40 sm:h-40 rounded-2xl border-4 border-white shadow-md bg-gray-100 overflow-hidden flex-shrink-0">
                            <img v-if="profile.company_logo_url || perusahaan.avatar_url" :src="profile.company_logo_url || perusahaan.avatar_url" class="w-full h-full object-contain p-2" />
                            <div v-else class="w-full h-full bg-gray-100"></div>
                        </div>
                        
                        <div class="flex-1 text-center sm:text-left">
                            <h1 class="text-3xl font-bold text-gray-900">{{ perusahaan.name }}</h1>
                            <p class="text-lg text-gray-600 font-medium mt-1">{{ profile.industry || 'Perusahaan' }}</p>
                            
                            <div class="mt-3 flex flex-wrap items-center justify-center sm:justify-start gap-4">
                                <VerificationBadge :status="profile.verification_status" />
                                
                                <div v-if="profile.company_size" class="flex items-center gap-1.5 text-sm text-gray-600 bg-gray-100 px-3 py-1 rounded-full">
                                    <Users class="w-4 h-4" />
                                    {{ profile.company_size }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex-shrink-0 mt-4 sm:mt-0">
                            <a v-if="profile.company_website" :href="profile.company_website" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-6 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg shadow-sm hover:bg-gray-50 transition">
                                <Globe class="w-5 h-5" />
                                Kunjungi Website
                            </a>
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
                        
                        <div v-if="perusahaan.location" class="flex items-start gap-3">
                            <MapPin class="w-5 h-5 text-gray-400 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">Alamat Kantor Pusat</p>
                                <p class="text-sm text-gray-600">{{ perusahaan.location }}</p>
                            </div>
                        </div>

                        <div v-if="profile.founded_year" class="flex items-start gap-3">
                            <Calendar class="w-5 h-5 text-gray-400 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">Tahun Berdiri</p>
                                <p class="text-sm text-gray-600">{{ profile.founded_year }}</p>
                            </div>
                        </div>

                        <div v-if="profile.nib_number" class="flex items-start gap-3">
                            <FileText class="w-5 h-5 text-gray-400 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900">NIB (Nomor Induk Berusaha)</p>
                                <p class="text-sm text-gray-600">{{ profile.nib_number }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Desc -->
                    <div v-if="profile.company_desc" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4">Profil Perusahaan</h3>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-wrap">{{ profile.company_desc }}</p>
                    </div>

                    <!-- Fields -->
                    <div v-if="profile.business_fields && profile.business_fields.length > 0" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <Briefcase class="w-5 h-5 text-gray-500" />
                            Bidang Usaha Layanan
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <span 
                                v-for="(field, idx) in profile.business_fields" 
                                :key="idx"
                                class="px-3 py-1 bg-emerald-50 border border-emerald-100 text-emerald-800 font-medium text-sm rounded-lg"
                            >
                                {{ field }}
                            </span>
                        </div>
                    </div>

                    <!-- Private Contacts (Only visible if logged in and not public) -->
                    <!-- We use perusahaan.email locally, but if hidden by controller it won't be available -->
                    <div v-if="!isPublic && (perusahaan.email || perusahaan.phone)" class="bg-white rounded-2xl shadow-sm border border-emerald-100 p-6 bg-emerald-50/30">
                        <h3 class="font-semibold text-gray-900 mb-4">Kontak Bisnis</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div v-if="perusahaan.email" class="p-3 bg-white border border-gray-100 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Email Resmi</p>
                                <p class="text-gray-900 font-medium">{{ perusahaan.email }}</p>
                            </div>
                            <div v-if="perusahaan.phone" class="p-3 bg-white border border-gray-100 rounded-lg">
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider mb-1">Telepon</p>
                                <p class="text-gray-900 font-medium">{{ perusahaan.phone }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
