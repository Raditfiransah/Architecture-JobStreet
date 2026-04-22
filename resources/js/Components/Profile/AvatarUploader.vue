<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { Camera, User } from 'lucide-vue-next';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    currentAvatar: {
        type: String,
        default: null
    },
    uploadUrl: {
        type: String,
        required: true
    },
    label: {
        type: String,
        default: 'Foto Profil'
    }
});

const fileInput = ref(null);
const previewUrl = ref(props.currentAvatar);

const form = useForm({
    avatar: null,
});

const triggerSelect = () => {
    fileInput.value.click();
};

const handleFileChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    form.avatar = file;
    previewUrl.value = URL.createObjectURL(file);
};

const upload = () => {
    form.post(props.uploadUrl, {
        preserveScroll: true,
        onSuccess: () => {
            // Success notification is handled by generic layout or flash message
            form.reset();
        },
    });
};
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center gap-6">
        <!-- Avatar Preview -->
        <div class="relative group cursor-pointer" @click="triggerSelect">
            <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden bg-gray-100 border-4 border-white shadow-lg flex items-center justify-center relative">
                <img v-if="previewUrl" :src="previewUrl" class="w-full h-full object-cover" alt="Avatar" />
                <div v-else class="text-gray-400 p-4">
                    <User class="w-full h-full" />
                </div>
                
                <!-- Overlay -->
                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <Camera class="w-8 h-8 text-white" />
                </div>
            </div>
            
            <button type="button" class="absolute bottom-0 right-0 bg-blue-600 rounded-full p-2 text-white shadow hover:bg-blue-700 transition-colors">
                <Camera class="w-4 h-4" />
            </button>
        </div>

        <!-- Info and Actions -->
        <div class="flex flex-col items-center sm:items-start gap-2">
            <h3 class="text-lg font-medium text-gray-900">{{ label }}</h3>
            <p class="text-sm text-gray-500">
                Format: JPG, PNG, atau WEBP. Maks 2MB.
            </p>
            
            <input 
                type="file" 
                ref="fileInput" 
                class="hidden" 
                accept="image/jpeg, image/png, image/webp" 
                @change="handleFileChange"
            />
            
            <div class="flex items-center gap-3 mt-2">
                <PrimaryButton 
                    type="button" 
                    @click="upload" 
                    :disabled="!form.avatar || form.processing"
                >
                    <span v-if="form.processing">Mengunggah...</span>
                    <span v-else>Simpan Foto</span>
                </PrimaryButton>
            </div>
            
            <InputError :message="form.errors.avatar" class="mt-2" />
        </div>
    </div>
</template>
