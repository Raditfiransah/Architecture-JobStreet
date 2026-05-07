<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, usePage, router } from '@inertiajs/vue3';
import ProfileLayout from '@/Layouts/ProfileLayout.vue';
import { Button } from '@/Components/UI/ui/button';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/Components/UI/ui/card';
import { Label } from '@/Components/UI/ui/label';
import { Input } from '@/Components/UI/ui/input';
import { AlertCircle, CheckCircle2, Upload, Loader2, Save } from 'lucide-vue-next';
import { Alert, AlertTitle, AlertDescription } from '@/Components/UI/ui/alert';

const page = usePage();
const user = computed(() => page.props.auth.user);
const profile = computed(() => user.value.profile || {});
const isVerified = computed(() => profile.value.verification_status === 'verified');
const verificationStatus = computed(() => profile.value.verification_status || 'unverified');
const verificationNote = computed(() => profile.value.verification_note || null);

const isLoading = ref(false);

const form = ref({
    phone: user.value.phone || '',
    identity_document_url: null,
    license_document_url: null
});

const fileNames = ref({
    identity_document_url: '',
    license_document_url: ''
});

onMounted(() => {
    if (!isVerified.value) {
        const draft = localStorage.getItem('arsitek_verifikasi_draft');
        if (draft) {
            try {
                const parsed = JSON.parse(draft);
                form.value.phone = parsed.phone || form.value.phone;
            } catch (e) {
                console.error("Failed to parse draft", e);
            }
        }
    }
});

watch(() => form.value.phone, (newVal) => {
    if (!isVerified.value) {
        localStorage.setItem('arsitek_verifikasi_draft', JSON.stringify({ phone: newVal }));
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
    const hasIdentity = form.value.identity_document_url !== null || profile.value.identity_document_url;
    const hasLicense = form.value.license_document_url !== null || profile.value.license_document_url;
    return form.value.phone.trim() !== '' && hasIdentity && hasLicense;
});

const submit = () => {
    if (!isFormValid.value) {
        alert("Mohon lengkapi semua field yang wajib diisi!");
        return;
    }
    
    if (confirm("Pastikan data sudah benar, pengajuan tidak bisa diubah setelah dikirim")) {
        isLoading.value = true;
        
        const formData = new FormData();
        formData.append('phone', form.value.phone);
        if (form.value.identity_document_url) {
            formData.append('identity_document', form.value.identity_document_url);
        }
        if (form.value.license_document_url) {
            formData.append('license_document', form.value.license_document_url);
        }

        router.post(route('arsitek.verifikasi.submit'), formData, {
            onSuccess: () => {
                alert("Pengajuan verifikasi berhasil dikirim!");
                localStorage.removeItem('arsitek_verifikasi_draft');
                isLoading.value = false;
            },
            onError: (err) => {
                console.error(err);
                alert("Terjadi kesalahan saat mengirim pengajuan!");
                isLoading.value = false;
            }
        });
    }
};
</script>

<template>
    <Head title="Verifikasi Arsitek" />

    <ProfileLayout>
        <div class="space-y-6 max-w-4xl">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-foreground">Verifikasi Arsitek</h2>
                <p class="text-muted-foreground mt-2">
                    Verifikasi profil profesional Anda untuk mendapatkan badge terverifikasi dan lebih banyak kepercayaan dari klien.
                </p>
            </div>

            <Alert v-if="isVerified" class="bg-emerald-50 text-emerald-600 border-emerald-200">
                <CheckCircle2 class="h-4 w-4 stroke-emerald-600" />
                <AlertTitle>Terverifikasi</AlertTitle>
                <AlertDescription>
                    Profil profesional Anda telah berhasil diverifikasi oleh admin. Data di bawah ini sudah tidak dapat diubah.
                </AlertDescription>
            </Alert>

            <Alert v-else-if="verificationStatus === 'pending'" class="bg-blue-50 text-blue-600 border-blue-200">
                <AlertCircle class="h-4 w-4 stroke-blue-600" />
                <AlertTitle>Menunggu Verifikasi</AlertTitle>
                <AlertDescription>
                    Pengajuan verifikasi Anda sedang ditinjau oleh admin. Harap menunggu maksimal 2x24 jam.
                </AlertDescription>
            </Alert>

            <Alert v-else-if="verificationStatus === 'rejected'" class="bg-rose-50 text-rose-600 border-rose-200">
                <AlertCircle class="h-4 w-4 stroke-rose-600" />
                <AlertTitle>Pengajuan Ditolak</AlertTitle>
                <AlertDescription>
                    <p>Pengajuan verifikasi Anda ditolak oleh admin.</p>
                    <p v-if="verificationNote" class="mt-1 font-semibold">Alasan: {{ verificationNote }}</p>
                    <p class="mt-2">Silakan perbaiki data dan ajukan kembali dokumen verifikasi Anda di bawah ini.</p>
                </AlertDescription>
            </Alert>

            <Card>
                <CardHeader>
                    <CardTitle>Dokumen Verifikasi Profesional</CardTitle>
                    <CardDescription>Upload dokumen asli yang jelas dan masih berlaku.</CardDescription>
                </CardHeader>
                <CardContent class="space-y-6">
                    <!-- Kontak & Email -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <Label for="phone">Nomor Kontak Aktif <span class="text-rose-500">*</span></Label>
                            <Input 
                                id="phone" 
                                v-model="form.phone" 
                                placeholder="Contoh: 081234567890" 
                                :disabled="isVerified || verificationStatus === 'pending'"
                            />
                        </div>
                        <div class="space-y-3">
                            <Label for="email">Email Profesional <span class="text-rose-500">*</span></Label>
                            <Input 
                                id="email" 
                                :value="user.email" 
                                disabled
                                class="bg-muted"
                            />
                            <p class="text-xs text-muted-foreground">Email terikat dengan akun Anda.</p>
                        </div>
                    </div>

                    <!-- KTP -->
                    <div class="space-y-3">
                        <Label>KTP / Identitas Diri <span class="text-rose-500">*</span></Label>
                        <div class="border-2 border-dashed rounded-xl p-6 text-center hover:bg-muted/50 transition-colors" :class="{'opacity-50 pointer-events-none': isVerified || verificationStatus === 'pending'}">
                            <input 
                                type="file" 
                                id="ktp" 
                                class="hidden" 
                                accept=".pdf,.jpg,.jpeg,.png"
                                @change="(e) => handleFileUpload(e, 'identity_document_url')"
                                :disabled="isVerified || verificationStatus === 'pending'"
                            >
                            <label for="ktp" class="cursor-pointer flex flex-col items-center gap-3">
                                <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                    <Upload class="w-6 h-6" />
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-foreground">Klik untuk upload KTP</p>
                                    <p class="text-xs text-muted-foreground">PDF, JPG, PNG (Max. 5MB)</p>
                                </div>
                            </label>
                            <p v-if="fileNames.identity_document_url" class="mt-4 text-sm font-bold text-primary">
                                {{ fileNames.identity_document_url }}
                            </p>
                        </div>
                    </div>

                    <!-- Sertifikat -->
                    <div class="space-y-3">
                        <Label>Sertifikat Profesi Arsitek (IAI / STRA) <span class="text-rose-500">*</span></Label>
                        <div class="border-2 border-dashed rounded-xl p-6 text-center hover:bg-muted/50 transition-colors" :class="{'opacity-50 pointer-events-none': isVerified || verificationStatus === 'pending'}">
                            <input 
                                type="file" 
                                id="sertifikat" 
                                class="hidden" 
                                accept=".pdf,.jpg,.jpeg,.png"
                                @change="(e) => handleFileUpload(e, 'license_document_url')"
                                :disabled="isVerified || verificationStatus === 'pending'"
                            >
                            <label for="sertifikat" class="cursor-pointer flex flex-col items-center gap-3">
                                <div class="w-12 h-12 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                    <Upload class="w-6 h-6" />
                                </div>
                                <div class="space-y-1">
                                    <p class="text-sm font-medium text-foreground">Klik untuk upload Sertifikat (IAI/STRA)</p>
                                    <p class="text-xs text-muted-foreground">PDF, JPG, PNG (Max. 5MB)</p>
                                </div>
                            </label>
                            <p v-if="fileNames.license_document_url" class="mt-4 text-sm font-bold text-primary">
                                {{ fileNames.license_document_url }}
                            </p>
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
