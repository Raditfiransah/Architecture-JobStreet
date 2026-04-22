<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
  LogOut, 
  User, 
  Settings, 
  Bell, 
  Search, 
  Menu,
  ChevronDown
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { 
  Avatar, 
  AvatarImage, 
  AvatarFallback 
} from "@/Components/UI/ui/avatar";
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from "@/Components/UI/ui/dropdown-menu";

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
  <header class="bg-background/95 backdrop-blur border-b border-border h-16 px-6 flex items-center justify-between sticky top-0 z-40">
    <div class="flex items-center gap-4 flex-1">
      <Button
        variant="ghost"
        size="icon"
        class="lg:hidden rounded-lg"
        @click="emit('toggle-sidebar')"
      >
        <Menu class="w-5 h-5" />
      </Button>

      <div class="hidden md:flex items-center relative max-w-sm w-full">
        <Search class="absolute left-3 w-4 h-4 text-muted-foreground" />
        <Input 
          placeholder="Cari fitur, lowongan, atau bantuan..." 
          class="pl-10 h-10 bg-muted/20 border-border focus:border-primary/50 rounded-lg"
        />
      </div>
    </div>

    <div class="flex items-center gap-3">
      <Button variant="ghost" size="icon" class="rounded-lg relative">
        <Bell class="w-5 h-5 text-muted-foreground" />
        <span class="absolute top-2 right-2 w-2 h-2 bg-primary rounded-full"></span>
      </Button>

      <div class="h-8 w-px bg-border/50 mx-2"></div>

      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button variant="ghost" class="h-10 rounded-lg gap-2 px-3 border border-transparent hover:border-border/50">
            <div class="text-right hidden sm:block">
              <p class="text-sm font-medium text-foreground leading-none">{{ userName }}</p>
              <p class="text-[10px] font-medium text-muted-foreground uppercase tracking-wider mt-1">{{ userRole }}</p>
            </div>
            <Avatar class="h-8 w-8 rounded-md">
              <AvatarImage :src="user.avatar_url" :alt="userName" />
              <AvatarFallback class="bg-primary/10 text-primary font-bold text-xs">
                {{ userInitials }}
              </AvatarFallback>
            </Avatar>
            <ChevronDown class="w-3.5 h-3.5 text-muted-foreground" />
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent class="w-56 mt-2 rounded-xl border-border/50 p-2" align="end">
          <DropdownMenuLabel class="font-normal px-3 py-3">
            <div class="flex flex-col space-y-1">
              <p class="text-sm font-medium leading-none">{{ userName }}</p>
              <p class="text-xs leading-none text-muted-foreground mt-1">{{ user.email }}</p>
            </div>
          </DropdownMenuLabel>
          <DropdownMenuSeparator />
          <DropdownMenuItem asChild class="rounded-lg cursor-pointer py-2">
            <Link :href="route(user.role === 'admin' ? 'admin.dashboard' : user.role + '.profil.edit')" class="flex items-center w-full">
              <User class="mr-2 h-4 w-4 text-primary" />
              <span>Profil Saya</span>
            </Link>
          </DropdownMenuItem>
          <DropdownMenuSeparator />
          <DropdownMenuItem @click="logout" class="rounded-lg cursor-pointer py-2 text-destructive focus:bg-destructive/10 focus:text-destructive">
            <LogOut class="mr-2 h-4 w-4" />
            <span>Keluar</span>
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    </div>
  </header>
</template>
