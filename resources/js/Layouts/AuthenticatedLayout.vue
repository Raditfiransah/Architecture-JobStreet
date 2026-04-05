<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import Topbar from '@/Components/Topbar.vue';

const sidebarOpen = ref(false);
const page = usePage();
const user = page.props.auth.user;

const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value;
};

const closeSidebar = () => {
    sidebarOpen.value = false;
};

// Handle escape key to close sidebar
const handleEsc = (e) => {
    if (e.key === 'Escape') closeSidebar();
};

onMounted(() => window.addEventListener('keydown', handleEsc));
onUnmounted(() => window.removeEventListener('keydown', handleEsc));
</script>

<template>
    <div class="flex min-h-screen bg-background font-sans antialiased text-foreground selection:bg-primary/20 selection:text-primary">
        <!-- Sidebar -->
        <div
            id="sidebar"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-72 transition-all duration-500 ease-in-out lg:static lg:inset-auto lg:translate-x-0 bg-card border-r border-border/50 shadow-2xl lg:shadow-none"
        >
            <Sidebar v-if="user" :role="user.role" class="w-full" />
        </div>

        <!-- Sidebar Overlay (Mobile) -->
        <transition
            enter-active-class="transition-opacity ease-linear duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity ease-linear duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="sidebarOpen"
                @click="closeSidebar"
                class="fixed inset-0 bg-background/80 backdrop-blur-sm z-40 lg:hidden"
            ></div>
        </transition>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden relative">
            <!-- Subtle background decoration -->
            <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/2 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
            
            <Topbar v-if="user" :user="user" @toggle-sidebar="toggleSidebar" />

            <main class="flex-1 overflow-y-auto scroll-smooth">
                <div class="max-w-7xl mx-auto p-6 md:p-10 lg:p-12">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
