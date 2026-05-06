<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    links: Array,
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in links" :key="key">
                <div v-if="link.url === null" :key="`null-${key}`"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-muted-foreground border border-border/60 rounded-xl"
                    v-html="link.label" />
                <Link v-else :key="`link-${key}`"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-border/60 rounded-xl hover:bg-muted hover:text-primary transition-colors focus:border-primary focus:text-primary"
                    :class="{ 'bg-primary text-white hover:bg-primary hover:text-white border-primary shadow-lg shadow-primary/20 font-bold': link.active }" :href="link.url"
                    v-html="link.label" />
            </template>
        </div>
    </div>
</template>
