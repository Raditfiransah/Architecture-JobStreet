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
    <div class="flex min-h-screen bg-gray-50 font-sans antialiased">
        <!-- Sidebar -->
        <div
            id="sidebar"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
            class="fixed inset-y-0 left-0 z-50 w-64 transition-transform duration-300 ease-in-out lg:static lg:inset-auto lg:translate-x-0 bg-gray-900 shadow-xl lg:shadow-none"
        >
            <Sidebar :role="user.role" />
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
                class="fixed inset-0 bg-black/50 z-40 lg:hidden"
            ></div>
        </transition>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
            <Topbar :user="user" @toggle-sidebar="toggleSidebar" />

            <main class="flex-1 overflow-y-auto p-4 md:p-8">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
