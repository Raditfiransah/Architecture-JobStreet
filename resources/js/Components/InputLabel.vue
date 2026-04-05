<script setup>
import { Label } from "@/Components/UI/ui/label";

defineProps({
    value: {
        type: String,
    },
});
</script>

<template>
    <Label class="block font-semibold text-sm text-ink-soft mb-1.5 cursor-pointer">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </Label>
</template>
