<script setup>
import { computed } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Button } from '@/Components/UI/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/UI/ui/table';
import { Badge } from '@/Components/UI/ui/badge';
import { Alert, AlertDescription } from '@/Components/UI/ui/alert';
import Pagination from '@/Components/Pagination.vue';
import { CheckCircle2, ExternalLink, Pencil, PlusCircle, Trash2, Image as ImageIcon } from 'lucide-vue-next';

defineProps({
    infohubs: Object,
});

const page = usePage();
const successMessage = computed(() => page.props.flash?.success || page.props.flash?.status);
const deleteForm = useForm({});

const destroyInfo = (info) => {
    if (!window.confirm(`Hapus postingan "${info.judul}"?`)) {
        return;
    }

    deleteForm.delete(route('admin.info.destroy', info.id), {
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="Manajemen InfoHub" />

    <AuthenticatedLayout>
        <div class="space-y-6">
            <Alert v-if="successMessage" class="border-emerald-200 bg-emerald-50 text-emerald-900">
                <CheckCircle2 class="h-4 w-4 text-emerald-600" />
                <AlertDescription class="font-medium">
                    {{ successMessage }}
                </AlertDescription>
            </Alert>

            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-foreground">Kelola Informasi</h1>
                    <p class="text-muted-foreground mt-1">Kelola papan pengumuman (Event, Sayembara, Magang).</p>
                </div>
                <Link :href="route('admin.info.create')">
                    <Button class="gap-2">
                        <PlusCircle class="w-4 h-4" />
                        Buat Postingan Mading
                    </Button>
                </Link>
            </div>

            <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
                <!-- Desktop Table -->
                <div class="hidden sm:block">
                  <Table>
                    <TableHeader class="bg-muted/30">
                        <TableRow>
                            <TableHead class="font-bold text-xs uppercase tracking-wider py-4 w-[100px]">Poster</TableHead>
                            <TableHead class="font-bold text-xs uppercase tracking-wider">Judul</TableHead>
                            <TableHead class="font-bold text-xs uppercase tracking-wider">Kategori</TableHead>
                            <TableHead class="font-bold text-xs uppercase tracking-wider">Tgl Dibuat</TableHead>
                            <TableHead class="font-bold text-xs uppercase tracking-wider text-right">Aksi</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="info in infohubs.data" :key="info.id" class="group hover:bg-muted/5">
                            <TableCell class="py-4">
                                <div class="w-16 h-16 rounded bg-muted flex items-center justify-center overflow-hidden border border-border/50">
                                    <img v-if="info.image_url" :src="info.image_url" class="object-cover w-full h-full" alt="Poster" />
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
                            <TableCell>
                                <div class="flex justify-end gap-2">
                                    <Button asChild variant="ghost" size="icon-sm" class="rounded-lg">
                                        <Link :href="route('info.show', info.id)">
                                            <ExternalLink class="w-4 h-4" />
                                        </Link>
                                    </Button>
                                    <Button asChild variant="outline" size="icon-sm" class="rounded-lg">
                                        <Link :href="route('admin.info.edit', info.id)">
                                            <Pencil class="w-4 h-4" />
                                        </Link>
                                    </Button>
                                    <Button type="button" variant="destructive" size="icon-sm" class="rounded-lg" :disabled="deleteForm.processing" @click="destroyInfo(info)">
                                        <Trash2 class="w-4 h-4" />
                                    </Button>
                                </div>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="infohubs.data.length === 0">
                            <TableCell colspan="5" class="py-12 text-center text-muted-foreground">
                                Belum ada data InfoHub.
                            </TableCell>
                        </TableRow>
                    </TableBody>
                  </Table>
                </div>

                <!-- Mobile Card List -->
                <div class="sm:hidden divide-y divide-border/40">
                  <div v-if="infohubs.data.length === 0" class="py-12 text-center text-muted-foreground text-sm">
                    Belum ada data InfoHub.
                  </div>
                  <div v-for="info in infohubs.data" :key="info.id" class="p-4 flex items-start gap-3 hover:bg-muted/5 transition-colors">
                    <div class="w-14 h-14 rounded-lg bg-muted flex items-center justify-center overflow-hidden border border-border/50 shrink-0">
                      <img v-if="info.image_url" :src="info.image_url" class="object-cover w-full h-full" alt="Poster" />
                      <ImageIcon v-else class="w-5 h-5 text-muted-foreground/50" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="font-bold text-sm text-foreground truncate">{{ info.judul }}</p>
                      <div class="flex items-center gap-2 mt-1">
                        <Badge variant="outline" class="rounded-lg font-bold text-[10px] uppercase tracking-wider">{{ info.kategori }}</Badge>
                        <span class="text-xs text-muted-foreground">{{ new Date(info.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) }}</span>
                      </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0">
                      <Button asChild variant="ghost" size="icon" class="rounded-lg h-8 w-8">
                        <Link :href="route('admin.info.edit', info.id)"><Pencil class="w-3.5 h-3.5" /></Link>
                      </Button>
                      <Button type="button" variant="ghost" size="icon" class="rounded-lg h-8 w-8 text-destructive hover:bg-destructive/10" :disabled="deleteForm.processing" @click="destroyInfo(info)">
                        <Trash2 class="w-3.5 h-3.5" />
                      </Button>
                    </div>
                  </div>
                </div>

                <div v-if="infohubs.links && infohubs.links.length > 3" class="px-4 sm:px-8 py-4 border-t border-border/40 bg-muted/5">
                    <Pagination :links="infohubs.links" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
