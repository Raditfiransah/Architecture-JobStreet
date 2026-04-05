<script setup>
import { ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";

const props = defineProps({
    showSearch: {
        type: Boolean,
        default: false,
    },
    transparent: {
        type: Boolean,
        default: false,
    },
});

const page = usePage();
const user = computed(() => page.props.auth?.user);

const mobileOpen = ref(false);
const userDropdownOpen = ref(false);

const searchQuery = ref(page.props.filters?.q || "");
const locationQuery = ref(page.props.filters?.l || "");

const handleSearch = () => {
    router.get(route("lowongan.index"), { 
        q: searchQuery.value,
        l: locationQuery.value 
    }, { 
        preserveState: true,
        replace: true 
    });
};

const navLinks = [
    { name: "Lowongan Kerja", route: "lowongan.index" },
    { name: "Proyek", route: "proyek.index" },
    { name: "Info Hub", route: "info.index" },
    { name: "Hire Architect", route: "home" }, // Placeholder for now
];
</script>

<template>
    <header 
        :class="[
            'sticky top-0 z-50 transition-all duration-300',
            transparent && !showSearch ? 'bg-white/80 backdrop-blur-md border-b border-[#e4ede8]' : 'bg-white border-b border-[#e4ede8]'
        ]"
    >
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-10">
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center gap-2 group">
                    <span class="w-8 h-8 rounded-lg bg-[#00a032] flex items-center justify-center text-white shadow-lg shadow-[#00a032]/20 group-hover:scale-110 transition">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm0-8h-2V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-6h2v6z"/>
                        </svg>
                    </span>
                    <span class="text-xl font-black tracking-tight text-[#00a032]">LokerArsitek</span>
                </Link>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-8">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.name" 
                        :href="route(link.route)"
                        :class="[
                            'text-[15px] font-medium transition-colors border-b-2 pb-5 mt-5',
                            $page.component.includes(link.name.split(' ')[0]) || route().current(link.route)
                                ? 'text-[#00a032] border-[#00a032] font-bold'
                                : 'text-ink-muted hover:text-ink border-transparent'
                        ]"
                    >
                        {{ link.name }}
                    </Link>
                </nav>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-5">
                <template v-if="!user">
                    <Link :href="route('login')" class="hidden sm:block text-[15px] font-bold text-ink hover:text-[#00a032] transition">Masuk</Link>
                    <Link :href="route('register')" class="bg-[#00a032] hover:bg-[#008a2b] text-white font-bold px-5 py-2.5 rounded-full transition shadow-sm">Daftar</Link>
                </template>
                <template v-else>
                    <div class="relative">
                        <button 
                            @click="userDropdownOpen = !userDropdownOpen"
                            class="w-9 h-9 rounded-full bg-surface-muted text-ink font-bold flex items-center justify-center ring-2 ring-transparent hover:ring-[#00a032] transition"
                        >
                            {{ user.name.charAt(0).toUpperCase() }}
                        </button>
                        
                        <div v-if="userDropdownOpen" class="absolute right-0 mt-3 w-48 bg-white border border-[#e4ede8] rounded-xl shadow-xl py-2 z-50">
                            <Link :href="route(user.dashboard_route || 'home')" class="block px-4 py-2 text-sm text-ink hover:bg-surface-muted transition">Dashboard</Link>
                            <div class="border-t border-[#e4ede8] my-1"></div>
                            <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">Logout</Link>
                        </div>
                    </div>
                </template>

                <!-- Mobile Menu Button -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden text-ink-muted hover:text-ink transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
            </div>
        </div>

        <!-- Search Area (Optional) -->
        <div v-if="showSearch" class="bg-white border-t border-[#f0f2f1] py-4 shadow-sm animate-in fade-in slide-in-from-top-1 duration-300">
            <div class="max-w-[1440px] mx-auto px-4 md:px-8 flex flex-col lg:flex-row items-center gap-3">
                <div class="flex-1 w-full relative flex items-center bg-[#f0f2f1] rounded-full border border-transparent focus-within:border-[#00a032] focus-within:bg-white transition-all overflow-hidden shadow-inner">
                    <div class="pl-5 pr-2">
                        <svg class="w-4 h-4 text-ink-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input 
                        v-model="searchQuery" 
                        type="text" 
                        @keyup.enter="handleSearch"
                        placeholder="Cari lowongan (misal: Senior Arsitek)" 
                        class="flex-1 bg-transparent border-none py-3 text-[15px] text-ink placeholder:text-ink-muted/60 focus:ring-0 outline-none"
                    />
                    <div class="w-px h-6 bg-ink-muted/20"></div>
                    <div class="pl-4 pr-2">
                        <svg class="w-4 h-4 text-ink-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    </div>
                    <input 
                        v-model="locationQuery" 
                        type="text" 
                        @keyup.enter="handleSearch"
                        placeholder="Lokasi (misal: Jakarta)" 
                        class="flex-1 bg-transparent border-none py-3 text-[15px] text-ink placeholder:text-ink-muted/60 focus:ring-0 outline-none"
                    />
                    <button @click="handleSearch" class="bg-[#00a032] text-white px-6 h-full font-bold hover:bg-[#008a2b] transition">
                        Cari
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <transition 
            enter-active-class="transition duration-200 ease-out" 
            enter-from-class="opacity-0 -translate-y-4" 
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div v-if="mobileOpen" class="md:hidden bg-white border-t border-[#e4ede8] px-4 py-6 space-y-4 shadow-xl">
                <Link 
                    v-for="link in navLinks" 
                    :key="link.name" 
                    :href="route(link.route)"
                    class="block text-lg font-medium text-ink hover:text-[#00a032]"
                    @click="mobileOpen = false"
                >
                    {{ link.name }}
                </Link>
                <div class="pt-4 border-t border-[#e4ede8] flex flex-col gap-3">
                    <template v-if="!user">
                        <Link :href="route('login')" class="w-full text-center py-3 font-bold text-ink border border-[#e4ede8] rounded-xl">Masuk</Link>
                        <Link :href="route('register')" class="w-full text-center py-3 font-bold bg-[#00a032] text-white rounded-xl">Daftar</Link>
                    </template>
                </div>
            </div>
        </transition>
    </header>
</template>
