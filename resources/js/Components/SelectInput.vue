<script setup>
import { ref } from 'vue';

const input = ref(null);

onMounted(() => {
  if (input.value?.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({ focus: () => input.value?.focus() });

const model = defineModel({
  required: true,
});

defineProps({
  options: {
    type: Array,
    default: () => [],
  },
  placeholder: {
    type: String,
    default: 'Pilih opsi...',
  },
});
</script>

<template>
  <select
    ref="input"
    class="border-border/50 bg-muted/20 text-foreground focus:border-primary/50 focus:ring-primary/20 rounded-xl px-4 py-2.5 text-sm appearance-none cursor-pointer transition-all duration-200"
    v-model="model"
  >
    <option value="" disabled selected>{{ placeholder }}</option>
    <option v-for="option in options" :key="option.value" :value="option.value">
      {{ option.label }}
    </option>
  </select>
</template>
