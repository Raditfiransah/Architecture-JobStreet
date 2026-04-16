<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { AlertCircle, CheckCircle2 } from 'lucide-vue-next';

const page = usePage();

const error = computed(() => {
    return page.props.flash?.error || Object.values(page.props.errors || {})[0];
});

const success = computed(() => {
    return page.props.flash?.success || page.props.status;
});
</script>

<template>
    <div v-if="error || success" class="mb-6 animate-in slide-in-from-top-2 fade-in duration-300">
        <!-- Error Message -->
        <div v-if="error" class="flex items-start gap-3 p-4 text-red-900 bg-red-50 border border-red-200 rounded-lg shadow-sm">
            <AlertCircle class="w-5 h-5 text-red-600 mt-0.5 shrink-0" />
            <div class="flex-1">
                <p class="text-sm font-medium">{{ error }}</p>
            </div>
        </div>

        <!-- Success Message -->
        <div v-if="success" class="flex items-start gap-3 p-4 text-emerald-900 bg-emerald-50 border border-emerald-200 rounded-lg shadow-sm">
            <CheckCircle2 class="w-5 h-5 text-emerald-600 mt-0.5 shrink-0" />
            <div class="flex-1">
                <p class="text-sm font-medium">{{ success }}</p>
            </div>
        </div>
    </div>
</template>
