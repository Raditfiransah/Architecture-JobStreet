<script setup>
import { 
    Card,
    CardContent,
    CardHeader,
    CardTitle,
} from "@/Components/UI/ui/card";

const props = defineProps({
    title: String,
    value: String,
    color: {
        type: String,
        default: 'emerald',
    },
});

const colorStyles = {
    emerald: 'bg-primary/10 text-primary border-primary/20',
    blue: 'bg-blue-500/10 text-blue-600 border-blue-500/20',
    purple: 'bg-purple-500/10 text-purple-600 border-purple-500/20',
    amber: 'bg-amber-500/10 text-amber-600 border-amber-500/20',
};
</script>

<template>
    <Card class="relative overflow-hidden group border-border/50 hover:border-primary/30 transition-all duration-500 rounded-2xl shadow-sm hover:shadow-xl hover:-translate-y-1">
        <!-- Abstract Glow -->
        <div class="absolute top-0 right-0 -translate-y-1/2 translate-x-1/4 w-32 h-32 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-all duration-500"></div>
        
        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
            <CardTitle class="text-[13px] font-display font-bold uppercase tracking-widest text-muted-foreground/80">
                {{ title }}
            </CardTitle>
            <div :class="[colorStyles[color], 'w-10 h-10 rounded-xl border flex items-center justify-center font-bold transition-all duration-500 group-hover:scale-110 group-hover:rotate-6']">
                <slot name="icon">
                    <span class="text-base font-extrabold">{{ value.charAt(0) }}</span>
                </slot>
            </div>
        </CardHeader>
        <CardContent>
            <div class="text-3xl font-display font-extrabold text-foreground tracking-tight">
                {{ value }}
            </div>
            <p class="text-xs text-muted-foreground mt-2 font-medium">
                <slot name="trend">
                    <span class="text-primary font-bold">+12%</span> dari bulan lalu
                </slot>
            </p>
        </CardContent>
    </Card>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
</style>
