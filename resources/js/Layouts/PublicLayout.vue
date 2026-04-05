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
    <div class="min-h-screen bg-background flex flex-col font-sans antialiased text-foreground selection:bg-primary/20 selection:text-primary">
        <!-- Unified Navbar -->
        <Navbar 
            :show-search="showSearch" 
            :transparent="transparentNavbar" 
        />

        <!-- Page Content -->
        <main class="flex-1 flex flex-col relative overflow-hidden">
            <!-- Subtle background decoration for public pages -->
            <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-primary/3 rounded-full blur-[150px] -z-10 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-primary/2 rounded-full blur-[100px] -z-10 pointer-events-none"></div>

            <slot />
        </main>

        <!-- Unified Footer -->
        <Footer v-if="showFooter" />
    </div>
</template>
