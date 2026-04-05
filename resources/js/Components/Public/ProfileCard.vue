<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";
import { 
    User, 
    MapPin, 
    GraduationCap, 
    Briefcase, 
    Pencil, 
    FileText, 
    Settings, 
    History,
    Upload,
    ExternalLink
} from "lucide-vue-next";
import { 
    Avatar, 
    AvatarImage, 
    AvatarFallback 
} from "@/Components/UI/ui/avatar";
import { Button } from "@/Components/UI/ui/button";

const props = defineProps({
    user: {
        type: Object,
        required: true
    }
});

const profile = computed(() => props.user.profile || {});

const fullName = computed(() => {
    if (profile.value.first_name && profile.value.last_name) {
        return `${profile.value.first_name} ${profile.value.last_name}`;
    }
    return props.user.name;
});

const jobTitle = computed(() => {
    if (profile.value.is_student) {
        return `Student at ${profile.value.school || 'your school'}`;
    }
    return profile.value.status_pekerjaan || 'No job status set';
});

const locationText = computed(() => {
    return profile.value.location || 'Location not set';
});
</script>

<template>
    <div class="w-full max-w-[320px] bg-background rounded-2xl overflow-hidden border border-border/50 shadow-2xl animate-in fade-in zoom-in duration-300">
        <!-- Header Profile -->
        <div class="relative p-5 pb-4 border-b border-border/50">
            <div class="flex items-start justify-between">
                <div class="flex items-center gap-4">
                    <Avatar class="h-16 w-16 ring-4 ring-primary/5 transition-transform hover:scale-105 duration-300">
                        <AvatarImage :src="user.avatar_url" :alt="fullName" />
                        <AvatarFallback class="bg-primary/10 text-primary text-xl font-bold">
                            {{ fullName.charAt(0).toUpperCase() }}
                        </AvatarFallback>
                    </Avatar>
                    <div class="flex flex-col">
                        <h3 class="text-[17px] font-bold text-foreground leading-tight">{{ fullName }}</h3>
                        <p class="text-[13px] text-muted-foreground mt-0.5">{{ jobTitle }}</p>
                        <div class="flex items-center text-[12px] text-muted-foreground mt-1.5 opacity-80">
                            <MapPin class="w-3 h-3 mr-1" />
                            {{ locationText }}
                        </div>
                    </div>
                </div>
                <Link :href="route('arsitek.profil.edit')" class="p-2 hover:bg-muted rounded-full transition-colors text-muted-foreground hover:text-primary">
                    <Pencil class="w-4 h-4" />
                </Link>
            </div>
        </div>

        <!-- Quick Info Details (Requested Fields) -->
        <div class="px-5 py-4 bg-muted/20 space-y-3 border-b border-border/50">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-0.5">
                    <span class="text-[11px] uppercase tracking-wider font-semibold text-muted-foreground opacity-70">Nama Depan</span>
                    <p class="text-[14px] font-medium text-foreground">{{ profile.first_name || '-' }}</p>
                </div>
                <div class="space-y-0.5">
                    <span class="text-[11px] uppercase tracking-wider font-semibold text-muted-foreground opacity-70">Nama Belakang</span>
                    <p class="text-[14px] font-medium text-foreground">{{ profile.last_name || '-' }}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 pt-1">
                <div class="space-y-0.5">
                    <span class="text-[11px] uppercase tracking-wider font-semibold text-muted-foreground opacity-70">Sekolah</span>
                    <p class="text-[13px] font-medium text-foreground line-clamp-1">{{ profile.school || '-' }}</p>
                </div>
                <div class="space-y-0.5">
                    <span class="text-[11px] uppercase tracking-wider font-semibold text-muted-foreground opacity-70">Gelar</span>
                    <p class="text-[13px] font-medium text-foreground">{{ profile.degree_type || '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Action Links -->
        <div class="p-2">
            <Link 
                :href="route('arsitek.profil.edit')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-primary/5 group transition-all duration-300">
                <span class="text-[14px] font-semibold text-foreground/90 group-hover:text-primary transition-colors">Resume & experience</span>
            </Link>
            
            <Link 
                :href="route('arsitek.pengaturan.index')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-primary/5 group transition-all duration-300">
                <span class="text-[14px] font-semibold text-foreground/90 group-hover:text-primary transition-colors">Job preferences</span>
            </Link>

            <Link 
                :href="route('arsitek.lamaran.index')"
                class="flex items-center px-4 py-3 rounded-xl hover:bg-primary/5 group transition-all duration-300">
                <span class="text-[14px] font-semibold text-foreground/90 group-hover:text-primary transition-colors">Job activity</span>
            </Link>
        </div>
    </div>
</template>

<style scoped>
.line-clamp-1 {
  display: -webkit-box;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;  
  overflow: hidden;
}
</style>
