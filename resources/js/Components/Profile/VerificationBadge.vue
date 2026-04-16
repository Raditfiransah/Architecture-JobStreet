<script setup>
import { computed } from 'vue';
import { 
    BadgeCheck, 
    XCircle, 
    Clock,
    AlertTriangle 
} from 'lucide-vue-next';

const props = defineProps({
    status: {
        type: String,
        default: 'unverified' // unverified, pending, verified, rejected
    },
    note: {
        type: String,
        default: null
    }
});

const config = computed(() => {
    switch (props.status) {
        case 'verified':
            return {
                text: 'Terverifikasi',
                colorClass: 'bg-green-100 text-green-800 border-green-200',
                icon: BadgeCheck,
                iconClass: 'text-green-600'
            };
        case 'pending':
            return {
                text: 'Menunggu Verifikasi',
                colorClass: 'bg-yellow-100 text-yellow-800 border-yellow-200',
                icon: Clock,
                iconClass: 'text-yellow-600'
            };
        case 'rejected':
            return {
                text: 'Ditolak',
                colorClass: 'bg-red-100 text-red-800 border-red-200',
                icon: XCircle,
                iconClass: 'text-red-600'
            };
        default:
            return {
                text: 'Belum Diverifikasi',
                colorClass: 'bg-gray-100 text-gray-800 border-gray-200',
                icon: AlertTriangle,
                iconClass: 'text-gray-500'
            };
    }
});
</script>

<template>
    <div class="flex flex-col gap-2">
        <span 
            class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm font-medium border"
            :class="config.colorClass"
        >
            <component :is="config.icon" class="w-5 h-5 flex-shrink-0" :class="config.iconClass" />
            {{ config.text }}
        </span>
        
        <p v-if="status === 'rejected' && note" class="text-sm text-red-600 mt-1">
            <strong>Alasan penolakan:</strong> {{ note }}
        </p>
    </div>
</template>
