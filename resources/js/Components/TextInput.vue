<script setup>
import { onMounted, ref } from 'vue';
import { Input } from "@/Components/UI/ui/input";

const model = defineModel({
    type: String,
    required: true,
});
</script>

<template>
    <Input
        class="border-[#e4ede8] rounded-xl bg-surface-muted/30 px-4 py-3 text-sm transition-all duration-300"
        v-model="model"
        v-bind="$attrs"
    />
</template>
