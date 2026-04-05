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
    ChevronDown,
    LayoutDashboard
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
    <header class="bg-background/80 backdrop-blur-xl border-b border-border/50 h-16 px-6 flex items-center justify-between sticky top-0 z-40 transition-all duration-300">
        <div class="flex items-center gap-4 flex-1">
            <Button
                variant="ghost"
                size="icon"
                class="lg:hidden rounded-xl"
                @click="emit('toggle-sidebar')"
            >
                <Menu class="w-5 h-5" />
            </Button>
            
            <!-- Global Search -->
            <div class="hidden md:flex items-center relative max-w-sm w-full group">
                <Search class="absolute left-3 w-4 h-4 text-muted-foreground group-focus-within:text-primary transition-colors" />
                <Input 
                    placeholder="Cari fitur, lowongan, atau bantuan..." 
                    class="pl-10 h-10 bg-muted/30 border-border/50 focus:bg-background focus:border-primary/50 rounded-xl transition-all duration-300"
                />
            </div>
        </div>

        <div class="flex items-center gap-3">
            <!-- Notifications -->
            <Button variant="ghost" size="icon" class="rounded-xl relative hover:bg-primary/5 group">
                <Bell class="w-5 h-5 text-muted-foreground group-hover:text-primary transition-colors" />
                <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-primary rounded-full border-2 border-background"></span>
            </Button>

            <div class="h-8 w-px bg-border/50 mx-2"></div>

            <!-- User Menu -->
            <DropdownMenu>
                <DropdownMenuTrigger asChild>
                    <Button variant="ghost" class="p-1 pl-3 h-11 rounded-xl hover:bg-muted/50 border border-transparent hover:border-border/50 transition-all gap-3">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-bold text-foreground leading-none">{{ userName }}</p>
                            <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-widest mt-1">{{ userRole }}</p>
                        </div>
                        <Avatar class="h-8 w-8 rounded-lg">
                            <AvatarImage :src="user.avatar_url" :alt="userName" />
                            <AvatarFallback class="bg-primary/10 text-primary font-bold text-xs rounded-lg">
                                {{ userInitials }}
                            </AvatarFallback>
                        </Avatar>
                        <ChevronDown class="w-3.5 h-3.5 text-muted-foreground" />
                    </Button>
                </DropdownMenuTrigger>
                <DropdownMenuContent class="w-56 mt-2 rounded-2xl shadow-2xl border-border/50 p-2" align="end">
                    <DropdownMenuLabel class="font-normal px-3 py-3">
                        <div class="flex flex-col space-y-1">
                            <p class="text-sm font-bold leading-none">{{ userName }}</p>
                            <p class="text-xs leading-none text-muted-foreground mt-1">{{ user.email }}</p>
                        </div>
                    </DropdownMenuLabel>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem asChild class="rounded-xl cursor-pointer py-2.5">
                        <Link :href="route('profile.edit')" class="flex items-center w-full">
                            <User class="mr-2 h-4 w-4 text-primary" />
                            <span>Profil Anda</span>
                        </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem asChild class="rounded-xl cursor-pointer py-2.5">
                        <Link href="#" class="flex items-center w-full">
                            <Settings class="mr-2 h-4 w-4 text-primary" />
                            <span>Pengaturan</span>
                        </Link>
                    </DropdownMenuItem>
                    <DropdownMenuSeparator />
                    <DropdownMenuItem @click="logout" class="rounded-xl cursor-pointer py-2.5 text-destructive focus:bg-destructive/10 focus:text-destructive">
                        <LogOut class="mr-2 h-4 w-4" />
                        <span>Keluar</span>
                    </DropdownMenuItem>
                </DropdownMenuContent>
            </DropdownMenu>
        </div>
    </header>
</template>
