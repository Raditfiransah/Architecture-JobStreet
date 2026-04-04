<script setup>
defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <label class="block font-semibold text-sm text-ink-soft mb-1.5">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
