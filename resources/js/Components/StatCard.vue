<script setup>
const props = defineProps({
    title: String,
    value: String,
    color: {
        type: String,
        default: 'blue',
    },
});

const colorClasses = {
    blue: 'bg-blue-50 text-blue-600',
    green: 'bg-green-50 text-green-600',
    purple: 'bg-purple-50 text-purple-600',
    amber: 'bg-amber-50 text-amber-600',
};
</script>

<template>
    <div class="bg-white p-6 rounded-2xl border border-[#e4ede8] shadow-sm hover:shadow-md transition duration-200">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-[13px] font-bold text-ink-muted uppercase tracking-wider">{{ title }}</h3>
            <div :class="colorClasses[color]" class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg">
                <slot name="icon">
                    <span class="text-base text-current font-bold">{{ value.charAt(0) }}</span>
                </slot>
            </div>
        </div>
        <div class="text-3xl font-extrabold text-ink">{{ value }}</div>
    </div>
</template>
