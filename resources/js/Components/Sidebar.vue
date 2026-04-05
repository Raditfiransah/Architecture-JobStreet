<script setup>
import { Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { 
    LayoutDashboard, 
    User, 
    FolderRoot, 
    Briefcase, 
    FileText, 
    Users, 
    MessageSquare,
    ChevronRight,
    Search,
    LogOut
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Separator } from "@/Components/UI/ui/separator";

const props = defineProps({
    role: {
        type: String,
        required: false,
        default: '',
    },
});

const panelTitle = computed(() => {
    if (!props.role) return 'User Panel';
    const titles = {
        arsitek: 'Arsitek',
        perusahaan: 'Perusahaan',
        client: 'Client',
        admin: 'Administrator',
    };
    return titles[props.role] || 'User Panel';
});

const isRoute = (name) => route().current(name);

const form = useForm({});
const logout = () => {
    form.post(route('logout'));
};
</script>

<template>
    <aside class="bg-card text-card-foreground w-64 min-h-screen flex-shrink-0 border-r border-border/50 flex flex-col sticky top-0 h-screen">
        <div class="p-6">
            <Link :href="route('home')" class="flex items-center gap-2 group mb-6">
                <div class="w-8 h-8 rounded-lg bg-primary flex items-center justify-center text-primary-foreground shadow-lg shadow-primary/20 group-hover:rotate-12 transition-all duration-500">
                    <Briefcase class="w-4 h-4" />
                </div>
                <span class="text-xl font-display font-extrabold tracking-tight text-primary">
                    Loker<span class="text-foreground">Arsitek</span>
                </span>
            </Link>
            
            <div class="px-3 py-2 rounded-xl bg-muted/50 border border-border/50">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground mb-1">Access Level</p>
                <p class="text-sm font-bold text-foreground">{{ panelTitle }}</p>
            </div>
        </div>

        <nav class="flex-1 px-3 space-y-1 overflow-y-auto mt-2">
            <!-- Arsitek Menu -->
            <template v-if="role === 'arsitek'">
                <div class="px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-muted-foreground/60 mt-4 mb-1">Utama</div>
                <Button asChild variant="ghost" :class="[isRoute('arsitek.dashboard') ? 'bg-primary/10 text-primary hover:bg-primary/15' : 'text-muted-foreground']" class="w-full justify-start rounded-xl font-semibold mb-1">
                    <Link :href="route('arsitek.dashboard')">
                        <LayoutDashboard class="mr-3 h-4 w-4" />
                        Dashboard
                    </Link>
                </Button>
                <Button asChild variant="ghost" class="w-full justify-start rounded-xl font-semibold text-muted-foreground mb-1 transition-all hover:translate-x-1">
                    <Link href="#">
                        <User class="mr-3 h-4 w-4" />
                        Profil Saya
                    </Link>
                </Button>

                <div class="px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-muted-foreground/60 mt-4 mb-1">Karir & Kerja</div>
                <Button asChild variant="ghost" class="w-full justify-start rounded-xl font-semibold text-muted-foreground mb-1 transition-all hover:translate-x-1">
                    <Link href="#">
                        <FolderRoot class="mr-3 h-4 w-4" />
                        Portofolio
                    </Link>
                </Button>
                <Button asChild variant="ghost" class="w-full justify-start rounded-xl font-semibold text-muted-foreground mb-1 transition-all hover:translate-x-1">
                    <Link href="#">
                        <Briefcase class="mr-3 h-4 w-4" />
                        Lamaran Kerja
                    </Link>
                </Button>
                <Button asChild variant="ghost" class="w-full justify-start rounded-xl font-semibold text-muted-foreground mb-1 transition-all hover:translate-x-1">
                    <Link href="#">
                        <FileText class="mr-3 h-4 w-4" />
                        Proposal Proyek
                    </Link>
                </Button>
            </template>

            <!-- Perusahaan Menu -->
            <template v-else-if="role === 'perusahaan'">
                <div class="px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-muted-foreground/60 mt-4 mb-1">Dashboard</div>
                <Button asChild variant="ghost" :class="[isRoute('perusahaan.dashboard') ? 'bg-primary/10 text-primary hover:bg-primary/15' : 'text-muted-foreground']" class="w-full justify-start rounded-xl font-semibold mb-1">
                    <Link :href="route('perusahaan.dashboard')">
                        <LayoutDashboard class="mr-3 h-4 w-4" />
                        Ringkasan
                    </Link>
                </Button>
                
                <div class="px-3 py-2 text-[11px] font-bold uppercase tracking-widest text-muted-foreground/60 mt-4 mb-1">Manajemen</div>
                <Button asChild variant="ghost" class="w-full justify-start rounded-xl font-semibold text-muted-foreground mb-1 transition-all hover:translate-x-1">
                    <Link href="#">
                        <FileText class="mr-3 h-4 w-4" />
                        Kelola Lowongan
                    </Link>
                </Button>
                <Button asChild variant="ghost" class="w-full justify-start rounded-xl font-semibold text-muted-foreground mb-1 transition-all hover:translate-x-1">
                    <Link href="#">
                        <Users class="mr-3 h-4 w-4" />
                        Kandidat Masuk
                    </Link>
                </Button>
            </template>

            <!-- Client Menu -->
            <template v-else-if="role === 'client'">
                <Button asChild variant="ghost" :class="[isRoute('client.dashboard') ? 'bg-primary/10 text-primary hover:bg-primary/15' : 'text-muted-foreground']" class="w-full justify-start rounded-xl font-semibold mb-1">
                    <Link :href="route('client.dashboard')">
                        <LayoutDashboard class="mr-3 h-4 w-4" />
                        Dashboard
                    </Link>
                </Button>
                <Button asChild variant="ghost" class="w-full justify-start rounded-xl font-semibold text-muted-foreground mb-1 transition-all hover:translate-x-1">
                    <Link href="#">
                        <FolderRoot class="mr-3 h-4 w-4" />
                        Proyek Saya
                    </Link>
                </Button>
            </template>

            <!-- Admin Menu -->
            <template v-else-if="role === 'admin'">
                <Button asChild variant="ghost" :class="[isRoute('admin.dashboard') ? 'bg-primary/10 text-primary hover:bg-primary/15' : 'text-muted-foreground']" class="w-full justify-start rounded-xl font-semibold mb-1">
                    <Link :href="route('admin.dashboard')">
                        <LayoutDashboard class="mr-3 h-4 w-4" />
                        Dashboard
                    </Link>
                </Button>
                <Button asChild variant="ghost" class="w-full justify-start rounded-xl font-semibold text-muted-foreground mb-1 transition-all hover:translate-x-1">
                    <Link href="#">
                        <Users class="mr-3 h-4 w-4" />
                        Kelola User
                    </Link>
                </Button>
            </template>
        </nav>

        <div class="px-6 py-6 border-t border-border/50">
            <Button asChild variant="outline" class="w-full justify-start rounded-xl font-semibold text-muted-foreground bg-muted/30 border-border/50 transition-all hover:border-primary/30 hover:text-primary mb-2">
                <Link :href="route('info.index')">
                    <MessageSquare class="mr-3 h-4 w-4" />
                    Info Hub
                </Link>
            </Button>

            <Button @click="logout" variant="ghost" class="w-full justify-start rounded-xl font-semibold text-destructive hover:bg-destructive/10 hover:text-destructive border border-transparent hover:border-destructive/20 transition-all">
                <LogOut class="mr-3 h-4 w-4" />
                Keluar
            </Button>
            
            <div class="mt-6 flex items-center gap-3 px-1">
                <div class="w-2 h-2 rounded-full bg-primary animate-pulse"></div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-muted-foreground">System Online</span>
            </div>
        </div>
    </aside>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
</style>
