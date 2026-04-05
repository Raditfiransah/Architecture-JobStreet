<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import { 
    Search, 
    User, 
    Menu, 
    X, 
    ChevronDown, 
    LogOut, 
    LayoutDashboard,
    MapPin,
    Briefcase
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { 
    Avatar, 
    AvatarImage, 
    AvatarFallback 
} from "@/Components/UI/ui/avatar";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from "@/Components/UI/ui/dropdown-menu";
import ProfileCard from "./ProfileCard.vue";

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
const isScrolled = ref(false);

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

const handleScroll = () => {
    isScrolled.value = window.scrollY > 20;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

const navLinks = computed(() => {
    const baseLinks = [
        { name: "Lowongan Kerja", route: "lowongan.index" },
    ];

    if (user.value) {
        return [
            ...baseLinks,
            { name: "Proyek", route: "proyek.index" },
            { name: "Info Hub", route: "info.index" },
            { name: "Hire Architect", route: "home" },
        ];
    }

    return baseLinks;
});
</script>

<template>
    <header 
        :class="[
            'sticky top-0 z-50 transition-all duration-500',
            transparent && !isScrolled && !showSearch
                ? 'bg-transparent border-transparent'
                : 'bg-background/80 backdrop-blur-xl border-b shadow-sm'
        ]"
    >
        <div class="max-w-[1440px] mx-auto px-4 md:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center gap-12">
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center gap-2.5 group">
                    <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-primary-foreground shadow-lg shadow-primary/20 group-hover:rotate-12 transition-all duration-500">
                        <Briefcase class="w-5 h-5" />
                    </div>
                    <span class="text-2xl font-display font-extrabold tracking-tight text-primary">
                        Loker<span class="text-foreground">Arsitek</span>
                    </span>
                </Link>

                <!-- Desktop Nav -->
                <nav class="hidden lg:flex items-center gap-1">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.name" 
                        :href="route(link.route)"
                        :class="[
                            'px-4 py-2 rounded-full text-[14px] font-semibold transition-all duration-300',
                            route().current(link.route)
                                ? 'bg-primary/10 text-primary'
                                : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                        ]"
                    >
                        {{ link.name }}
                    </Link>
                </nav>
            </div>

            <!-- Right Actions -->
            <div class="flex items-center gap-4">
                <template v-if="!user">
                    <Button variant="ghost" asChild class="hidden sm:inline-flex font-bold rounded-full">
                        <Link :href="route('login')">Masuk</Link>
                    </Button>
                    <Button asChild class="font-bold rounded-full px-6 shadow-lg shadow-primary/20 transition-all hover:scale-105 active:scale-95">
                        <Link :href="route('register')">Daftar</Link>
                    </Button>
                </template>
                
                <template v-else>
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <Button variant="ghost" class="relative h-10 w-10 rounded-full p-0 border border-border/50 hover:border-primary/30 transition-all overflow-hidden group">
                                <Avatar class="h-10 w-10">
                                    <AvatarImage :src="user.profile_photo_url" :alt="user.name" />
                                    <AvatarFallback class="bg-primary/10 text-primary font-bold">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </AvatarFallback>
                                </Avatar>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent 
                            class="w-[320px] mt-2 rounded-[24px] shadow-2xl border-none p-0 overflow-hidden" 
                            align="end"
                        >
                            <template v-if="user.role === 'arsitek'">
                                <ProfileCard :user="user" />
                                <div class="px-2 pb-2 bg-background">
                                    <DropdownMenuSeparator class="mx-2 mb-2" />
                                    <Link :href="route('logout')" method="post" as="button" class="flex items-center w-full px-4 py-3 rounded-xl text-destructive hover:bg-destructive/10 transition-all font-semibold">
                                        <LogOut class="mr-2 h-4 w-4" />
                                        <span>Keluar</span>
                                    </Link>
                                </div>
                            </template>
                            <template v-else>
                                <!-- Standard menu for other roles if needed -->
                                <div class="p-4">
                                    <p class="font-bold">{{ user.name }}</p>
                                    <p class="text-xs text-muted-foreground">{{ user.email }}</p>
                                    <DropdownMenuSeparator class="my-3" />
                                    <Link :href="route(user.dashboard_route || 'home')" class="flex items-center py-2 hover:text-primary">
                                        <LayoutDashboard class="mr-2 h-4 w-4" />
                                        <span>Dashboard</span>
                                    </Link>
                                    <Link :href="route('logout')" method="post" as="button" class="flex items-center w-full py-2 text-destructive mt-2">
                                        <LogOut class="mr-2 h-4 w-4" />
                                        <span>Keluar</span>
                                    </Link>
                                </div>
                            </template>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </template>

                <!-- Mobile Menu Button -->
                <Button 
                    variant="ghost" 
                    size="icon" 
                    class="lg:hidden rounded-full" 
                    @click="mobileOpen = !mobileOpen"
                >
                    <Menu v-if="!mobileOpen" class="w-6 h-6" />
                    <X v-else class="w-6 h-6" />
                </Button>
            </div>
        </div>

        <!-- Search Area (Optional) -->
        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div v-if="showSearch" class="bg-background border-t border-border/50 py-4 shadow-sm backdrop-blur-xl">
                <div class="max-w-[1440px] mx-auto px-4 md:px-8">
                    <div class="relative flex flex-col md:flex-row items-stretch md:items-center bg-muted/30 rounded-2xl md:rounded-full border border-border/50 focus-within:border-primary/50 focus-within:bg-background transition-all duration-500 overflow-hidden shadow-inner p-1">
                        <div class="flex-1 flex items-center px-4 py-2">
                            <Search class="w-4 h-4 text-muted-foreground mr-3" />
                            <Input 
                                v-model="searchQuery" 
                                type="text" 
                                @keyup.enter="handleSearch"
                                placeholder="Cari lowongan (misal: Senior Arsitek)" 
                                class="border-none shadow-none focus-visible:ring-0 px-0 bg-transparent h-auto py-2 text-[15px]" 
                            />
                        </div>
                        <div class="hidden md:block w-px h-8 bg-border/50 self-center"></div>
                        <div class="flex-1 flex items-center px-4 py-2 border-t md:border-t-0 border-border/50">
                            <MapPin class="w-4 h-4 text-muted-foreground mr-3" />
                            <Input 
                                v-model="locationQuery" 
                                type="text" 
                                @keyup.enter="handleSearch"
                                placeholder="Lokasi (misal: Jakarta)" 
                                class="border-none shadow-none focus-visible:ring-0 px-0 bg-transparent h-auto py-2 text-[15px]" 
                            />
                        </div>
                        <Button 
                            @click="handleSearch" 
                            size="lg" 
                            class="rounded-full px-10 h-11 font-bold shadow-lg shadow-primary/20"
                        >
                            Cari
                        </Button>
                    </div>
                </div>
            </div>
        </transition>

        <!-- Mobile Menu Overlay -->
        <transition 
            enter-active-class="transition duration-300 ease-out" 
            enter-from-class="opacity-0 -translate-y-10" 
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-10"
        >
            <div v-if="mobileOpen" class="lg:hidden absolute top-full left-0 w-full bg-background border-t border-border shadow-2xl p-6 py-10 space-y-6 z-[60]">
                <div class="grid gap-2">
                    <Link 
                        v-for="link in navLinks" 
                        :key="link.name" 
                        :href="route(link.route)"
                        class="p-4 rounded-2xl text-lg font-bold text-foreground hover:bg-primary/5 hover:text-primary transition-all active:scale-95"
                        @click="mobileOpen = false"
                    >
                        {{ link.name }}
                    </Link>
                </div>
                
                <div v-if="!user" class="pt-6 border-t flex flex-col gap-3">
                    <Button variant="outline" asChild size="lg" class="rounded-2xl h-14 font-bold shadow-sm">
                        <Link :href="route('login')">Masuk</Link>
                    </Button>
                    <Button asChild size="lg" class="rounded-2xl h-14 font-bold shadow-lg shadow-primary/20">
                        <Link :href="route('register')">Daftar Gratis</Link>
                    </Button>
                </div>
            </div>
        </transition>
    </header>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
</style>
