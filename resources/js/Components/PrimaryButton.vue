<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center justify-center px-6 py-3 bg-primary-300 border border-transparent rounded-xl font-bold text-sm text-white uppercase tracking-widest hover:bg-primary-400 active:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-100 transition ease-in-out duration-150 shadow-md shadow-primary-100/50"
    >
        <slot />
    </button>
</template>
