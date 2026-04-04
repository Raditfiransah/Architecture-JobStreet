<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });

defineProps({
    options: {
        type: Array,
        default: () => [],
    },
    placeholder: {
        type: String,
        default: 'Pilih opsi...',
    }
});
</script>

<template>
    <select
        class="border-[#e4ede8] focus:border-primary-300 focus:ring focus:ring-primary-100 rounded-xl shadow-sm bg-surface-muted/30 px-4 py-3 text-sm text-ink transition duration-150 appearance-none cursor-pointer"
        v-model="model"
        ref="input"
    >
        <option value="" disabled selected>{{ placeholder }}</option>
        <option v-for="option in options" :key="option.value" :value="option.value">
            {{ option.label }}
        </option>
    </select>
</template>
