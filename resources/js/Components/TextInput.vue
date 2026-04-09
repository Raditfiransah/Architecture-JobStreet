<script setup>
import { Input } from "@/Components/UI/ui/input";

const model = defineModel({
  type: String,
  required: true,
});
</script>

<template>
  <Input
    class="border-border/50 bg-muted/20 focus:bg-background focus:border-primary/50 focus:ring-primary/20 rounded-xl px-4 py-2.5 text-sm transition-all duration-200"
    v-model="model"
    v-bind="$attrs"
  />
</template>
