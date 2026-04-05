<script setup>
import { Link } from '@inertiajs/vue3';
import { Briefcase } from 'lucide-vue-next';
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-background font-sans antialiased py-12 px-6 relative overflow-hidden">
        <!-- Subtle background decoration -->
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/2 rounded-full blur-[120px] -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-primary/1 rounded-full blur-[80px] -z-10 pointer-events-none"></div>

        <div class="mb-10 animate-in fade-in slide-in-from-bottom-4 duration-700">
            <Link :href="route('home')" class="flex items-center gap-3 group">
                <div class="w-12 h-12 rounded-2xl bg-primary flex items-center justify-center text-primary-foreground shadow-2xl shadow-primary/20 group-hover:rotate-12 transition-all duration-500">
                    <Briefcase class="w-6 h-6" />
                </div>
                <span class="text-2xl font-display font-black tracking-tight text-primary">
                    Loker<span class="text-foreground">Arsitek</span>
                </span>
            </Link>
        </div>

        <div class="w-full sm:max-w-[480px] bg-card border-2 border-border/50 p-8 md:p-12 rounded-[40px] shadow-2xl shadow-primary/5 relative z-10 animate-in fade-in slide-in-from-bottom-8 duration-1000 delay-150">
            <slot />
        </div>
        
        <div class="mt-12 text-center text-[11px] font-bold uppercase tracking-widest text-muted-foreground/40 animate-in fade-in duration-1000 delay-500">
            &copy; 2026 Loker Arsitek Indonesia. All Rights Reserved.
        </div>
    </div>
</template>

<style scoped>
.font-display {
    font-family: 'Outfit', sans-serif;
}
</style>
