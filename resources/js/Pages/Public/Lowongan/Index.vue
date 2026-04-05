<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

const props = defineProps({
    title: String,
    jobs: Array,
    filters: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const searchQuery = ref(props.filters?.q || "");
const locationQuery = ref(props.filters?.l || "");

// Selection State
const selectedJob = ref(props.jobs && props.jobs.length > 0 ? props.jobs[0] : null);

// Watch for props change (e.g. after search) to reset selectedJob if needed
watch(() => props.jobs, (newJobs) => {
    if (newJobs?.length > 0) {
        if (!newJobs.find(j => j.id === selectedJob.value?.id)) {
            selectedJob.value = newJobs[0];
        }
    } else {
        selectedJob.value = null;
    }
});

const handleSearch = () => {
    router.get(route("lowongan.index"), { 
        q: searchQuery.value,
        l: locationQuery.value 
    }, { 
        preserveState: true,
        replace: true 
    });
};

const selectJob = (job) => {
    selectedJob.value = job;
};

const showMore = ref(false);
</script>

<template>
    <PublicLayout :show-search="true" :show-footer="false">
        <Head :title="title" />

        <main class="flex-1 w-full max-w-[1440px] mx-auto flex flex-col md:flex-row overflow-hidden h-[calc(100vh-165px)]">
            <!-- Sidebar -->
            <aside class="w-full md:w-[360px] lg:w-[420px] shrink-0 border-r border-[#e4ede8] bg-white overflow-y-auto px-4 py-4">
                <div class="mb-4 px-2 flex items-center justify-between text-[13px]">
                    <span class="text-ink-muted">{{ jobs?.length || 0 }} lowongan ditemukan</span>
                </div>

                <div class="space-y-3">
                    <div
                        v-for="job in jobs"
                        :key="job.id"
                        @click="selectJob(job)"
                        :class="[
                            'p-4 rounded-xl border transition-all cursor-pointer relative group',
                            selectedJob?.id === job.id 
                                ? 'bg-[#f0f7f1] border-[#00a032] ring-1 ring-[#00a032]' 
                                : 'bg-white border-[#e4ede8] hover:border-ink-muted'
                        ]"
                    >
                        <div class="flex gap-4">
                            <div :class="['w-10 h-10 rounded-lg flex items-center justify-center font-bold text-lg shrink-0', 
                                job.id % 2 === 0 ? 'bg-ink text-white' : 'bg-primary-300 text-white']">
                                {{ job.inisial }}
                            </div>
                            <div class="flex-1 min-w-0 pr-6">
                                <p class="text-[13px] text-ink-muted flex items-center gap-1.5">
                                    {{ job.perusahaan }} 
                                </p>
                                <h3 class="text-[15px] font-bold text-ink group-hover:underline mt-0.5 leading-snug">{{ job.posisi }}</h3>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    <span class="text-[12px] bg-surface-muted px-2 py-0.5 rounded-md text-ink-muted border border-[#e4ede8]">{{ job.tipe }}</span>
                                    <span class="text-[12px] font-semibold text-[#00a032]">{{ job.gaji }}</span>
                                </div>
                                <p class="text-[13px] text-ink-muted mt-1.5 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                    {{ job.kota }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Detail -->
            <section class="flex-1 bg-white overflow-y-auto px-6 lg:px-10 py-6">
                <div v-if="selectedJob" class="max-w-5xl mx-auto space-y-6 pb-20">
                    <div class="border border-[#e4ede8] rounded-2xl p-8 bg-white shadow-sm">
                        <div class="flex items-center gap-6 mb-6">
                            <div :class="['w-16 h-16 rounded-xl flex items-center justify-center font-bold text-3xl shrink-0', 
                                selectedJob.id % 2 === 0 ? 'bg-ink text-white' : 'bg-primary-300 text-white']">
                                {{ selectedJob.inisial }}
                            </div>
                            <div>
                                <p class="text-lg font-medium text-ink">{{ selectedJob.perusahaan }}</p>
                                <h1 class="text-2xl md:text-3xl font-bold text-ink mt-1">{{ selectedJob.posisi }}</h1>
                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    <span class="inline-flex items-center px-3 py-1 bg-surface-muted border border-[#e4ede8] rounded-lg text-sm font-medium text-ink">
                                        {{ selectedJob.tipe }}
                                    </span>
                                    <span class="text-lg font-bold text-[#00a032]">
                                        {{ selectedJob.gaji }}
                                    </span>
                                    <span class="text-[15px] text-ink-muted flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                        {{ selectedJob.kota }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <button class="bg-ink hover:bg-black text-white font-bold text-[15px] px-8 py-3.5 rounded-lg transition-all flex-1 md:flex-none">
                                Lamar Sekarang
                            </button>
                            <button class="p-3.5 bg-white border-2 border-ink rounded-lg text-ink hover:bg-surface-muted transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="prose prose-sm max-w-none text-ink-soft">
                        <h3 class="text-lg font-bold mb-4">Deskripsi Pekerjaan</h3>
                        <p class="whitespace-pre-line leading-relaxed">{{ selectedJob.deskripsi }}</p>
                        
                        <h3 class="text-lg font-bold mt-8 mb-4">Kualifikasi Utama</h3>
                        <ul class="list-disc pl-5 space-y-2">
                            <li v-for="(syarat, idx) in selectedJob.syarat" :key="idx">{{ syarat }}</li>
                        </ul>

                        <h3 class="text-lg font-bold mt-8 mb-4">Tanggung Jawab</h3>
                        <ul class="list-disc pl-5 space-y-2">
                            <li v-for="(task, idx) in selectedJob.tanggung_jawab" :key="idx">{{ task }}</li>
                        </ul>
                    </div>
                </div>
                <div v-else class="h-full flex items-center justify-center text-center opacity-50">
                    <p>Pilih lowongan untuk melihat detail</p>
                </div>
            </section>
        </main>
    </PublicLayout>
</template>