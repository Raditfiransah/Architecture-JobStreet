<script setup>
import { onMounted, ref } from 'vue';
import { Input } from "@/Components/UI/ui/input";

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value?.$el?.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <Input
        class="border-[#e4ede8] rounded-xl bg-surface-muted/30 px-4 py-3 text-sm transition-all duration-300"
        v-model="model"
        ref="input"
    />
</template>
