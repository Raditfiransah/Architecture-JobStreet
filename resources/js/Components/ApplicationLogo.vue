<script setup>
import { Briefcase } from "lucide-vue-next";
</script>

<template>
    <div class="flex items-center gap-2.5 group">
        <div class="w-9 h-9 rounded-xl bg-primary flex items-center justify-center text-primary-foreground shadow-lg shadow-primary/20 group-hover:rotate-12 transition-all duration-500">
            <Briefcase class="w-5 h-5" />
        </div>
        <span class="text-2xl font-display font-extrabold tracking-tight text-primary">
            Loker<span class="text-foreground">Arsitek</span>
        </span>
    </div>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
</style>
