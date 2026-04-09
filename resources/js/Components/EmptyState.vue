<script setup>
import { Link } from "@inertiajs/vue3";
import { Package, ChevronRight } from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";

defineProps({
  title: {
    type: String,
    default: 'Tidak ada data',
  },
  description: {
    type: String,
    default: 'Sesuatu yang baru akan muncul di sini nanti.',
  },
  actionText: String,
  actionUrl: String,
});
</script>

<template>
  <div class="py-16 px-6 bg-muted/20 border border-border/50 rounded-xl text-center">
    <div class="w-16 h-16 bg-muted rounded-full flex items-center justify-center mx-auto mb-6">
      <Package class="w-8 h-8 text-muted-foreground/60" />
    </div>
    <h3 class="text-lg font-bold text-foreground">{{ title }}</h3>
    <p class="text-sm text-muted-foreground mt-2 max-w-sm mx-auto leading-relaxed">{{ description }}</p>
    
    <div v-if="actionText && actionUrl" class="mt-8">
      <Button asChild class="rounded-lg px-8 h-10 font-semibold">
        <Link :href="actionUrl" class="inline-flex items-center">
          {{ actionText }}
          <ChevronRight class="ml-2 w-4 h-4" />
        </Link>
      </Button>
    </div>
  </div>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
