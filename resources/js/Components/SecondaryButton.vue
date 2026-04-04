<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center px-4 py-2 bg-white border border-[#e4ede8] rounded-xl font-semibold text-xs text-ink-soft uppercase tracking-widest shadow-sm hover:bg-surface-muted focus:outline-none focus:ring-2 focus:ring-primary-100 disabled:opacity-25 transition ease-in-out duration-150"
    >
        <slot />
    </button>
</template>
