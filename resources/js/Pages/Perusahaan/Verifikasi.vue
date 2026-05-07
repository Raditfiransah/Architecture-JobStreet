<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from '@/Components/UI/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/UI/ui/card';
import { Label } from '@/Components/UI/ui/label';
import { Input } from '@/Components/UI/ui/input';
import { Textarea } from '@/Components/UI/ui/textarea';
import { AlertCircle, CheckCircle2, Upload, Loader2, Save } from 'lucide-vue-next';
import { Alert, AlertTitle, AlertDescription } from '@/Components/UI/ui/alert';

const page = usePage();
const user = computed(() => page.props.auth.user);
const profile = computed(() => user.value.profile || {});
const isVerified = computed(() => profile.value.verification_status === 'verified');
const verificationStatus = computed(() => profile.value.verification_status || 'unverified');

const isLoading = ref(false);

const form = ref({
    phone: user.value.phone || profile.value.phone || '',
    address: profile.value.location || '',
    nib_document_url: null,
    npwp_document_url: null,
    akta_document_url: null,
    siup_document_url: null,
    pic_document_url: null
});

const fileNames = ref({
    nib_document_url: '',
    npwp_document_url: '',
    akta_document_url: '',
    siup_document_url: '',
    pic_document_url: ''
});

onMounted(() => {
    if (!isVerified.value) {
        const draft = localStorage.getItem('perusahaan_verifikasi_draft');
        if (draft) {
            try {
                const parsed = JSON.parse(draft);
                form.value.phone = parsed.phone || form.value.phone;
                form.value.address = parsed.address || form.value.address;
            } catch (e) {
                console.error("Failed to parse draft", e);
            }
        }
    }
});

watch([() => form.value.phone, () => form.value.address], ([newPhone, newAddress]) => {
    if (!isVerified.value) {
        localStorage.setItem('perusahaan_verifikasi_draft', JSON.stringify({ phone: newPhone, address: newAddress }));
    }
});

const handleFileUpload = (e, field) => {
    const file = e.target.files[0];
    if (file) {
        form.value[field] = file;
        fileNames.value[field] = file.name;
    }
};

const isFormValid = computed(() => {
    return form.value.phone.trim() !== '' && 
           form.value.address.trim() !== '' &&
           form.value.nib_document_url !== null &&
           form.value.npwp_document_url !== null &&
           form.value.akta_document_url !== null &&
           form.value.siup_document_url !== null;
});

const submit = () => {
    if (!isFormValid.value) {
        alert("Mohon lengkapi semua field dan dokumen yang wajib diisi!");
        return;
    }
    
    if (confirm("Pastikan data sudah benar, pengajuan tidak bisa diubah setelah dikirim")) {
        isLoading.value = true;
        setTimeout(() => {
            alert("Pengajuan verifikasi perusahaan berhasil dikirim!");
            localStorage.removeItem('perusahaan_verifikasi_draft');
            isLoading.value = false;
        }, 1500);
    }
};
</script>

<template>
    <Head title="Verifikasi Perusahaan" />

    <ProfileLayout>
        <div class="space-y-6 max-w-4xl">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-foreground">Verifikasi Perusahaan</h2>
                <p class="text-muted-foreground mt-2">
                    Verifikasi legalitas perusahaan Anda untuk dapat merekrut arsitek profesional di platform kami.
                </p>
            </div>

            <Alert v-if="isVerified" class="bg-emerald-50 text-emerald-600 border-emerald-200">
                <CheckCircle2 class="h-4 w-4 stroke-emerald-600" />
                <AlertTitle>Terverifikasi</AlertTitle>
                <AlertDescription>
                    Perusahaan Anda telah berhasil diverifikasi oleh admin. Data legalitas di bawah ini sudah tidak dapat diubah.
                </AlertDescription>
            </Alert>

            <Alert v-else-if="verificationStatus === 'pending'" class="bg-blue-50 text-blue-600 border-blue-200">
                <AlertCircle class="h-4 w-4 stroke-blue-600" />
                <AlertTitle>Menunggu Verifikasi</AlertTitle>
                <AlertDescription>
                    Pengajuan verifikasi perusahaan sedang ditinjau oleh admin. Harap menunggu maksimal 2x24 jam.
                </AlertDescription>
            </Alert>

            <Card>
                <CardHeader>
                    <CardTitle>Data & Dokumen Legalitas</CardTitle>
                    <CardDescription>Upload dokumen asli (berwarna) yang masih berlaku.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-6">
                    <!-- Kontak & Alamat -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <Label for="phone">Nomor Kontak Resmi <span class="text-rose-500">*</span></Label>
                            <Input 
                                id="phone" 
                                v-model="form.phone" 
                                placeholder="Contoh: (021) 1234567" 
                                :disabled="isVerified || verificationStatus === 'pending'"
                            />
                        </div>
                        <div class="space-y-3">
                            <Label for="address">Alamat Kantor <span class="text-rose-500">*</span></Label>
                            <Textarea 
                                id="address" 
                                v-model="form.address" 
                                placeholder="Alamat lengkap perusahaan" 
                                :disabled="isVerified || verificationStatus === 'pending'"
                                class="resize-none"
                                rows="2"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- NIB -->
                        <div class="space-y-3">
                            <Label>NIB (Nomor Induk Berusaha) <span class="text-rose-500">*</span></Label>
                            <div class="border-2 border-dashed rounded-xl p-6 text-center hover:bg-muted/50 transition-colors" :class="{'opacity-50 pointer-events-none': isVerified || verificationStatus === 'pending'}">
                                <input type="file" id="nib" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="(e) => handleFileUpload(e, 'nib_document_url')" :disabled="isVerified || verificationStatus === 'pending'">
                                <label for="nib" class="cursor-pointer flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                        <Upload class="w-6 h-6" />
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium">Upload Dokumen NIB</p>
                                        <p class="text-xs text-muted-foreground">PDF, JPG, PNG (Max. 5MB)</p>
                                    </div>
                                </label>
                                <p v-if="fileNames.nib_document_url" class="mt-4 text-sm font-bold text-primary">{{ fileNames.nib_document_url }}</p>
                            </div>
                        </div>

                        <!-- NPWP -->
                        <div class="space-y-3">
                            <Label>NPWP Perusahaan <span class="text-rose-500">*</span></Label>
                            <div class="border-2 border-dashed rounded-xl p-6 text-center hover:bg-muted/50 transition-colors" :class="{'opacity-50 pointer-events-none': isVerified || verificationStatus === 'pending'}">
                                <input type="file" id="npwp" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="(e) => handleFileUpload(e, 'npwp_document_url')" :disabled="isVerified || verificationStatus === 'pending'">
                                <label for="npwp" class="cursor-pointer flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                        <Upload class="w-6 h-6" />
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium">Upload NPWP</p>
                                        <p class="text-xs text-muted-foreground">PDF, JPG, PNG (Max. 5MB)</p>
                                    </div>
                                </label>
                                <p v-if="fileNames.npwp_document_url" class="mt-4 text-sm font-bold text-primary">{{ fileNames.npwp_document_url }}</p>
                            </div>
                        </div>

                        <!-- Akta Pendirian -->
                        <div class="space-y-3">
                            <Label>Akta Pendirian Perusahaan <span class="text-rose-500">*</span></Label>
                            <div class="border-2 border-dashed rounded-xl p-6 text-center hover:bg-muted/50 transition-colors" :class="{'opacity-50 pointer-events-none': isVerified || verificationStatus === 'pending'}">
                                <input type="file" id="akta" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="(e) => handleFileUpload(e, 'akta_document_url')" :disabled="isVerified || verificationStatus === 'pending'">
                                <label for="akta" class="cursor-pointer flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                        <Upload class="w-6 h-6" />
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium">Upload Akta Pendirian</p>
                                        <p class="text-xs text-muted-foreground">PDF, JPG, PNG (Max. 5MB)</p>
                                    </div>
                                </label>
                                <p v-if="fileNames.akta_document_url" class="mt-4 text-sm font-bold text-primary">{{ fileNames.akta_document_url }}</p>
                            </div>
                        </div>

                        <!-- SIUP -->
                        <div class="space-y-3">
                            <Label>SIUP / Izin Usaha <span class="text-rose-500">*</span></Label>
                            <div class="border-2 border-dashed rounded-xl p-6 text-center hover:bg-muted/50 transition-colors" :class="{'opacity-50 pointer-events-none': isVerified || verificationStatus === 'pending'}">
                                <input type="file" id="siup" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="(e) => handleFileUpload(e, 'siup_document_url')" :disabled="isVerified || verificationStatus === 'pending'">
                                <label for="siup" class="cursor-pointer flex flex-col items-center gap-3">
                                    <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                        <Upload class="w-6 h-6" />
                                    </div>
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium">Upload SIUP</p>
                                        <p class="text-xs text-muted-foreground">PDF, JPG, PNG (Max. 5MB)</p>
                                    </div>
                                </label>
                                <p v-if="fileNames.siup_document_url" class="mt-4 text-sm font-bold text-primary">{{ fileNames.siup_document_url }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Surat Penunjukan PIC -->
                    <div class="space-y-3">
                        <Label>Surat Penunjukan PIC / HRD <span class="text-muted-foreground font-normal">(Opsional)</span></Label>
                        <div class="border-2 border-dashed rounded-xl p-6 text-center hover:bg-muted/50 transition-colors max-w-md mx-auto" :class="{'opacity-50 pointer-events-none': isVerified || verificationStatus === 'pending'}">
                            <input type="file" id="pic" class="hidden" accept=".pdf,.jpg,.jpeg,.png" @change="(e) => handleFileUpload(e, 'pic_document_url')" :disabled="isVerified || verificationStatus === 'pending'">
                            <label for="pic" class="cursor-pointer flex flex-col items-center gap-3">
                                <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                    <Upload class="w-6 h-6" />
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium">Upload Surat Penunjukan PIC</p>
                                    <p class="text-xs text-muted-foreground">PDF, JPG, PNG (Max. 5MB)</p>
                                </div>
                            </label>
                            <p v-if="fileNames.pic_document_url" class="mt-4 text-sm font-bold text-primary">{{ fileNames.pic_document_url }}</p>
                        </div>
                    </div>

                    <div class="pt-4 flex justify-end" v-if="!isVerified && verificationStatus !== 'pending'">
                        <Button @click="submit" :disabled="isLoading || !isFormValid" class="min-w-[150px]">
                            <Loader2 v-if="isLoading" class="w-4 h-4 mr-2 animate-spin" />
                            <Save v-else class="w-4 h-4 mr-2" />
                            Kirim Pengajuan
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </ProfileLayout>
</template>
