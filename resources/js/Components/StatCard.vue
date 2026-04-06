<script setup>
import { 
  Card,
  CardContent,
  CardHeader,
  CardTitle,
} from "@/Components/UI/ui/card";
import { Circle, Square, Diamond, Triangle } from "lucide-vue-next";

const props = defineProps({
  title: String,
  value: String,
  color: {
    type: String,
    default: 'neutral',
  },
});

const colorStyles = {
  neutral: 'bg-muted/20 text-foreground border-border',
  blue: 'bg-muted/30 text-muted-foreground border-border',
  purple: 'bg-muted/30 text-muted-foreground border-border',
  amber: 'bg-muted/30 text-muted-foreground border-border',
};

const iconMap = {
  default: Circle,
};
</script>

<template>
  <Card class="border-border hover:border-foreground/30 transition-colors rounded-lg">
    <CardHeader class="flex flex-row items-center justify-between pb-3 pt-5 px-5">
      <CardTitle class="text-xs font-bold uppercase tracking-widest text-muted-foreground">
        {{ title }}
      </CardTitle>
      <div :class="[colorStyles[color], 'w-9 h-9 rounded-lg flex items-center justify-center']">
        <slot name="icon">
          <span class="text-sm font-bold">{{ value.charAt(0) }}</span>
        </slot>
      </div>
    </CardHeader>
    <CardContent class="px-5 pb-5">
      <div class="text-2xl font-bold text-foreground tracking-tight">
        {{ value }}
      </div>
      <p class="text-xs text-muted-foreground mt-1.5 font-medium">
        <slot name="trend">
          <span class="text-foreground font-semibold">+12%</span> dari bulan lalu
        </slot>
      </p>
    </CardContent>
  </Card>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
