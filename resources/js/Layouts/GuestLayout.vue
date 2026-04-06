<script setup>
import { Link } from '@inertiajs/vue3';
import { Briefcase } from "lucide-vue-next";

defineProps({
  hideLogo: {
    type: Boolean,
    default: false,
  },
});
</script>

<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-background font-sans antialiased py-12 px-6">
    <div v-if="!hideLogo" class="mb-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
      <Link :href="route('home')" class="flex items-center gap-3 group">
        <div class="w-12 h-12 rounded-xl bg-primary flex items-center justify-center text-primary-foreground group-hover:rotate-12 transition-all duration-300">
           <Briefcase class="w-6 h-6" />
        </div>
        <span class="text-2xl font-display font-bold tracking-tight text-primary">
          Loker<span class="text-foreground">Arsitek</span>
        </span>
      </Link>
    </div>

    <div class="w-full sm:max-w-[480px] bg-card border border-border/50 p-8 rounded-2xl relative z-10 animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-150">
      <slot />
    </div>
    
    <div class="mt-12 text-center text-xs font-bold uppercase tracking-widest text-muted-foreground/50">
      &copy; 2026 Loker Arsitek Indonesia. All Rights Reserved.
    </div>
  </div>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
