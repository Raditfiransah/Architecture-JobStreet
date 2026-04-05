<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 translate-y-[-4px]"
        enter-to-class="opacity-100 translate-y-0"
    >
        <div v-show="message">
            <p class="text-[13px] font-medium text-destructive mt-1.5 ml-1">
                {{ message }}
            </p>
        </div>
    </transition>
</template>
