<script setup>
import { ref, watch } from 'vue';
import { X } from 'lucide-vue-next';

const props = defineProps({
    modelValue: {
        type: Array,
        default: () => []
    },
    placeholder: {
        type: String,
        default: 'Ketik lalu tekan Enter'
    }
});

const emit = defineEmits(['update:modelValue']);
const inputValue = ref('');
const tags = ref([...(props.modelValue || [])]);

watch(() => props.modelValue, (newVal) => {
    if (newVal !== tags.value) {
        tags.value = [...(newVal || [])];
    }
}, { deep: true });

const addTag = () => {
    const value = inputValue.value.trim();
    if (value && !tags.value.includes(value)) {
        tags.value.push(value);
        emit('update:modelValue', tags.value);
    }
    inputValue.value = '';
};

const removeTag = (index) => {
    tags.value.splice(index, 1);
    emit('update:modelValue', tags.value);
};

const handleKeyDown = (e) => {
    if (e.key === 'Enter') {
        e.preventDefault();
        addTag();
    } else if (e.key === 'Backspace' && inputValue.value === '' && tags.value.length > 0) {
        removeTag(tags.value.length - 1);
    }
};
</script>

<template>
    <div class="border-gray-300 focus-within:border-brand focus-within:ring-brand rounded-md shadow-sm border p-1.5 flex flex-wrap gap-1.5 min-h-[42px] bg-white transition-colors duration-200">
        <span 
            v-for="(tag, index) in tags" 
            :key="index"
            class="inline-flex items-center gap-1 px-2.5 py-1 rounded bg-brand/10 text-brand text-sm font-medium"
        >
            {{ tag }}
            <button 
                type="button" 
                @click="removeTag(index)" 
                class="hover:bg-brand/20 rounded-full p-0.5 transition-colors focus:outline-none"
            >
                <X class="w-3.5 h-3.5" />
            </button>
        </span>
        
        <input
            v-model="inputValue"
            @keydown="handleKeyDown"
            @blur="addTag"
            type="text"
            class="flex-1 outline-none border-none ring-0 focus:ring-0 min-w-[120px] p-1 text-sm bg-transparent"
            :placeholder="tags.length === 0 ? placeholder : ''"
        />
    </div>
</template>
