<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from "@/Components/UI/ui/button";
import { Card, CardContent } from "@/Components/UI/ui/card";
import { Plus, ExternalLink, Calendar, Trash2, Edit2, GripVertical, Image as ImageIcon } from 'lucide-vue-next';
import { Badge } from "@/Components/UI/ui/badge";

const props = defineProps({
    portofolios: Array
});

const deletePortofolio = (id) => {
    if (confirm('Apakah Anda yakin ingin menghapus portofolio ini?')) {
        router.delete(route('arsitek.portofolio.destroy', id));
    }
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long'
    });
};
</script>

<template>
    <ProfileLayout>
        <Head title="Portofolio Saya" />

        <div class="space-y-8 animate-in fade-in duration-500">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-display font-bold text-slate-900 tracking-tight">Portofolio Saya</h1>
                    <p class="text-slate-500 mt-1 font-medium">Pamerkan karya terbaik Anda untuk menarik perhatian klien dan perusahaan.</p>
                </div>
                <Button :as="Link" :href="route('arsitek.portofolio.create')" class="rounded-xl px-6 py-6 h-auto font-bold gap-2 shadow-lg shadow-primary/20 hover:shadow-primary/30 transition-all active:scale-95">
                    <Plus class="w-5 h-5" />
                    Tambah Portofolio
                </Button>
            </div>

            <!-- Empty State -->
            <Card v-if="portofolios.length === 0" class="border-dashed border-2 bg-slate-50/50 rounded-3xl overflow-hidden min-h-[400px] flex flex-col items-center justify-center p-12 text-center">
                <div class="w-24 h-24 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-6">
                    <ImageIcon class="w-10 h-10 text-slate-300" />
                </div>
                <h3 class="text-xl font-display font-bold text-slate-800 mb-2">Belum ada portofolio</h3>
                <p class="text-slate-500 max-w-sm mb-8 font-medium">Mulai unggah proyek arsitektur Anda untuk memperlengkap profil profesional Anda.</p>
                <Button :as="Link" :href="route('arsitek.portofolio.create')" variant="outline" class="rounded-xl border-dashed px-8">
                    Buat Proyek Pertama
                </Button>
            </Card>

            <!-- Portfolio Grid -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <div v-for="item in portofolios" :key="item.id" class="group relative bg-white rounded-3xl border border-slate-200 overflow-hidden hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500 flex flex-col">
                    <!-- Thumbnail Container -->
                    <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                        <img 
                            v-if="item.thumbnail" 
                            :src="'/storage/' + item.thumbnail" 
                            :alt="item.title"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" 
                        />
                        <div v-else class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-2">
                            <ImageIcon class="w-12 h-12 opacity-20" />
                            <span class="text-xs font-bold uppercase tracking-widest opacity-40">No Image Preview</span>
                        </div>
                        
                        <!-- Overlay Actions -->
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-3">
                            <Link :href="route('arsitek.portofolio.edit', item.id)" class="p-3 bg-white/10 backdrop-blur-md rounded-xl text-white hover:bg-white hover:text-slate-900 transition-all active:scale-90" title="Edit">
                                <Edit2 class="w-5 h-5" />
                            </Link>
                            <button @click="deletePortofolio(item.id)" class="p-3 bg-white/10 backdrop-blur-md rounded-xl text-white hover:bg-red-500 transition-all active:scale-90" title="Hapus">
                                <Trash2 class="w-5 h-5" />
                            </button>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-4 mb-2">
                            <h3 class="text-lg font-display font-bold text-slate-800 leading-tight group-hover:text-primary transition-colors">{{ item.title }}</h3>
                            <a v-if="item.link" :href="item.link" target="_blank" class="text-slate-400 hover:text-primary transition-colors">
                                <ExternalLink class="w-4 h-4" />
                            </a>
                        </div>
                        
                        <p class="text-sm text-slate-500 line-clamp-2 mb-4 font-medium flex-1">
                            {{ item.description }}
                        </p>

                        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                            <div class="flex items-center text-slate-400 gap-2 text-xs font-bold uppercase tracking-wider">
                                <Calendar class="w-3.5 h-3.5" />
                                {{ formatDate(item.project_date) || 'No Date' }}
                            </div>
                            <div v-if="item.images && item.images.length > 0" class="flex items-center gap-1 text-slate-400">
                                <ImageIcon class="w-3.5 h-3.5" />
                                <span class="text-xs font-bold">{{ item.images.length }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </ProfileLayout>
</template>

<style scoped>
.font-display {
    font-family: 'Outfit', sans-serif;
}
</style>
