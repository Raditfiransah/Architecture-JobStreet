<script setup>
import { Link, usePage, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
  User, 
  Briefcase, 
  Settings, 
  Bell, 
  LogOut, 
  MapPin, 
  GraduationCap, 
  FileText, 
  Target, 
  Activity,
  Edit2,
  ChevronRight,
  HelpCircle,
  Menu
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import { Card, CardContent } from "@/Components/UI/ui/card";
import { Separator } from "@/Components/UI/ui/separator";
import Topbar from "@/Components/Topbar.vue";

const page = usePage();
const user = computed(() => page.props.auth.user);

const userInitials = computed(() => {
  if (!user.value?.name) return 'U';
  return user.value.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
});

const menuItems = [
  { group: 'Job seeking', items: [
    { label: 'Resume & experience', icon: FileText, href: '#' },
    { label: 'Job preferences', icon: Target, href: '#' },
    { label: 'Job activity', icon: Activity, href: '#' },
  ]},
  { group: 'Manage account', items: [
    { label: 'Account settings', icon: Settings, href: '#' },
    { label: 'Notifications', icon: Bell, href: '#' },
  ]}
];

const form = useForm({});

const logout = () => {
    form.post(route('logout'));
};
</script>

<template>
  <div class="min-h-screen bg-[#F3F6F9] font-sans antialiased text-[#1E293B] selection:bg-primary/20 selection:text-primary">
    <Topbar :user="user" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- Sidebar (1/3) -->
        <aside class="w-full lg:w-[320px] shrink-0 space-y-6">
          <!-- Profile Card -->
          <Card class="border-border/60 shadow-[0_2px_4px_rgba(0,0,0,0.02)] overflow-hidden rounded-2xl">
            <CardContent class="p-5">
              <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                  <Avatar class="h-14 w-14 rounded-xl border border-border shadow-sm shrink-0">
                    <AvatarImage :src="user?.avatar_url" />
                    <AvatarFallback class="bg-primary/5 text-primary font-bold text-lg">
                      {{ userInitials }}
                    </AvatarFallback>
                  </Avatar>
                  <div class="min-w-0">
                    <h2 class="font-display font-bold text-lg leading-tight truncate text-[#1E293B]">{{ user?.name }}</h2>
                    <p class="text-[11px] text-[#64748B] mt-0.5 leading-relaxed truncate">
                      {{ user?.role === 'arsitek' ? 'Candidate at Polinema' : 'Company Profile' }}
                    </p>
                    <p class="text-[11px] text-[#64748B] flex items-center gap-1.5 mt-0.5">
                      <MapPin class="w-2.5 h-2.5" />
                      {{ user?.location || 'Malang, East Java' }}
                    </p>
                  </div>
                </div>
                <Button variant="ghost" size="icon" class="h-7 w-7 rounded-lg hover:bg-slate-100 shrink-0">
                  <Edit2 class="w-3.5 h-3.5 text-[#64748B]" />
                </Button>
              </div>
            </CardContent>
          </Card>

          <!-- Navigation Menu -->
          <div class="space-y-7 px-1">
            <div v-for="group in menuItems" :key="group.group" class="space-y-2.5">
              <h3 class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 px-3">
                {{ group.group }}
              </h3>
              <nav class="space-y-0.5">
                <Link 
                  v-for="item in group.items" 
                  :key="item.label"
                  :href="item.href"
                  class="flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl transition-all duration-200 hover:bg-white hover:shadow-sm hover:translate-x-1 group text-[#334155]"
                >
                  <component :is="item.icon" class="w-4 h-4 text-slate-400 group-hover:text-primary transition-colors" />
                  {{ item.label }}
                </Link>
              </nav>
            </div>

            <div class="space-y-2.5 pt-2">
              <h3 class="text-[10px] font-bold uppercase tracking-[0.1em] text-slate-400 px-3">
                Account
              </h3>
               <button 
                  @click="logout"
                  class="w-full h-auto flex items-center gap-3 px-3.5 py-2.5 text-sm font-medium rounded-xl text-destructive hover:bg-destructive/5 transition-all duration-200 hover:translate-x-1 group"
                >
                  <LogOut class="w-4 h-4 text-destructive/70" />
                  Sign out
                </button>
            </div>

            <Button variant="outline" class="w-full justify-center rounded-xl bg-white border-border/60 h-10 font-bold text-[11px] uppercase tracking-wider shadow-sm hover:bg-slate-50 mt-4 text-[#334155]">
              Help Center
            </Button>
          </div>
        </aside>

        <!-- Main Content (2/3) -->
        <main class="flex-1 w-full space-y-6">
          <slot />
        </main>
      </div>
    </div>
  </div>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style>
