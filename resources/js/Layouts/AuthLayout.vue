<script setup>
import { Link } from '@inertiajs/vue3';
import { Briefcase } from "lucide-vue-next";
import { buttonVariants } from "@/Components/UI/ui/button";
import { cn } from "@/lib/utils";

defineProps({
  mode: {
    type: String,
    required: true,
  },
});

const quote = "The way to get started is to quit talking and begin doing";
const author = "Walt Disney";
</script>

<template>  
  <div class="relative min-h-screen flex-col items-center justify-center grid lg:max-w-none lg:grid-cols-5 lg:px-0 bg-background text-foreground animate-in fade-in duration-700">
    <!-- Top Right Toggle Link -->
    <Link
      :href="mode === 'login' ? route('register') : route('login')"
      :class="cn(
        buttonVariants({ variant: 'ghost' }),
        'absolute right-4 top-4 md:right-8 md:top-8 font-bold z-50'
      )"
    >
      {{ mode === 'login' ? 'Daftar' : 'Masuk' }}
    </Link>
 
    <!-- Left Panel (lg only) -->
    <div class="relative hidden h-full flex-col bg-zinc-950 p-10 text-white lg:flex border-r border-border/10 lg:col-span-3">
      <div class="absolute inset-0 bg-zinc-950" />
      <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:32px_32px]"></div>
      
      <div class="relative z-20 flex items-center text-xl font-bold">
        <Link :href="route('home')" class="flex items-center gap-3 group">
          <div class="w-10 h-10 rounded-xl bg-primary flex items-center justify-center text-primary-foreground group-hover:rotate-12 transition-all duration-300">
             <Briefcase class="w-5 h-5" />
          </div>
          <span class="text-xl font-display font-bold tracking-tight text-white">
            Loker<span class="text-gray-400">Arsitek</span>
          </span>
        </Link>
      </div>

      <div class="relative z-20 mt-auto">
        <div class="p-8 rounded-xl">
          <blockquote class="max-w-2xl">
            <p class="text-lg leading-relaxed">
              “{{ quote }}”
              <span>– {{ author }}</span>
            </p>
          </blockquote>
        </div>
      </div>
    </div>
 
    <!-- Right Panel (Form) -->
    <div class="lg:p-8 flex items-center justify-center lg:col-span-2">
      <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
        <slot />
        
        <p class="px-8 text-center text-xs text-muted-foreground leading-relaxed">
          Dengan melanjutkan, Anda menyetujui 
          <Link href="#" class="underline underline-offset-4 hover:text-primary transition-colors">Syarat & Ketentuan</Link> 
          dan 
          <Link href="#" class="underline underline-offset-4 hover:text-primary transition-colors">Kebijakan Privasi</Link> kami.
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.font-display {
  font-family: 'Poppins', sans-serif;
}
</style>
