<script setup>
import { computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import { Badge } from '@/Components/UI/ui/badge';
import { Button } from '@/Components/UI/ui/button';
import { Calendar, ChevronLeft, Info } from 'lucide-vue-next';

const props = defineProps({
    title: {
        type: String,
        default: 'Detail Info Hub',
    },
    infoHub: {
        type: Object,
        required: true,
    },
});

const formattedDate = computed(() => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(props.infoHub.created_at));
});

const badgeVariant = computed(() => {
    const variants = {
        Event: 'default',
        Magang: 'secondary',
        Sayembara: 'destructive',
    };

    return variants[props.infoHub.kategori] ?? 'outline';
});
</script>

<template>
    <PublicLayout>
        <Head :title="title" />

        <main class="w-full max-w-[1120px] mx-auto px-6 py-10 md:py-14">
            <div class="mb-8">
                <Button asChild variant="ghost" class="mb-6 -ml-3 gap-2">
                    <Link :href="route('info.index')">
                        <ChevronLeft class="h-4 w-4" />
                        Kembali ke Info Hub
                    </Link>
                </Button>

                <div class="flex flex-wrap items-center gap-3">
                    <Badge :variant="badgeVariant" class="text-[10px] font-bold uppercase tracking-wider">
                        {{ infoHub.kategori }}
                    </Badge>
                    <span class="inline-flex items-center gap-2 text-sm text-muted-foreground">
                        <Calendar class="h-4 w-4" />
                        {{ formattedDate }}
                    </span>
                </div>

                <h1 class="mt-5 max-w-4xl text-3xl font-bold tracking-tight text-foreground md:text-5xl">
                    {{ infoHub.judul }}
                </h1>
            </div>

            <div class="overflow-hidden rounded-xl border border-border/70 bg-muted">
                <img
                    v-if="infoHub.image_url"
                    :src="infoHub.image_url"
                    :alt="`Poster ${infoHub.judul}`"
                    class="aspect-video w-full object-cover"
                />
                <div v-else class="flex aspect-video w-full items-center justify-center bg-primary/5 text-primary">
                    <Info class="h-14 w-14 opacity-30" />
                </div>
            </div>

            <article class="prose prose-neutral mt-8 max-w-3xl text-foreground prose-p:text-muted-foreground prose-p:leading-8">
                <p class="whitespace-pre-line text-base leading-8 text-muted-foreground">
                    {{ infoHub.deskripsi }}
                </p>
            </article>
        </main>
    </PublicLayout>
</template>
