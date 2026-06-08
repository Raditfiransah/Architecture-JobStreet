<script setup>
import { ref, computed } from "vue";
import { Head, Link, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {
    ArrowLeft,
    BadgeCheck,
    XCircle,
    Clock,
    AlertTriangle,
    ExternalLink,
    FileText,
    User,
    Building2,
    CheckCircle2,
    CalendarDays,
    MapPin,
    Phone,
    Mail,
    Briefcase,
} from "lucide-vue-next";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";
import {
    Avatar,
    AvatarImage,
    AvatarFallback,
} from "@/Components/UI/ui/avatar";

const props = defineProps({
    profile: Object,
    type: String,
});

// ─── Document list per type ───────────────────────────────────────────────────
const documents = computed(() => {
    if (props.type === "arsitek") {
        return [
            {
                key: "identity",
                label: "KTP / Identitas Diri",
                url: props.profile.identity_document_url,
                required: true,
            },
            {
                key: "license",
                label: "Sertifikat STRA / Profesi",
                url: props.profile.license_document_url,
                required: true,
            },
        ];
    }
    if (props.type === "company") {
        return [
            {
                key: "identity",
                label: "NIB / Identitas Diri",
                url: props.profile.identity_document_url,
                required: true,
            },
            {
                key: "npwp",
                label: "NPWP Perusahaan",
                url: props.profile.npwp_document_url,
                required: true,
            },
            {
                key: "akta",
                label: "Akta Pendirian Perusahaan",
                url: props.profile.akta_document_url,
                required: true,
            },
            {
                key: "siup",
                label: "SIUP / Izin Usaha",
                url: props.profile.siup_document_url,
                required: true,
            },
            {
                key: "pic",
                label: "Surat Penunjukan PIC / HRD",
                url: props.profile.pic_document_url,
                required: false,
            },
        ];
    }
    if (props.type === "client") {
        return [
            {
                key: "identity",
                label: "KTP / Identitas Diri",
                url: props.profile.identity_document_url,
                required: true,
            },
            {
                key: "domicile",
                label: "Surat Keterangan Domisili",
                url: props.profile.domicile_document_url,
                required: false,
            },
            {
                key: "project_ownership",
                label: "Bukti Kepemilikan Proyek / Lahan",
                url: props.profile.project_ownership_document_url,
                required: false,
            },
        ];
    }
    return [];
});

// Only docs that were actually uploaded
const uploadedDocuments = computed(() =>
    documents.value.filter((d) => d.url)
);

const openDoc = (url) => {
    window.open(url, "_blank");
};

// ─── Status config ────────────────────────────────────────────────────────────
const statusConfig = computed(() => {
    const map = {
        verified: {
            label: "Terverifikasi",
            class: "bg-emerald-500/10 text-emerald-600 border-emerald-500/20",
            icon: BadgeCheck,
        },
        pending: {
            label: "Menunggu Verifikasi",
            class: "bg-orange-500/10 text-orange-600 border-orange-500/20",
            icon: Clock,
        },
        rejected: {
            label: "Ditolak",
            class: "bg-rose-500/10 text-rose-600 border-rose-500/20",
            icon: XCircle,
        },
        unverified: {
            label: "Belum Diverifikasi",
            class: "bg-muted text-muted-foreground border-border",
            icon: AlertTriangle,
        },
    };
    return (
        map[props.profile.verification_status] || map["unverified"]
    );
});

// ─── Display name & avatar ────────────────────────────────────────────────────
const displayName = computed(
    () =>
        props.profile.company_name ||
        (props.profile.first_name
            ? `${props.profile.first_name} ${props.profile.last_name ?? ""}`.trim()
            : props.profile.user?.name) ||
        "—"
);

const avatarUrl = computed(
    () => props.profile.company_logo_url || props.profile.user?.avatar_url
);

const avatarFallback = computed(() =>
    displayName.value.charAt(0).toUpperCase()
);

// ─── Actions ──────────────────────────────────────────────────────────────────
const rejectNote = ref("");
const showRejectForm = ref(false);

const handleVerify = () => {
    if (!confirm("Verifikasi profil ini?")) return;
    router.post(
        route("admin.profiles.verify", {
            type: props.type,
            profile: props.profile.id,
        }),
        {},
        { preserveScroll: true }
    );
};

const handleReject = () => {
    if (rejectNote.value.trim().length < 5) {
        alert("Alasan penolakan wajib diisi minimal 5 karakter.");
        return;
    }
    router.post(
        route("admin.profiles.reject", {
            type: props.type,
            profile: props.profile.id,
        }),
        { note: rejectNote.value },
        { preserveScroll: true }
    );
};

const typeLabel = computed(() => {
    return { arsitek: "Arsitek", company: "Perusahaan", client: "Client" }[props.type] ?? props.type;
});
</script>

<template>
    <Head :title="`Detail Profil — ${displayName}`" />

    <AuthenticatedLayout>
        <div class="space-y-8 max-w-4xl mx-auto">

            <!-- Back + breadcrumb -->
            <div class="flex items-center gap-3">
                <Link
                    :href="route('admin.profiles.index', { type })"
                    class="flex items-center gap-2 text-sm text-muted-foreground hover:text-foreground transition-colors font-medium"
                >
                    <ArrowLeft class="w-4 h-4" />
                    Kembali ke Moderasi Profil
                </Link>
                <span class="text-muted-foreground/40">/</span>
                <span class="text-sm font-semibold text-foreground">{{ displayName }}</span>
            </div>

            <!-- Profile header card -->
            <div class="bg-card border border-border/60 rounded-2xl shadow-sm p-6">
                <div class="flex flex-col sm:flex-row sm:items-start gap-5">
                    <Avatar class="h-16 w-16 rounded-2xl border border-border/60 shrink-0">
                        <AvatarImage :src="avatarUrl" />
                        <AvatarFallback class="bg-primary/5 text-primary font-bold text-xl rounded-2xl">
                            {{ avatarFallback }}
                        </AvatarFallback>
                    </Avatar>

                    <div class="flex-1 min-w-0 space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <h1 class="text-2xl font-bold text-foreground">{{ displayName }}</h1>
                            <Badge
                                variant="outline"
                                :class="['rounded-lg font-bold text-[10px] uppercase tracking-wider px-2.5 py-1 border flex items-center gap-1.5', statusConfig.class]"
                            >
                                <component :is="statusConfig.icon" class="w-3.5 h-3.5" />
                                {{ statusConfig.label }}
                            </Badge>
                        </div>

                        <div class="flex flex-wrap gap-x-5 gap-y-1 text-sm text-muted-foreground">
                            <span v-if="profile.user?.email" class="flex items-center gap-1.5">
                                <Mail class="w-3.5 h-3.5" />
                                {{ profile.user.email }}
                            </span>
                            <span v-if="profile.user?.phone || profile.phone" class="flex items-center gap-1.5">
                                <Phone class="w-3.5 h-3.5" />
                                {{ profile.user?.phone || profile.phone }}
                            </span>
                            <span v-if="profile.location" class="flex items-center gap-1.5">
                                <MapPin class="w-3.5 h-3.5" />
                                {{ profile.location }}
                            </span>
                            <span v-if="profile.industry || profile.specialization" class="flex items-center gap-1.5">
                                <Briefcase class="w-3.5 h-3.5" />
                                {{ profile.industry || profile.specialization }}
                            </span>
                            <span v-if="profile.verified_at" class="flex items-center gap-1.5 text-emerald-600">
                                <CalendarDays class="w-3.5 h-3.5" />
                                Diverifikasi {{ new Date(profile.verified_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                            </span>
                        </div>

                        <!-- Rejection note -->
                        <div
                            v-if="profile.verification_status === 'rejected' && profile.verification_note"
                            class="mt-2 p-3 bg-rose-50 border border-rose-200 rounded-xl text-sm text-rose-700"
                        >
                            <span class="font-bold">Alasan penolakan:</span> {{ profile.verification_note }}
                        </div>
                    </div>

                    <!-- Role badge -->
                    <Badge variant="outline" class="shrink-0 rounded-xl font-bold text-xs uppercase tracking-wider px-3 py-1.5 border bg-muted/30">
                        <component :is="type === 'company' ? Building2 : User" class="w-3.5 h-3.5 mr-1.5" />
                        {{ typeLabel }}
                    </Badge>
                </div>
            </div>

            <!-- Documents section -->
            <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-border/40 bg-muted/20">
                    <h2 class="font-bold text-base text-foreground flex items-center gap-2">
                        <FileText class="w-4 h-4 text-primary" />
                        Dokumen yang Diunggah
                    </h2>
                    <p class="text-xs text-muted-foreground mt-0.5">
                        Buka setiap dokumen sebelum melakukan verifikasi.
                    </p>
                </div>

                <div class="divide-y divide-border/40">
                    <!-- No documents at all -->
                    <div
                        v-if="uploadedDocuments.length === 0"
                        class="px-6 py-12 text-center text-muted-foreground text-sm"
                    >
                        <FileText class="w-10 h-10 mx-auto mb-3 opacity-20" />
                        Belum ada dokumen yang diunggah.
                    </div>

                    <div
                        v-for="doc in documents"
                        :key="doc.key"
                        class="flex items-center justify-between px-6 py-4 gap-4"
                        :class="{ 'opacity-40': !doc.url }"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                                :class="doc.url ? 'bg-primary/5' : 'bg-muted'"
                            >
                                <FileText
                                    class="w-4 h-4"
                                    :class="doc.url ? 'text-primary' : 'text-muted-foreground'"
                                />
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-foreground">
                                    {{ doc.label }}
                                    <span
                                        v-if="!doc.required"
                                        class="ml-1.5 text-[10px] font-bold uppercase tracking-wider text-muted-foreground bg-muted px-1.5 py-0.5 rounded"
                                    >Opsional</span>
                                </p>
                                <p v-if="!doc.url" class="text-xs text-muted-foreground italic">Belum diunggah</p>
                                <p v-else class="text-xs text-muted-foreground">Dokumen tersedia</p>
                            </div>
                        </div>

                        <Button
                            v-if="doc.url"
                            @click="openDoc(doc.url)"
                            variant="outline"
                            size="sm"
                            class="shrink-0 h-9 px-4 rounded-xl gap-2 font-bold text-xs border-primary/30 text-primary hover:bg-primary/5"
                        >
                            <ExternalLink class="w-3.5 h-3.5" />
                            Buka Dokumen
                        </Button>
                        <span v-else class="text-xs text-muted-foreground italic shrink-0">—</span>
                    </div>
                </div>
            </div>

            <!-- Action section -->
            <div
                v-if="profile.verification_status !== 'verified'"
                class="bg-card border border-border/60 rounded-2xl shadow-sm p-6 space-y-5"
            >
                <h2 class="font-bold text-base text-foreground">Keputusan Verifikasi</h2>

                <!-- No documents uploaded -->
                <div
                    v-if="uploadedDocuments.length === 0"
                    class="flex items-start gap-3 p-4 bg-muted/40 border border-border rounded-xl text-sm text-muted-foreground"
                >
                    <AlertTriangle class="w-4 h-4 mt-0.5 shrink-0" />
                    <span>User belum mengunggah dokumen apapun. Tidak dapat diverifikasi.</span>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <!-- Approve -->
                    <Button
                        @click="handleVerify"
                        :disabled="uploadedDocuments.length === 0"
                        class="flex-1 h-11 rounded-xl gap-2 font-bold bg-emerald-600 hover:bg-emerald-700 text-white disabled:opacity-40 disabled:cursor-not-allowed"
                    >
                        <CheckCircle2 class="w-4 h-4" />
                        Setujui Verifikasi
                    </Button>

                    <!-- Reject toggle -->
                    <Button
                        @click="showRejectForm = !showRejectForm"
                        variant="outline"
                        class="flex-1 h-11 rounded-xl gap-2 font-bold border-rose-200 text-rose-600 hover:bg-rose-50"
                    >
                        <XCircle class="w-4 h-4" />
                        Tolak
                    </Button>
                </div>

                <!-- Reject form -->
                <div v-if="showRejectForm" class="space-y-3 pt-1">
                    <label class="text-sm font-semibold text-foreground">
                        Alasan Penolakan <span class="text-rose-500">*</span>
                    </label>
                    <textarea
                        v-model="rejectNote"
                        rows="3"
                        placeholder="Jelaskan alasan penolakan kepada user (minimal 5 karakter)..."
                        class="w-full px-4 py-3 text-sm border border-border rounded-xl bg-background focus:outline-none focus:ring-2 focus:ring-rose-300 resize-none"
                    />
                    <div class="flex gap-2 justify-end">
                        <Button
                            @click="showRejectForm = false"
                            variant="ghost"
                            size="sm"
                            class="rounded-xl text-muted-foreground"
                        >
                            Batal
                        </Button>
                        <Button
                            @click="handleReject"
                            size="sm"
                            class="rounded-xl gap-2 font-bold bg-rose-600 hover:bg-rose-700 text-white"
                        >
                            <XCircle class="w-3.5 h-3.5" />
                            Konfirmasi Tolak
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Already verified state -->
            <div
                v-else
                class="flex items-center gap-3 p-5 bg-emerald-50 border border-emerald-200 rounded-2xl text-emerald-700"
            >
                <BadgeCheck class="w-5 h-5 shrink-0" />
                <div>
                    <p class="font-bold text-sm">Profil ini sudah terverifikasi.</p>
                    <p v-if="profile.verified_at" class="text-xs mt-0.5 text-emerald-600">
                        Diverifikasi pada {{ new Date(profile.verified_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                    </p>
                </div>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
