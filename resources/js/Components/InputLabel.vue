<script setup>
import { Label } from "@/Components/UI/ui/label";

defineProps({
  value: String,
});
</script>

<template>
  <Label class="block text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>
  </Label>
</template>
