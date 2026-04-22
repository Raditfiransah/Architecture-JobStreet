<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { FileText, UploadCloud, FileCheck } from 'lucide-vue-next';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    type: {
        type: String,
        required: true, // identity, license
    },
    label: {
        type: String,
        required: true
    },
    description: {
        type: String,
        required: true
    },
    uploadUrl: {
        type: String,
        required: true
    },
    currentDocumentUrl: {
        type: String,
        default: null
    }
});

const fileInput = ref(null);
const fileName = ref('');

const form = useForm({
    type: props.type,
    document: null
});

const triggerSelect = () => {
    fileInput.value.click();
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    form.document = file;
    fileName.value = file.name;
};

const upload = () => {
    form.post(props.uploadUrl, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('document');
            fileName.value = '';
        }
    });
};

const hasUploaded = computed(() => !!props.currentDocumentUrl);
</script>

<template>
    <div class="border rounded-lg p-5 flex flex-col gap-4" :class="hasUploaded ? 'bg-green-50/50 border-green-200' : 'bg-white'">
        <div class="flex sm:items-center gap-4 flex-col sm:flex-row">
            <div class="p-3 rounded-full flex-shrink-0" :class="hasUploaded ? 'bg-green-100 text-green-600' : 'bg-gray-100 text-gray-500'">
                <FileCheck v-if="hasUploaded" class="w-6 h-6" />
                <FileText v-else class="w-6 h-6" />
            </div>
            
            <div class="flex-1">
                <h4 class="text-base font-semibold text-gray-900 flex items-center gap-2">
                    {{ label }}
                    <span v-if="hasUploaded" class="text-xs font-medium text-green-700 bg-green-100 px-2 py-0.5 rounded-full">
                        Dokumen Tersimpan
                    </span>
                </h4>
                <p class="text-sm text-gray-500 mt-1">{{ description }}</p>
                <p class="text-xs text-gray-400 mt-1">Format: PDF, JPG, PNG. Maks 5MB.</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3">
            <input 
                type="file" 
                ref="fileInput" 
                class="hidden" 
                accept=".pdf,image/jpeg,image/png" 
                @change="handleFileChange"
            />
            
            <button 
                type="button" 
                @click="triggerSelect"
                class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
            >
                <UploadCloud class="w-4 h-4" />
                Pilih File
            </button>
            
            <span v-if="fileName" class="text-sm text-gray-600 font-medium truncate max-w-xs">
                {{ fileName }}
            </span>
            
            <PrimaryButton 
                v-if="form.document"
                type="button" 
                @click="upload" 
                :disabled="form.processing"
                class="ml-auto"
            >
                <span v-if="form.processing">Mengunggah...</span>
                <span v-else>Unggah Dokumen</span>
            </PrimaryButton>
        </div>
        
        <InputError :message="form.errors.document" class="mt-1" />
        <InputError :message="form.errors.type" class="mt-1" />
    </div>
</template>
