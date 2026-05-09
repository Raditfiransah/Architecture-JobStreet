<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/Components/UI/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/UI/ui/table';
import { Badge } from '@/Components/UI/ui/badge';
import Pagination from '@/Components/Pagination.vue';
import { PlusCircle, Image as ImageIcon } from 'lucide-vue-next';

defineProps({
    infohubs: Object,
});
</script>

<template>
    <Head title="Manajemen InfoHub" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-foreground">Info Hub</h1>
                    <p class="text-muted-foreground mt-1">Kelola papan pengumuman (Event, Sayembara, Magang).</p>
                </div>
                <Link :href="route('admin.info.create')">
                    <Button class="gap-2">
                        <PlusCircle class="w-4 h-4" />
                        Tambah Info Baru
                    </Button>
                </Link>
            </div>

            <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
                <Table>
                    <TableHeader class="bg-muted/30">
                        <TableRow>
                            <TableHead class="font-bold text-xs uppercase tracking-wider py-4 w-[100px]">Poster</TableHead>
                            <TableHead class="font-bold text-xs uppercase tracking-wider">Judul</TableHead>
                            <TableHead class="font-bold text-xs uppercase tracking-wider">Kategori</TableHead>
                            <TableHead class="font-bold text-xs uppercase tracking-wider">Tgl Dibuat</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="info in infohubs.data" :key="info.id" class="group hover:bg-muted/5">
                            <TableCell class="py-4">
                                <div class="w-16 h-16 rounded bg-muted flex items-center justify-center overflow-hidden border border-border/50">
                                    <img v-if="info.gambar_poster" :src="`/storage/${info.gambar_poster}`" class="object-cover w-full h-full" alt="Poster" />
                                    <ImageIcon v-else class="w-6 h-6 text-muted-foreground/50" />
                                </div>
                            </TableCell>
                            <TableCell>
                                <p class="font-bold text-sm text-foreground">{{ info.judul }}</p>
                            </TableCell>
                            <TableCell>
                                <Badge variant="outline" class="rounded-lg font-bold text-[10px] uppercase tracking-wider">
                                    {{ info.kategori }}
                                </Badge>
                            </TableCell>
                            <TableCell class="text-sm text-muted-foreground">
                                {{ new Date(info.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="infohubs.data.length === 0">
                            <TableCell colspan="4" class="py-12 text-center text-muted-foreground">
                                Belum ada data InfoHub.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>

                <div v-if="infohubs.links && infohubs.links.length > 3" class="px-8 py-4 border-t border-border/40 bg-muted/5">
                    <Pagination :links="infohubs.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
