<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/UI/ui/card';
import { Badge } from '@/Components/UI/ui/badge';
import Pagination from '@/Components/Pagination.vue';
import { Calendar, Info } from 'lucide-vue-next';

defineProps({
    title: String,
    infohubs: Object,
});

const getBadgeVariant = (kategori) => {
    switch(kategori) {
        case 'Event': return 'default';
        case 'Sayembara': return 'destructive';
        case 'Magang': return 'secondary';
        default: return 'outline';
    }
};
</script>

<template>
    <PublicLayout>
        <Head :title="title" />

        <main class="flex-1 w-full max-w-[1280px] mx-auto px-6 py-12 md:py-16">
            <!-- Header Section -->
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-foreground mb-4">Papan Pengumuman</h1>
                <p class="text-lg text-muted-foreground leading-relaxed">
                    Dapatkan informasi terbaru seputar Event Arsitektur, Info Magang, dan Sayembara Desain.
                </p>
            </div>

            <!-- Grid Layout -->
            <div v-if="infohubs.data.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <Card v-for="info in infohubs.data" :key="info.id" class="overflow-hidden border-border/50 hover:border-primary/30 transition-all hover:shadow-md flex flex-col group">
                    <div class="relative w-full h-48 md:h-56 bg-muted overflow-hidden flex-shrink-0">
                        <img v-if="info.gambar_poster" :src="`/storage/${info.gambar_poster}`" class="object-cover w-full h-full group-hover:scale-105 transition-transform duration-500" alt="Poster Info" />
                        <div v-else class="w-full h-full flex items-center justify-center bg-primary/5 text-primary">
                            <Info class="w-12 h-12 opacity-20" />
                        </div>
                        <div class="absolute top-4 right-4">
                            <Badge :variant="getBadgeVariant(info.kategori)" class="shadow-sm font-bold uppercase tracking-wider text-[10px] px-2.5 py-1 backdrop-blur-md">
                                {{ info.kategori }}
                            </Badge>
                        </div>
                    </div>
                    
                    <CardHeader class="pb-3 flex-shrink-0">
                        <div class="flex items-center gap-2 text-xs text-muted-foreground mb-2">
                            <Calendar class="w-3.5 h-3.5" />
                            <span>{{ new Date(info.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</span>
                        </div>
                        <CardTitle class="text-xl font-bold line-clamp-2 leading-tight group-hover:text-primary transition-colors">
                            {{ info.judul }}
                        </CardTitle>
                    </CardHeader>
                    
                    <CardContent class="flex-1">
                        <p class="text-sm text-muted-foreground line-clamp-3 leading-relaxed">
                            {{ info.deskripsi }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-24 bg-muted/20 rounded-3xl border border-border/50">
                <div class="w-20 h-20 bg-muted rounded-full flex items-center justify-center mx-auto mb-6">
                    <Info class="w-10 h-10 text-muted-foreground/50" />
                </div>
                <h3 class="text-2xl font-bold mb-2">Belum Ada Informasi</h3>
                <p class="text-muted-foreground">Saat ini belum ada pengumuman atau event yang diterbitkan.</p>
            </div>

            <!-- Pagination -->
            <div v-if="infohubs.links && infohubs.links.length > 3" class="mt-12 flex justify-center">
                <Pagination :links="infohubs.links" />
            </div>
        </main>
    </PublicLayout>
</template>
