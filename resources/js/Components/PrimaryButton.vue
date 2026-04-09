<script setup>
import { Button } from "@/Components/UI/ui/button";

defineProps({
  type: {
    type: String,
    default: 'submit',
  },
});
</script>

<template>
  <Button
    :type="type"
    class="rounded-lg font-bold uppercase tracking-wider px-6 py-2.5 transition-all duration-200"
  >
    <slot />
  </Button>
</template>
