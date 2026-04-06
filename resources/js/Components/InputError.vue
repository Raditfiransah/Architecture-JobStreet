<script setup>
defineProps({
  message: String,
});
</script>

<template>
  <p v-if="message" class="text-xs font-medium text-destructive mt-1.5">
    {{ message }}
  </p>
</template>
