<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";

const page = usePage();
const user = computed(() => page.props.auth.user);

const mobileOpen = ref(false);
const userDropdownOpen = ref(false);

const searchQuery = ref("");

const handleSearch = () => {
    if (!searchQuery.value.trim()) return;
    router.get(route("lowongan.index"), { q: searchQuery.value });
};

const popularTags = [
    "Arsitek Residensial",
    "BIM Specialist",
    "Interior Designer",
    "Urban Planner",
    "Fresh Graduate",
];
</script>

<template>
    <PublicLayout :transparent-navbar="true">
        <Head>
            <title>Loker Arsitek — Platform Arsitek #1 di Indonesia</title>
            <meta
                name="description"
                content="Temukan lowongan kerja, proyek freelance, dan portofolio digital untuk arsitek Indonesia. Bergabung dengan 1.200+ arsitek terverifikasi."
            />
        </Head>

        <!-- Minimal Search Center -->
        <div class="flex-1 flex flex-col items-center justify-center bg-gradient-to-b from-primary-50/50 to-white py-20 px-6">
            <div class="w-full max-w-4xl text-center">
                <h1 class="text-[32px] md:text-[48px] font-bold text-ink leading-[1.15] tracking-tight mb-8">
                    Temukan Peluang Terbaik untuk <span class="text-[#00a032]">Arsitek Indonesia</span>
                </h1>

                <!-- Search Bar Focused -->
                <div class="bg-white border border-[#e4ede8] rounded-[24px] p-2.5 shadow-xl shadow-primary-100/50 hover:border-primary-200 transition-all duration-300">
                    <form @submit.prevent="handleSearch" class="flex flex-col md:flex-row items-stretch md:items-center">
                        <div class="flex-1 flex items-center px-4">
                            <svg class="w-5 h-5 text-ink-muted/40 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input
                                v-model="searchQuery"
                                type="text"
                                placeholder="Cari posisi, keahlian, atau perusahaan..."
                                required
                                class="w-full border-0 bg-transparent px-4 py-4 text-[16px] text-ink placeholder:text-ink-muted/50 focus:ring-0 focus:outline-none"
                            />
                        </div>
                        <div class="hidden md:block w-px h-8 bg-[#e4ede8] self-center shrink-0 mx-2"></div>
                        <button
                            type="submit"
                            class="mt-2 md:mt-0 bg-[#00a032] hover:bg-[#008a2b] text-white font-bold text-[15px] px-10 py-4 rounded-[18px] transition-all duration-150 shrink-0 shadow-lg shadow-primary-300/20 active:scale-[0.98]"
                        >
                            Cari Lowongan
                        </button>
                    </form>
                </div>

                <!-- Popular Tags -->
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <span class="text-[13px] text-ink-muted">Pencarian populer:</span>
                    <Link
                        v-for="tag in popularTags"
                        :key="tag"
                        :href="route('lowongan.index', { q: tag })"
                        class="text-[13px] px-4 py-1.5 rounded-full bg-white border border-[#e4ede8] text-ink-muted hover:bg-primary-50 hover:text-primary-700 hover:border-primary-200 transition-all duration-150"
                    >
                        {{ tag }}
                    </Link>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
