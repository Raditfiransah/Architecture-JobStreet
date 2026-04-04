<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input
        class="border-[#e4ede8] focus:border-primary-300 focus:ring focus:ring-primary-100 rounded-xl shadow-sm bg-surface-muted/30 px-4 py-3 text-sm text-ink placeholder:text-ink-muted/50 transition duration-150"
        v-model="model"
        ref="input"
    />
</template>
