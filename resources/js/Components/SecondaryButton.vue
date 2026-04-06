<script setup>
import { Button } from "@/Components/UI/ui/button";

defineProps({
  type: {
    type: String,
    default: 'button',
  },
});
</script>

<template>
  <Button
    :type="type"
    variant="outline"
    class="rounded-lg font-semibold text-xs uppercase tracking-wider text-muted-foreground border-border/50 hover:border-primary/30 hover:text-primary transition-all duration-200"
  >
    <slot />
  </Button>
</template>
