<script setup>
import { Link } from "@inertiajs/vue3";
import { PackageOpen, ArrowRight } from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";

defineProps({
    title: {
        type: String,
        default: 'Tidak ada data',
    },
    description: {
        type: String,
        default: 'Sesuatu yang baru akan muncul di sini nanti.',
    },
    actionText: String,
    actionUrl: String,
});
</script>

<template>
    <div class="text-center py-20 px-6 bg-card border-2 border-dashed border-border/60 rounded-3xl transition-all hover:bg-muted/30 group">
        <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
            <PackageOpen class="w-10 h-10 text-primary opacity-60" />
        </div>
        <h3 class="text-xl font-display font-bold text-foreground">{{ title }}</h3>
        <p class="text-[15px] text-muted-foreground mt-2 max-w-sm mx-auto leading-relaxed">{{ description }}</p>
        
        <div v-if="actionText && actionUrl" class="mt-8">
            <Button asChild class="rounded-2xl px-8 h-12 font-bold shadow-lg shadow-primary/20">
                <Link :href="actionUrl" class="inline-flex items-center">
                    {{ actionText }}
                    <ArrowRight class="ml-2 w-4 h-4" />
                </Link>
            </Button>
        </div>
    </div>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
</style>
