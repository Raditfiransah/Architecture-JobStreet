<script setup>
import { ref, computed } from "vue";
import { Head, Link, usePage, router } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { Search, ArrowRight, TrendingUp } from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Input } from "@/Components/UI/ui/input";
import { Badge } from "@/Components/UI/ui/badge";

const page = usePage();
const user = computed(() => page.props.auth.user);

const searchQuery = ref("");

const handleSearch = () => {
    if (!searchQuery.value.trim()) return;
    router.get(route("lowongan.index"), { q: searchQuery.value });
};

const popularTags = [
    "Arsitek Residensial",
    "BIM Specialist",
    "Interior Designer",
    "Urban Planner",
    "Fresh Graduate",
    "Senior Architect"
];
</script>

<template>
    <PublicLayout :transparent-navbar="true">
        <Head>
            <title>Loker Arsitek — Platform Karir Arsitek #1 di Indonesia</title>
            <meta
                name="description"
                content="Temukan lowongan kerja, proyek freelance, dan portofolio digital untuk arsitek Indonesia. Bergabung dengan 1.200+ arsitek terverifikasi."
            />
        </Head>

        <!-- Hero Section -->
        <main class="relative min-h-[90vh] flex flex-col items-center justify-center overflow-hidden">
            <!-- Background Decorations -->
            <div class="absolute inset-0 bg-gradient-to-b from-primary/5 via-background to-background -z-10"></div>
            <div class="absolute top-1/4 left-1/4 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] -z-10 animate-pulse"></div>
            <div class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-primary/5 rounded-full blur-[100px] -z-10"></div>

            <div class="w-full max-w-5xl px-6 py-20 text-center space-y-12 animate-in fade-in slide-in-from-bottom-8 duration-1000">
                <!-- Badging -->
                <div class="flex justify-center">
                    <Badge variant="secondary" class="px-4 py-1.5 rounded-full bg-primary/10 text-primary border-primary/20 font-bold tracking-wide uppercase text-[11px] flex items-center gap-2">
                        <TrendingUp class="w-3.5 h-3.5" />
                        Platform Karir Arsitek Terpercaya
                    </Badge>
                </div>

                <!-- Hero Text -->
                <div class="space-y-6">
                    <h1 class="text-[42px] md:text-[72px] font-display font-extrabold text-foreground leading-[1.05] tracking-tight">
                        Bangun Karir Impianmu sebagai <br />
                        <span class="text-primary italic relative">
                            Arsitek Profesional
                            <svg class="absolute -bottom-2 left-0 w-full h-3 text-primary/30" viewBox="0 0 100 10" preserveAspectRatio="none">
                                <path d="M0 5 Q 25 0, 50 5 T 100 5" fill="none" stroke="currentColor" stroke-width="4" />
                            </svg>
                        </span>
                    </h1>
                    <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto leading-relaxed font-medium">
                        Temukan ribuan lowongan kerja, proyek kolaboratif, dan kembangkan portofolio digitalmu di komunitas arsitek terbesar di Indonesia.
                    </p>
                </div>

                <!-- Search Interface Premium -->
                <div class="w-full max-w-3xl mx-auto">
                    <div class="bg-card/80 backdrop-blur-xl border-border/50 border-2 rounded-[32px] p-2 shadow-2xl shadow-primary/10 hover:border-primary/30 hover:shadow-primary/20 transition-all duration-500 group">
                        <form @submit.prevent="handleSearch" class="flex flex-col md:flex-row items-stretch md:items-center">
                            <div class="flex-1 flex items-center px-6 py-2">
                                <Search class="w-6 h-6 text-muted-foreground shrink-0 group-focus-within:text-primary transition-colors" />
                                <Input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Cari posisi, keahlian, atau perusahaan..."
                                    required
                                    class="border-0 bg-transparent text-lg h-14 md:h-16 shadow-none focus-visible:ring-0 placeholder:text-muted-foreground/50 font-medium"
                                />
                            </div>
                            <Button
                                type="submit"
                                size="lg"
                                class="rounded-[24px] px-10 h-14 md:h-16 font-bold text-lg shadow-xl shadow-primary/20 active:scale-95 transition-all"
                            >
                                Cari Lowongan
                                <ArrowRight class="ml-2 w-5 h-5 group-hover:translate-x-1 transition-transform" />
                            </Button>
                        </form>
                    </div>

                    <!-- Popular Tags -->
                    <div class="mt-10 flex flex-wrap items-center justify-center gap-2.5">
                        <span class="text-xs font-bold uppercase tracking-widest text-muted-foreground mr-2">Populer:</span>
                        <Link
                            v-for="tag in popularTags"
                            :key="tag"
                            :href="route('lowongan.index', { q: tag })"
                        >
                            <Badge variant="outline" class="px-5 py-2 rounded-full border-border/50 bg-background/50 hover:bg-primary/5 hover:text-primary hover:border-primary/30 transition-all cursor-pointer font-semibold text-[13px]">
                                {{ tag }}
                            </Badge>
                        </Link>
                    </div>
                </div>
            </div>
        </main>
    </PublicLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Bricolage Grotesque', sans-serif;
}
</style>
