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
        class="rounded-xl font-semibold text-xs text-ink-soft uppercase tracking-widest transition-all duration-300"
    >
        <slot />
    </Button>
</template>
