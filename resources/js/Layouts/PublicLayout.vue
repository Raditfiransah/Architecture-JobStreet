<script setup>
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Navbar from "@/Components/Public/Navbar.vue";
import Footer from "@/Components/Public/Footer.vue";
import FlashMessage from "@/Components/Public/FlashMessage.vue";

const page = usePage();
const hasFlashMessage = computed(() => {
  return Boolean(
    page.props.flash?.error ||
    page.props.flash?.success ||
    page.props.flash?.status ||
    page.props.status ||
    Object.keys(page.props.errors || {}).length > 0
  );
});

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
      <div v-if="hasFlashMessage" class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <FlashMessage />
      </div>
      <slot />
    </main>

    <Footer v-if="showFooter" />
  </div>
</template>
