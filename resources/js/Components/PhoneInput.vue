<script setup>
/**
 * PhoneInput — dropdown kode negara + input nomor lokal
 *
 * Emits:
 *   update:modelValue  → string E.164 lengkap, misal "+6281234567890"
 *
 * Props:
 *   modelValue  → string E.164 atau nomor lokal yang sudah tersimpan
 *   disabled    → boolean
 *   error       → string pesan error dari server
 */
import { ref, computed, watch, onMounted } from 'vue';
import { ChevronDown, Phone } from 'lucide-vue-next';

const props = defineProps({
    modelValue: { type: String, default: '' },
    disabled:   { type: Boolean, default: false },
    error:      { type: String, default: null },
});

const emit = defineEmits(['update:modelValue']);

// ─── Daftar negara (fokus Asia Tenggara + umum) ───────────────────────────────
const countries = [
    { code: 'ID', name: 'Indonesia',     dial: '+62',  flag: '🇮🇩', format: '8xx-xxxx-xxxx',   maxLen: 13 },
    { code: 'MY', name: 'Malaysia',      dial: '+60',  flag: '🇲🇾', format: '1x-xxxx-xxxx',    maxLen: 11 },
    { code: 'SG', name: 'Singapura',     dial: '+65',  flag: '🇸🇬', format: 'xxxx-xxxx',       maxLen: 8  },
    { code: 'TH', name: 'Thailand',      dial: '+66',  flag: '🇹🇭', format: 'xx-xxxx-xxxx',    maxLen: 10 },
    { code: 'PH', name: 'Filipina',      dial: '+63',  flag: '🇵🇭', format: '9xx-xxx-xxxx',    maxLen: 10 },
    { code: 'VN', name: 'Vietnam',       dial: '+84',  flag: '🇻🇳', format: 'xx-xxxx-xxxx',    maxLen: 10 },
    { code: 'AU', name: 'Australia',     dial: '+61',  flag: '🇦🇺', format: 'xxx-xxx-xxx',     maxLen: 9  },
    { code: 'JP', name: 'Jepang',        dial: '+81',  flag: '🇯🇵', format: 'xx-xxxx-xxxx',    maxLen: 11 },
    { code: 'KR', name: 'Korea Selatan', dial: '+82',  flag: '🇰🇷', format: 'xx-xxxx-xxxx',    maxLen: 11 },
    { code: 'CN', name: 'Tiongkok',      dial: '+86',  flag: '🇨🇳', format: 'xxx-xxxx-xxxx',   maxLen: 11 },
    { code: 'IN', name: 'India',         dial: '+91',  flag: '🇮🇳', format: 'xxxxx-xxxxx',     maxLen: 10 },
    { code: 'US', name: 'Amerika Serikat', dial: '+1', flag: '🇺🇸', format: '(xxx) xxx-xxxx',  maxLen: 10 },
    { code: 'GB', name: 'Inggris',       dial: '+44',  flag: '🇬🇧', format: 'xxxx-xxxxxx',     maxLen: 10 },
    { code: 'NL', name: 'Belanda',       dial: '+31',  flag: '🇳🇱', format: 'x-xxxx-xxxx',     maxLen: 9  },
    { code: 'SA', name: 'Arab Saudi',    dial: '+966', flag: '🇸🇦', format: 'xx-xxx-xxxx',     maxLen: 9  },
    { code: 'AE', name: 'Uni Emirat Arab', dial: '+971', flag: '🇦🇪', format: 'xx-xxx-xxxx',   maxLen: 9  },
];

// ─── State ────────────────────────────────────────────────────────────────────
const selectedCountry = ref(countries[0]); // default Indonesia
const localNumber     = ref('');
const dropdownOpen    = ref(false);
const searchQuery     = ref('');

// ─── Parse modelValue saat mount / berubah dari luar ─────────────────────────
const parseIncoming = (val) => {
    if (!val) return;
    // Cari country yang cocok berdasarkan dial code
    const matched = countries.find(c => val.startsWith(c.dial));
    if (matched) {
        selectedCountry.value = matched;
        localNumber.value = val.slice(matched.dial.length).replace(/\D/g, '');
    } else {
        // Simpan apa adanya di localNumber
        localNumber.value = val.replace(/\D/g, '');
    }
};

onMounted(() => parseIncoming(props.modelValue));
watch(() => props.modelValue, (v) => {
    // Hanya re-parse jika berbeda dari yang kita emit (hindari loop)
    const current = selectedCountry.value.dial + localNumber.value;
    if (v !== current) parseIncoming(v);
});

// ─── Emit E.164 setiap kali berubah ──────────────────────────────────────────
const emitValue = () => {
    const digits = localNumber.value.replace(/\D/g, '');
    emit('update:modelValue', digits ? selectedCountry.value.dial + digits : '');
};

watch(localNumber, emitValue);
watch(selectedCountry, emitValue);

// ─── Hanya izinkan digit ─────────────────────────────────────────────────────
const onInput = (e) => {
    localNumber.value = e.target.value.replace(/\D/g, '').slice(0, selectedCountry.value.maxLen);
};

// ─── Dropdown filter ─────────────────────────────────────────────────────────
const filteredCountries = computed(() => {
    const q = searchQuery.value.toLowerCase();
    if (!q) return countries;
    return countries.filter(c =>
        c.name.toLowerCase().includes(q) ||
        c.dial.includes(q) ||
        c.code.toLowerCase().includes(q)
    );
});

const selectCountry = (c) => {
    selectedCountry.value = c;
    dropdownOpen.value    = false;
    searchQuery.value     = '';
};

// Tutup dropdown saat klik di luar
const closeDropdown = (e) => {
    if (!e.target.closest('.phone-input-wrapper')) {
        dropdownOpen.value = false;
    }
};
if (typeof window !== 'undefined') {
    window.addEventListener('click', closeDropdown);
}

// ─── Placeholder dinamis ──────────────────────────────────────────────────────
const placeholder = computed(() => selectedCountry.value.format);
</script>

<template>
    <div class="phone-input-wrapper relative">
        <div
            class="flex rounded-xl border bg-background overflow-hidden transition-colors"
            :class="[
                error ? 'border-rose-400 ring-1 ring-rose-300' : 'border-border focus-within:border-primary focus-within:ring-1 focus-within:ring-primary/30',
                disabled ? 'opacity-60 pointer-events-none bg-muted' : ''
            ]"
        >
            <!-- Dropdown trigger -->
            <button
                type="button"
                @click.stop="dropdownOpen = !dropdownOpen"
                :disabled="disabled"
                class="flex items-center gap-1.5 px-3 py-2.5 border-r border-border bg-muted/40 hover:bg-muted/70 transition-colors shrink-0 min-w-[90px]"
            >
                <span class="text-base leading-none">{{ selectedCountry.flag }}</span>
                <span class="text-xs font-bold text-foreground">{{ selectedCountry.dial }}</span>
                <ChevronDown class="w-3 h-3 text-muted-foreground" :class="{ 'rotate-180': dropdownOpen }" />
            </button>

            <!-- Number input -->
            <div class="flex items-center flex-1 px-3 gap-2">
                <Phone class="w-3.5 h-3.5 text-muted-foreground shrink-0" />
                <input
                    type="tel"
                    :value="localNumber"
                    @input="onInput"
                    :placeholder="placeholder"
                    :disabled="disabled"
                    class="flex-1 bg-transparent text-sm py-2.5 outline-none placeholder:text-muted-foreground/50 min-w-0"
                />
            </div>
        </div>

        <!-- Error message -->
        <p v-if="error" class="mt-1.5 text-xs text-rose-500 font-medium">{{ error }}</p>

        <!-- Preview E.164 -->
        <p v-if="localNumber && !error" class="mt-1 text-[11px] text-muted-foreground">
            Tersimpan sebagai: <span class="font-mono font-semibold">{{ selectedCountry.dial + localNumber }}</span>
        </p>

        <!-- Dropdown -->
        <div
            v-if="dropdownOpen"
            class="absolute z-50 top-full left-0 mt-1 w-72 bg-popover border border-border rounded-xl shadow-lg overflow-hidden"
        >
            <!-- Search -->
            <div class="p-2 border-b border-border">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Cari negara atau kode..."
                    class="w-full px-3 py-2 text-sm bg-muted/40 rounded-lg outline-none placeholder:text-muted-foreground/50"
                    @click.stop
                />
            </div>

            <!-- List -->
            <ul class="max-h-56 overflow-y-auto">
                <li
                    v-for="c in filteredCountries"
                    :key="c.code"
                    @click.stop="selectCountry(c)"
                    class="flex items-center gap-3 px-3 py-2.5 cursor-pointer hover:bg-muted/50 transition-colors"
                    :class="{ 'bg-primary/5': c.code === selectedCountry.code }"
                >
                    <span class="text-base">{{ c.flag }}</span>
                    <span class="text-sm flex-1 text-foreground">{{ c.name }}</span>
                    <span class="text-xs font-mono font-bold text-muted-foreground">{{ c.dial }}</span>
                </li>
                <li v-if="filteredCountries.length === 0" class="px-3 py-4 text-sm text-center text-muted-foreground">
                    Negara tidak ditemukan
                </li>
            </ul>
        </div>
    </div>
</template>
