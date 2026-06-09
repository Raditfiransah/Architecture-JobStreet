<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { ImagePlus, Pencil } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    currentBanner: {
        type: String,
        default: null,
    },
    uploadUrl: {
        type: String,
        required: true,
    },
});

const fileInput = ref(null);
const previewUrl = ref(props.currentBanner);

const form = useForm({ banner: null });

const triggerSelect = () => fileInput.value.click();

const handleFileChange = (e) => {
    const file = e.target.files?.[0];
    if (!file) return;
    form.banner = file;
    previewUrl.value = URL.createObjectURL(file);
};

const upload = () => {
    form.post(props.uploadUrl, {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <div class="space-y-3">
        <!-- Preview area -->
        <div
            class="relative w-full h-40 rounded-xl overflow-hidden bg-gradient-to-r from-gray-200 to-gray-300 cursor-pointer group border border-gray-200"
            @click="triggerSelect"
        >
            <img
                v-if="previewUrl"
                :src="previewUrl"
                class="w-full h-full object-cover"
                alt="Background profil"
            />

            <!-- overlay on hover -->
            <div class="absolute inset-0 bg-black/40 flex flex-col items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                <ImagePlus class="w-8 h-8 text-white" />
                <span class="text-sm font-medium text-white">Klik untuk ganti background</span>
            </div>

            <!-- edit button always visible -->
            <button
                type="button"
                class="absolute top-3 right-3 bg-white/90 hover:bg-white rounded-lg px-3 py-1.5 flex items-center gap-1.5 text-xs font-semibold text-gray-700 shadow transition"
                @click.stop="triggerSelect"
            >
                <Pencil class="w-3.5 h-3.5" />
                Ganti Background
            </button>
        </div>

        <input
            ref="fileInput"
            type="file"
            class="hidden"
            accept="image/jpeg,image/jpg,image/png,image/webp"
            @change="handleFileChange"
        />

        <div class="flex items-center justify-between">
            <p class="text-xs text-gray-500">Format: JPG, PNG, WEBP. Ukuran maks 5MB. Rasio ideal 3:1 (contoh: 1200×400px).</p>

            <button
                v-if="form.banner"
                type="button"
                :disabled="form.processing"
                class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 hover:bg-gray-700 disabled:opacity-50 text-white text-sm font-semibold rounded-lg transition"
                @click="upload"
            >
                <span v-if="form.processing">Mengunggah…</span>
                <span v-else>Simpan Background</span>
            </button>
        </div>

        <InputError :message="form.errors.banner" />
    </div>
</template>
