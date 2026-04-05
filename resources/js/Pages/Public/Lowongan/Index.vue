<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { 
    MapPin, 
    Briefcase, 
    DollarSign, 
    Calendar, 
    Bookmark, 
    Share2, 
    Building2,
    Clock,
    CheckCircle2,
    ChevronRight,
    ArrowUpRight
} from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/Components/UI/ui/avatar";
import { Separator } from "@/Components/UI/ui/separator";

const props = defineProps({
    title: String,
    jobs: Array,
    filters: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);

const searchQuery = ref(props.filters?.q || "");
const locationQuery = ref(props.filters?.l || "");

// Selection State
const selectedJob = ref(props.jobs && props.jobs.length > 0 ? props.jobs[0] : null);

// Watch for props change (e.g. after search) to reset selectedJob if needed
watch(() => props.jobs, (newJobs) => {
    if (newJobs?.length > 0) {
        if (!selectedJob.value || !newJobs.find(j => j.id === selectedJob.value?.id)) {
            selectedJob.value = newJobs[0];
        }
    } else {
        selectedJob.value = null;
    }
});

const handleSearch = () => {
    router.get(route("lowongan.index"), { 
        q: searchQuery.value,
        l: locationQuery.value 
    }, { 
        preserveState: true,
        replace: true 
    });
};

const selectJob = (job) => {
    selectedJob.value = job;
};

const handleAction = (action) => {
    if (!user.value) {
        router.get(route('login'));
        return;
    }
    // Handle authenticated action (e.g. apply, save)
    console.log(`${action} job:`, selectedJob.value?.posisi);
};
</script>

<template>
    <PublicLayout :show-search="true" :show-footer="false">
        <Head :title="title" />

        <main class="flex-1 w-full max-w-[1440px] mx-auto flex flex-col md:flex-row overflow-hidden h-[calc(100vh-64px-73px)] mt-0.5">
            <!-- Sidebar / Job List -->
            <aside class="w-full md:w-[380px] lg:w-[440px] shrink-0 border-r border-border/50 bg-card/30 overflow-y-auto custom-scrollbar flex flex-col">
                <div class="sticky top-0 bg-card/80 backdrop-blur-xl z-20 px-6 py-4 border-b border-border/50 flex items-center justify-between">
                    <span class="text-xs font-bold uppercase tracking-widest text-muted-foreground">{{ jobs?.length || 0 }} lowongan ditemukan</span>
                    <Button variant="ghost" size="sm" class="text-xs rounded-lg h-8 px-3 font-bold text-primary">Terbaru</Button>
                </div>

                <div class="p-3 space-y-2">
                    <div
                        v-for="job in jobs"
                        :key="job.id"
                        @click="selectJob(job)"
                        :class="[
                            'group p-5 rounded-[24px] border-2 transition-all duration-300 cursor-pointer relative overflow-hidden',
                            selectedJob?.id === job.id 
                                ? 'bg-primary/[0.03] border-primary/40 shadow-lg shadow-primary/5' 
                                : 'bg-card border-transparent hover:border-border/80 hover:shadow-xl hover:-translate-y-0.5'
                        ]"
                    >
                        <!-- Active Indicator -->
                        <div v-if="selectedJob?.id === job.id" class="absolute top-0 right-0 w-16 h-16 bg-primary/10 rounded-bl-[40px] -mr-4 -mt-4 -z-0"></div>

                        <div class="relative z-10 flex gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-bold text-muted-foreground/80 uppercase tracking-widest mb-1">{{ job.perusahaan }}</p>
                                <h3 class="text-[17px] font-display font-bold text-foreground group-hover:text-primary transition-colors leading-tight mb-2">{{ job.posisi }}</h3>
                                
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    <Badge variant="outline" class="rounded-lg bg-muted/50 border-border/50 font-semibold py-0 text-[11px]">{{ job.tipe }}</Badge>
                                    <Badge variant="outline" class="rounded-lg bg-primary/5 border-primary/20 text-primary font-bold py-0 text-[11px]">Rp {{ job.gaji }}</Badge>
                                </div>
                                
                                <div class="flex items-center gap-3 text-[12px] font-semibold text-muted-foreground">
                                    <span class="flex items-center gap-1"><MapPin class="w-3.5 h-3.5" /> {{ job.kota }}</span>
                                    <span class="flex items-center gap-1"><Clock class="w-3.5 h-3.5" /> 2 hari lalu</span>
                                </div>
                            </div>
                            
                            <div class="self-start">
                                <Button variant="ghost" size="icon" class="rounded-full w-8 h-8 opacity-40 group-hover:opacity-100 group-hover:bg-primary/10 group-hover:text-primary transition-all">
                                    <Bookmark class="w-4 h-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Detail Pane -->
            <section class="flex-1 bg-background overflow-y-auto px-6 lg:px-12 py-10 custom-scrollbar relative">
                <div v-if="selectedJob" class="max-w-4xl mx-auto space-y-10 pb-32 animate-in fade-in slide-in-from-right-8 duration-500">
                    <!-- Header Card -->
                    <div class="bg-card border-2 border-border/50 rounded-[40px] p-8 md:p-12 shadow-2xl shadow-primary/5 overflow-hidden relative group">
                        <!-- Decoration -->
                        <div class="absolute -top-12 -right-12 w-48 h-48 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors duration-500"></div>

                        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-8 mb-10">
                            <div class="flex-1 space-y-3">
                                <div class="flex items-center gap-3">
                                    <p class="text-xl font-display font-medium text-muted-foreground">{{ selectedJob.perusahaan }}</p>
                                    <Badge class="bg-primary/10 text-primary hover:bg-primary/10 rounded-full border-primary/20 px-3">Terverifikasi</Badge>
                                </div>
                                <h1 class="text-4xl md:text-5xl font-display font-black text-foreground tracking-tight leading-none">{{ selectedJob.posisi }}</h1>
                                
                                <div class="flex flex-wrap items-center gap-6 pt-2">
                                    <div class="flex items-center gap-2 text-primary font-bold text-lg">
                                        <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><DollarSign class="w-4 h-4" /></div>
                                        <span>Rp {{ selectedJob.gaji }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-muted-foreground font-semibold">
                                        <div class="w-8 h-8 rounded-lg bg-muted flex items-center justify-center"><MapPin class="w-4 h-4 text-primary" /></div>
                                        <span>{{ selectedJob.kota }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-muted-foreground font-semibold">
                                        <div class="w-8 h-8 rounded-lg bg-muted flex items-center justify-center"><Briefcase class="w-4 h-4 text-primary" /></div>
                                        <span>{{ selectedJob.tipe }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative z-10 flex flex-wrap items-center gap-4">
                            <Button @click="handleAction('apply')" size="lg" class="rounded-[20px] px-12 h-14 font-bold text-lg shadow-xl shadow-primary/20 active:scale-95 group/btn flex-1 md:flex-none">
                                {{ user ? 'Lamar Sekarang' : 'Melamar' }}
                                <ArrowUpRight class="ml-2 w-5 h-5 group-hover/btn:translate-x-1 group-hover/btn:-translate-y-1 transition-transform" />
                            </Button>
                            <Button @click="handleAction('save')" variant="outline" size="lg" class="rounded-[20px] px-8 h-14 border-2 border-border/80 hover:border-primary/30 font-bold transition-all group/save">
                                <Bookmark class="w-5 h-5 mr-2 group-hover/save:fill-primary group-hover/save:text-primary transition-all" />
                                Simpan
                            </Button>
                            <Button variant="ghost" size="icon" class="rounded-[20px] w-14 h-14 border-2 border-transparent hover:border-border/50 hover:bg-muted/50">
                                <Share2 class="w-5 h-5" />
                            </Button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 px-2">
                        <div class="lg:col-span-2 space-y-12">
                            <section>
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-1 h-8 bg-primary rounded-full"></div>
                                    <h3 class="text-2xl font-display font-bold">Tentang Pekerjaan</h3>
                                </div>
                                <p class="text-[17px] text-muted-foreground leading-relaxed whitespace-pre-line font-medium">
                                    {{ selectedJob.deskripsi }}
                                </p>
                            </section>

                            <section>
                                <div class="flex items-center gap-3 mb-8">
                                    <div class="w-1 h-8 bg-primary rounded-full"></div>
                                    <h3 class="text-2xl font-display font-bold">Kualifikasi & Peran</h3>
                                </div>
                                
                                <div class="grid gap-8">
                                    <div class="space-y-4">
                                        <h4 class="text-lg font-bold text-foreground/80 flex items-center gap-2">
                                            <CheckCircle2 class="w-5 h-5 text-primary" />
                                            Persyaratan Utama
                                        </h4>
                                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <li v-for="(syarat, idx) in selectedJob.syarat" :key="idx" class="p-4 rounded-2xl bg-muted/30 border border-border/30 text-sm font-semibold flex items-center gap-3">
                                                <div class="w-1.5 h-1.5 rounded-full bg-primary/60 shrink-0"></div>
                                                {{ syarat }}
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="space-y-4">
                                        <h4 class="text-lg font-bold text-foreground/80 flex items-center gap-2">
                                            <CheckCircle2 class="w-5 h-5 text-primary" />
                                            Tanggung Jawab
                                        </h4>
                                        <ul class="grid grid-cols-1 gap-3">
                                            <li v-for="(task, idx) in selectedJob.tanggung_jawab" :key="idx" class="p-4 rounded-2xl bg-muted/30 border border-border/30 text-sm font-semibold flex items-center gap-3">
                                                <div class="w-1.5 h-1.5 rounded-full bg-primary/60 shrink-0"></div>
                                                {{ task }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </section>
                        </div>

                        <!-- Side Specs -->
                        <div class="space-y-8">
                            <Card class="rounded-[32px] border-2 border-border/50 overflow-hidden shadow-xl shadow-primary/5">
                                <CardHeader class="pb-2">
                                    <CardTitle class="text-sm font-bold uppercase tracking-widest text-muted-foreground">Ringkasan Perusahaan</CardTitle>
                                </CardHeader>
                                <CardContent class="space-y-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-muted flex items-center justify-center"><Building2 class="w-5 h-5 text-primary" /></div>
                                        <div>
                                            <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Industri</p>
                                            <p class="text-sm font-bold">Architecture & Design</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-muted flex items-center justify-center"><Users class="w-5 h-5 text-primary" /></div>
                                        <div>
                                            <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Ukuran</p>
                                            <p class="text-sm font-bold">50 - 200 Karyawan</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-muted flex items-center justify-center"><Calendar class="w-5 h-5 text-primary" /></div>
                                        <div>
                                            <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Diposting</p>
                                            <p class="text-sm font-bold">April 05, 2024</p>
                                        </div>
                                    </div>
                                    
                                    <Separator class="bg-border/50" />
                                    
                                    <Button variant="outline" class="w-full rounded-xl font-bold border-border/80 hover:border-primary/30 h-11">
                                        Lihat Profil Perusahaan
                                    </Button>
                                </CardContent>
                            </Card>
                            
                            <div class="p-8 rounded-[32px] bg-gradient-to-br from-primary to-primary-foreground text-white shadow-2xl shadow-primary/20 relative overflow-hidden group">
                                <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000"></div>
                                <h4 class="text-xl font-display font-black mb-4 relative z-10">Tingkatkan Peluangmu!</h4>
                                <p class="text-sm font-medium text-white/80 mb-6 relative z-10 leading-relaxed">Arsitek dengan portofolio lengkap memiliki peluang 4x lebih besar.</p>
                                <Button variant="secondary" class="w-full rounded-[14px] font-bold h-11 relative z-10 bg-white text-primary hover:bg-white shadow-lg">Lengkapi Profil Now</Button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div v-else class="h-full flex flex-col items-center justify-center text-center p-12 space-y-6">
                    <div class="w-24 h-24 bg-muted rounded-full flex items-center justify-center">
                        <Briefcase class="w-10 h-10 text-muted-foreground/40" />
                    </div>
                    <div class="max-w-xs space-y-2">
                        <h3 class="text-xl font-display font-bold">Pilih Lowongan</h3>
                        <p class="text-sm text-muted-foreground font-medium leading-relaxed">Silakan pilih lowongan di sebelah kiri untuk melihat detail pekerjaan secara lengkap.</p>
                    </div>
                </div>
            </section>
        </main>
    </PublicLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}

.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: transparent;
    border-radius: 10px;
}
.custom-scrollbar:hover::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.05);
}
</style>