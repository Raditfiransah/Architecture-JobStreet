<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";

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
    <Head>
        <title>Loker Arsitek — Platform Arsitek #1 di Indonesia</title>
        <meta
            name="description"
            content="Temukan lowongan kerja, proyek freelance, dan portofolio digital untuk arsitek Indonesia. Bergabung dengan 1.200+ arsitek terverifikasi."
        />
    </Head>

    <div class="bg-white text-ink font-sans antialiased min-h-screen flex flex-col">
        <!-- Navbar -->
        <nav class="sticky top-0 z-50 w-full bg-white border-b border-[#e4ede8]">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded border-2 border-primary-300 flex items-center justify-center">
                        <span class="w-2 h-2 bg-primary-300 rounded-sm"></span>
                    </span>
                    <span class="text-xl font-bold tracking-tight">
                        <span class="text-ink">Loker</span><span class="text-primary-400">Arsitek</span>
                    </span>
                </Link>

                <!-- Center nav links (desktop) -->
                <div class="hidden md:flex items-center gap-8">
                    <Link :href="route('lowongan.index')" class="text-sm text-ink-muted hover:text-ink transition-colors duration-150">Lowongan Kerja</Link>
                    <Link :href="route('proyek.index')" class="text-sm text-ink-muted hover:text-ink transition-colors duration-150">Proyek</Link>
                    <Link :href="route('info.index')" class="text-sm text-ink-muted hover:text-ink transition-colors duration-150">Info Hub</Link>
                    <Link :href="route('arsitek.direktori')" class="text-sm text-ink-muted hover:text-ink transition-colors duration-150">Hire Architect</Link>
                </div>

                <!-- Right actions -->
                <div class="hidden md:flex items-center gap-3">
                    <template v-if="!user">
                        <Link :href="route('login')" class="text-sm px-4 py-2 rounded-lg border border-[#e4ede8] bg-white text-ink hover:bg-surface-muted transition-colors duration-150">Masuk</Link>
                        <Link :href="route('register')" class="text-sm px-4 py-2 rounded-lg bg-primary-300 text-white font-medium hover:bg-primary-400 transition-colors duration-150">Daftar</Link>
                    </template>
                    <template v-else>
                        <div class="relative">
                            <button @click="userDropdownOpen = !userDropdownOpen" class="w-9 h-9 rounded-full bg-primary-100 text-primary-700 font-semibold text-sm flex items-center justify-center hover:ring-2 hover:ring-primary-200 transition">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </button>
                            <div v-if="userDropdownOpen" v-click-away="() => (userDropdownOpen = false)" class="absolute right-0 mt-2 w-44 bg-white border border-[#e4ede8] rounded-xl shadow-lg py-1 z-50">
                                <Link :href="route(user.dashboard_route || 'home')" class="block px-4 py-2 text-sm text-ink-soft hover:bg-surface-muted transition">Dashboard</Link>
                                <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-ink-soft hover:bg-surface-muted transition">Logout</Link>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Hamburger (mobile) -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 -mr-2 text-ink-muted hover:text-ink transition" aria-label="Toggle menu">
                    <svg v-if="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Mobile menu -->
            <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                <div v-if="mobileOpen" class="md:hidden border-t border-[#e4ede8] bg-white px-6 py-4 space-y-3">
                    <Link :href="route('lowongan.index')" class="block text-sm text-ink-muted hover:text-ink py-1">Lowongan Kerja</Link>
                    <Link :href="route('proyek.index')" class="block text-sm text-ink-muted hover:text-ink py-1">Proyek</Link>
                    <Link :href="route('arsitek.direktori')" class="block text-sm text-ink-muted hover:text-ink py-1">Arsitek</Link>
                    <Link :href="route('info.index')" class="block text-sm text-ink-muted hover:text-ink py-1">Info Hub</Link>
                    <div class="pt-3 border-t border-[#e4ede8] flex gap-3">
                        <template v-if="!user">
                            <Link :href="route('login')" class="flex-1 text-center text-sm px-4 py-2 rounded-lg border border-[#e4ede8] text-ink hover:bg-surface-muted transition">Masuk</Link>
                            <Link :href="route('register')" class="flex-1 text-center text-sm px-4 py-2 rounded-lg bg-primary-300 text-white font-medium hover:bg-primary-400 transition">Daftar</Link>
                        </template>
                        <template v-else>
                            <Link :href="route(user.dashboard_route || 'home')" class="flex-1 text-center text-sm px-4 py-2 rounded-lg bg-primary-300 text-white font-medium hover:bg-primary-400 transition">Dashboard</Link>
                        </template>
                    </div>
                </div>
            </transition>
        </nav>

        <!-- Minimal Search Center -->
        <main class="flex-1 flex flex-col items-center justify-center bg-gradient-to-b from-primary-50/50 to-white py-20">
            <div class="w-full max-w-4xl px-6 text-center">
                <h1 class="text-[32px] md:text-[48px] font-bold text-ink leading-[1.15] tracking-tight mb-8">
                    Temukan Peluang Terbaik untuk <span class="text-primary-400">Arsitek Indonesia</span>
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
                            class="mt-2 md:mt-0 bg-primary-300 hover:bg-primary-400 text-white font-bold text-[15px] px-10 py-4 rounded-[18px] transition-all duration-150 shrink-0 shadow-lg shadow-primary-300/20 active:scale-[0.98]"
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
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-[#e4ede8] py-12">
            <div class="max-w-6xl mx-auto px-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-10">
                    <div>
                        <Link :href="route('home')" class="flex items-center gap-2 mb-3">
                            <span class="w-5 h-5 rounded border-2 border-primary-300 flex items-center justify-center">
                                <span class="w-2 h-2 bg-primary-300 rounded-sm"></span>
                            </span>
                            <span class="text-lg font-bold text-ink">Loker<span class="text-primary-400">Arsitek</span></span>
                        </Link>
                        <p class="text-sm text-ink-muted">Platform arsitek terpadu untuk Indonesia.</p>
                        <p class="text-[11px] text-ink-muted/60 mt-4">&copy; 2024 Loker Arsitek. All rights reserved.</p>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-bold text-ink uppercase tracking-wider mb-4">Platform</h4>
                        <ul class="space-y-3 text-[13px] text-ink-muted">
                            <li><Link :href="route('lowongan.index')" class="hover:text-primary-500 transition">Lowongan Kerja</Link></li>
                            <li><Link :href="route('proyek.index')" class="hover:text-primary-500 transition">Marketplace Proyek</Link></li>
                            <li><Link :href="route('arsitek.direktori')" class="hover:text-primary-500 transition">Direktori Arsitek</Link></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
