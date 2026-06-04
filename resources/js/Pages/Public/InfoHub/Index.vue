<script setup>
import { computed, shallowRef } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { Button } from '@/Components/UI/ui/button';
import { Skeleton } from '@/Components/UI/ui/skeleton';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/UI/ui/select';
import { SlidersHorizontal } from 'lucide-vue-next';
import InfoCard from './Components/InfoCard.vue';

const props = defineProps({
    title: {
        type: String,
        default: 'Papan Informasi',
    },
    infohubs: {
        type: Object,
        default: null,
    },
});

const categories = ['Semua', 'Event', 'Magang', 'Sayembara'];
const selectedCategory = shallowRef('Semua');
const selectedSort = shallowRef('terbaru');
const isLoading = shallowRef(false);

const sourceItems = computed(() => {
    if (Array.isArray(props.infohubs?.data)) {
        return props.infohubs.data;
    }

    return [];
});

const filteredInfohubs = computed(() => {
    return [...sourceItems.value]
        .filter((info) => selectedCategory.value === 'Semua' || info.kategori === selectedCategory.value)
        .sort((a, b) => {
            const firstDate = new Date(a.created_at).getTime();
            const secondDate = new Date(b.created_at).getTime();

            return selectedSort.value === 'terbaru'
                ? secondDate - firstDate
                : firstDate - secondDate;
        });
});

const featuredInfo = computed(() => filteredInfohubs.value[0] ?? null);
const regularInfohubs = computed(() => filteredInfohubs.value.slice(1));
</script>

<template>
    <PublicLayout>
        <Head :title="title" />

        <main class="flex-1 w-full max-w-[1280px] mx-auto px-6 py-10 md:py-14">
            <section class="max-w-3xl mb-10 md:mb-12">
                <p class="mb-3 text-xs font-bold uppercase tracking-[0.24em] text-primary">Info Hub</p>
                <h1 class="text-4xl md:text-5xl font-bold tracking-tight text-foreground mb-4">Papan Informasi Arsitektur</h1>
                <p class="text-base md:text-lg text-muted-foreground leading-relaxed">
                    Dapatkan informasi terbaru seputar Event Arsitektur, Info Magang, dan Sayembara Desain.
                </p>
            </section>

            <section class="mb-8 flex flex-col gap-4 border-y border-border/70 py-4 md:flex-row md:items-center md:justify-between">
                <div class="flex flex-wrap gap-2">
                    <Button
                        v-for="category in categories"
                        :key="category"
                        type="button"
                        size="sm"
                        :variant="selectedCategory === category ? 'default' : 'outline'"
                        class="rounded-full"
                        @click="selectedCategory = category"
                    >
                        {{ category }}
                    </Button>
                </div>

                <div class="flex items-center gap-3 md:w-[260px]">
                    <SlidersHorizontal class="h-4 w-4 text-muted-foreground" />
                    <Select v-model="selectedSort">
                        <SelectTrigger class="h-10 rounded-full">
                            <SelectValue placeholder="Urutkan" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="terbaru">Terbaru</SelectItem>
                            <SelectItem value="terlama">Terlama</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
            </section>

            <section v-if="isLoading" class="space-y-8">
                <Skeleton class="aspect-video w-full rounded-xl md:h-[320px]" />
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <Skeleton v-for="item in 3" :key="item" class="aspect-video rounded-xl" />
                </div>
            </section>

            <section v-else-if="filteredInfohubs.length > 0" class="space-y-8">
                <InfoCard v-if="featuredInfo" :info="featuredInfo" featured />

                <div v-if="regularInfohubs.length > 0" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                    <InfoCard
                        v-for="info in regularInfohubs"
                        :key="info.id"
                        :info="info"
                    />
                </div>
            </section>

            <EmptyState
                v-else
                title="Belum Ada Informasi"
                description="Tidak ada pengumuman yang cocok dengan filter saat ini. Coba pilih kategori lain atau ubah urutan daftar."
            />
        </main>
    </PublicLayout>
</template>
