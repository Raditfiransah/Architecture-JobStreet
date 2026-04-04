<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['toggle-sidebar']);

const userName = computed(() => props.user?.name || 'User');
const userInitials = computed(() => {
    return props.user?.name ? props.user.name.charAt(0).toUpperCase() : 'U';
});
const userRole = computed(() => {
    if (!props.user?.role) return 'Unassigned';
    return props.user.role.charAt(0).toUpperCase() + props.user.role.slice(1);
});

const form = useForm({});

const logout = () => {
    form.post(route('logout'));
};
</script>

<template>
    <header class="bg-white border-b border-gray-100 h-16 px-6 flex items-center justify-between sticky top-0 z-40">
        <div class="flex items-center">
            <button
                type="button"
                class="lg:hidden mr-4 text-gray-500 hover:text-gray-700 focus:outline-none"
                @click="emit('toggle-sidebar')"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        <div class="flex items-center space-x-6">
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-gray-900 leading-tight">{{ userName }}</p>
                    <p class="text-[11px] font-medium text-gray-500 uppercase tracking-wider">{{ userRole }}</p>
                </div>

                <div v-if="user.avatar_url" class="w-9 h-9 rounded-full overflow-hidden border border-gray-200">
                    <img :src="user.avatar_url" alt="Avatar" class="w-full h-full object-cover">
                </div>
                <div v-else class="w-9 h-9 rounded-full bg-primary-300 flex items-center justify-center border border-primary-400/20 shadow-sm shadow-primary-100">
                    <span class="text-white text-sm font-bold">{{ userInitials }}</span>
                </div>
            </div>

            <div class="h-6 w-px bg-gray-100"></div>

            <button
                type="button"
                @click="logout"
                class="text-sm font-medium text-gray-500 hover:text-red-600 transition flex items-center gap-2"
            >
                <span>Logout</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
            </button>
        </div>
    </header>
</template>
