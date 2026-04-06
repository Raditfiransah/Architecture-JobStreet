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
  },
  showBreadcrumb: {
    type: Boolean,
    default: false,
  }
});
</script>

<template>
  <div class="min-h-screen bg-background flex flex-col font-sans antialiased text-foreground selection:bg-primary/20 selection:text-primary">
    <Navbar 
      :show-search="showSearch" 
      :transparent="transparentNavbar" 
    />

    <main class="flex-1 flex flex-col relative">
      <slot />
    </main>

    <Footer v-if="showFooter" />
  </div>
</template>
