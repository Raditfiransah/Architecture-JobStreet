<script setup>
import Navbar from "@/Components/Public/Navbar.vue";
import Footer from "@/Components/Public/Footer.vue";

defineProps({
    showFooter: {
        type: Boolean,
        default: true,
    },
    showSearch: {
        type: Boolean,
        default: false,
    },
    transparentNavbar: {
        type: Boolean,
        default: false,
    }
});
</script>

<template>
    <div class="min-h-screen bg-white flex flex-col font-sans antialiased text-ink">
        <!-- Unified Navbar -->
        <Navbar 
            :show-search="showSearch" 
            :transparent="transparentNavbar" 
        />

        <!-- Page Content -->
        <main class="flex-1 flex flex-col">
            <slot />
        </main>

        <!-- Unified Footer -->
        <Footer v-if="showFooter" />
    </div>
</template>
