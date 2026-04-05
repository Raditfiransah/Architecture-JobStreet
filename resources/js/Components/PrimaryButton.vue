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
        class="rounded-xl font-bold uppercase tracking-widest transition-all duration-300"
    >
        <slot />
    </Button>
</template>
