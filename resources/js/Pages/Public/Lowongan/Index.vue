<script setup>
import { ref, computed, watch } from "vue";
import { Head, Link, usePage, router, useForm } from "@inertiajs/vue3";
import PublicLayout from "@/Layouts/PublicLayout.vue";
import { 
  MapPin, 
  Briefcase, 
  Calculator, 
  Clock, 
  Bookmark, 
  ExternalLink, 
  Building,
  CheckCircle,
  CheckCircle2,
  ChevronRight,
  Search,
  Users
} from "lucide-vue-next";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";
import { Separator } from "@/Components/UI/ui/separator";
import { Input } from "@/Components/UI/ui/input";
import { Label } from "@/Components/UI/ui/label";
import { Textarea } from "@/Components/UI/ui/textarea";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogFooter,
  DialogHeader,
  DialogTitle,
} from "@/Components/UI/ui/dialog";

const props = defineProps({
  title: String,
  jobs: Array,
  filters: Object,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const isProfileVerified = computed(() => user.value?.profile?.verification_status === 'verified');

const searchQuery = ref(props.filters?.q || "");
const locationQuery = ref(props.filters?.l || "");

const selectedJob = ref(props.jobs && props.jobs.length > 0 ? props.jobs[0] : null);

const formatDate = (dateString) => {
  if (!dateString) return "-";

  return new Date(dateString).toLocaleDateString("id-ID", {
    day: "numeric",
    month: "short",
    year: "numeric",
  });
};

watch(() => props.jobs, (newJobs) => {
  if (newJobs?.length > 0) {
    if (!selectedJob.value || !newJobs.find(j => j.id === selectedJob.value?.id)) {
      selectedJob.value = newJobs[0];
    }
  } else {
    selectedJob.value = null;
  }
});

const handleSearch = () => {
  router.get(route("lowongan.index"), { 
    q: searchQuery.value,
    l: locationQuery.value 
  }, { 
    preserveState: true,
    replace: true 
  });
};

const selectJob = (job) => {
  selectedJob.value = job;
};

const applyModalOpen = ref(false);
const cvInputKey = ref(0);
const applyForm = useForm({
  cv: null,
  notes: '',
});

const handleFileChange = (e) => {
  applyForm.cv = e.target.files?.[0] || null;
  applyForm.clearErrors('cv');
};

const resetApplyForm = () => {
  applyForm.reset();
  applyForm.clearErrors();
  cvInputKey.value += 1;
};

const closeApplyModal = () => {
  applyModalOpen.value = false;
  resetApplyForm();
};

const submitApply = () => {
  if (!selectedJob.value) return;

  if (!applyForm.cv) {
    applyForm.setError('cv', 'CV wajib dipilih sebelum mengirim lamaran.');
    return;
  }
  
  applyForm.post(route('arsitek.lamaran.store', selectedJob.value.id), {
    forceFormData: true,
    preserveScroll: true,
    onSuccess: () => {
      closeApplyModal();
    },
  });
};

watch(applyModalOpen, (isOpen) => {
  if (!isOpen) {
    resetApplyForm();
  }
});

const handleAction = (action) => {
  if (!user.value) {
    router.visit(route('login'));
    return;
  }

  if (action === 'apply') {
    if (user.value.role === 'arsitek') {
      if (!isProfileVerified.value) {
        router.visit(route('arsitek.verifikasi.index'));
        return;
      }

      applyModalOpen.value = true;
    }
  }
};
</script>

<template>
  <PublicLayout :show-search="true" :show-footer="false">
    <Head :title="title" />

    <main class="flex-1 w-full max-w-[1280px] mx-auto flex flex-col md:flex-row bg-white">
      <!-- Job List -->
      <aside class="w-full md:w-80 lg:w-96 shrink-0 border-r border-border md:h-[calc(100vh-134px)] md:min-h-[600px] overflow-y-auto">
        <div class="sticky top-0 bg-background/95 backdrop-blur z-20 px-4 py-3 border-b border-border flex items-center justify-between">
          <span class="text-xs font-bold uppercase tracking-widest text-muted-foreground">{{ jobs?.length || 0 }} lowongan</span>
          <Button variant="ghost" size="sm" class="text-xs h-7 px-2 font-medium text-primary">Terbaru</Button>
        </div>

        <div class="p-3 space-y-2">
          <div
            v-for="job in jobs"
            :key="job.id"
            @click="selectJob(job)"
            :class="[
              'p-4 rounded-lg border transition-colors cursor-pointer',
              selectedJob?.id === job.id 
                ? 'bg-primary/5 border-primary' 
                : 'bg-card border-transparent hover:bg-muted hover:border-border'
            ]"
          >
            <div class="flex gap-3">
              <div class="flex-1 min-w-0">
                <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-1">{{ job.perusahaan }}</p>
                <h3 class="text-sm font-bold text-foreground leading-tight mb-2">{{ job.posisi }}</h3>
                <div class="flex flex-wrap gap-1.5 mb-2">
                  <Badge variant="outline" class="rounded text-[10px]">{{ job.tipe }}</Badge>
                  <Badge variant="outline" class="rounded text-[10px] text-primary border-primary/30">Rp {{ job.gaji }}</Badge>
                </div>
                <div class="flex items-center gap-3 text-xs text-muted-foreground">
                  <span class="flex items-center gap-1"><MapPin class="w-3 h-3" /> {{ job.kota }}</span>
                  <span class="flex items-center gap-1"><Clock class="w-3 h-3" /> s.d. {{ formatDate(job.batas_lamaran) }}</span>
                </div>
              </div>
              <Button v-if="!user || user.role === 'arsitek'" variant="ghost" size="icon" class="rounded-full w-8 h-8 shrink-0">
                <Bookmark class="w-4 h-4" />
              </Button>
            </div>
          </div>
        </div>
      </aside>

      <!-- Detail -->
      <section class="flex-1 bg-background md:h-[calc(100vh-134px)] md:min-h-[600px] overflow-y-auto px-6 lg:px-8 py-8">
        <div v-if="selectedJob" class="max-w-3xl mx-auto space-y-8">
          <div class="border border-border rounded-xl p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-start gap-6 mb-8">
              <div class="flex-1 space-y-4">
                <div class="flex items-center gap-3">
                  <p class="text-lg font-medium text-muted-foreground">{{ selectedJob.perusahaan }}</p>
                  <Badge class="bg-primary/10 text-primary rounded-full px-2 py-1 text-xs">Terverifikasi</Badge>
                </div>
                <h1 class="text-2xl font-bold text-foreground">{{ selectedJob.posisi }}</h1>
                <div class="flex flex-wrap items-center gap-4">
                  <div class="flex items-center gap-2 text-primary font-semibold">
                    <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center"><Calculator class="w-4 h-4" /></div>
                    <span>Rp {{ selectedJob.gaji }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-muted-foreground">
                    <div class="w-8 h-8 rounded-lg bg-muted flex items-center justify-center"><MapPin class="w-4 h-4 text-primary" /></div>
                    <span>{{ selectedJob.kota }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-muted-foreground">
                     <div class="w-8 h-8 rounded-lg bg-muted flex items-center justify-center"><Briefcase class="w-4 h-4 text-primary" /></div>
                    <span>{{ selectedJob.tipe }}</span>
                  </div>
                  <div class="flex items-center gap-2 text-muted-foreground">
                    <div class="w-8 h-8 rounded-lg bg-muted flex items-center justify-center"><Clock class="w-4 h-4 text-primary" /></div>
                    <span>Batas {{ formatDate(selectedJob.batas_lamaran) }}</span>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex flex-wrap gap-4">
              <Button v-if="!user || user.role === 'arsitek'" @click="handleAction('apply')" size="lg" class="rounded-lg px-8 h-12 font-semibold flex-1 md:flex-none">
                {{ user?.role === 'arsitek' && !isProfileVerified ? 'Verifikasi untuk Melamar' : 'Lamar Sekarang' }}
                <ChevronRight class="ml-2 w-5 h-5" />
              </Button>
              <Button v-if="!user || user.role === 'arsitek'" @click="handleAction('save')" variant="outline" size="lg" class="rounded-lg px-6 h-12 border-border">
                <Bookmark class="w-5 h-5 mr-2" />
                Simpan
              </Button>
              <Button variant="ghost" size="icon" class="rounded-lg w-12 h-12">
                <ExternalLink class="w-5 h-5" />
              </Button>
            </div>
          </div>

          <!-- Content -->
          <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
              <section>
                <div class="flex items-center gap-3 mb-4">
                  <div class="w-1 h-6 bg-primary rounded-full"></div>
                  <h3 class="text-xl font-bold">Tentang Pekerjaan</h3>
                </div>
                <p class="text-base text-muted-foreground leading-relaxed whitespace-pre-line">
                  {{ selectedJob.deskripsi }}
                </p>
              </section>

              <section>
                <div class="flex items-center gap-3 mb-6">
                  <div class="w-1 h-6 bg-primary rounded-full"></div>
                  <h3 class="text-xl font-bold">Kualifikasi & Peran</h3>
                </div>
                <div class="grid gap-6">
                  <div class="space-y-3">
                    <h4 class="text-base font-bold flex items-center gap-2">
                      <CheckCircle2 class="w-5 h-5 text-primary" />
                      Persyaratan Utama
                    </h4>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                      <li v-for="(syarat, idx) in selectedJob.syarat" :key="idx" class="p-3 rounded-lg bg-muted/30 border border-border text-sm font-medium flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></div>
                        {{ syarat }}
                      </li>
                    </ul>
                  </div>

                  <div class="space-y-3">
                    <h4 class="text-base font-bold flex items-center gap-2">
                      <CheckCircle class="w-5 h-5 text-primary" />
                      Tanggung Jawab
                    </h4>
                    <ul class="grid grid-cols-1 gap-3">
                      <li v-for="(task, idx) in selectedJob.tanggung_jawab" :key="idx" class="p-3 rounded-lg bg-muted/30 border border-border text-sm font-medium flex items-center gap-2">
                        <div class="w-1.5 h-1.5 rounded-full bg-primary shrink-0"></div>
                        {{ task }}
                      </li>
                    </ul>
                  </div>
                </div>
              </section>
            </div>

            <!-- Side Specs -->
            <div class="space-y-6">
              <Card class="rounded-xl border-border overflow-hidden">
                <CardHeader class="pb-3">
                  <CardTitle class="text-xs font-bold uppercase tracking-wider text-muted-foreground">Ringkasan Perusahaan</CardTitle>
                </CardHeader>
                <CardContent class="space-y-4">
                  <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-muted flex items-center justify-center"><Building class="w-5 h-5 text-primary" /></div>
                    <div>
                      <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Industri</p>
                      <p class="text-sm font-bold">Architecture & Design</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-muted flex items-center justify-center"><Users class="w-5 h-5 text-primary" /></div>
                    <div>
                      <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Ukuran</p>
                      <p class="text-sm font-bold">50 - 200 Karyawan</p>
                    </div>
                  </div>
                  <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-muted flex items-center justify-center"><Clock class="w-5 h-5 text-primary" /></div>
                    <div>
                      <p class="text-xs font-bold text-muted-foreground uppercase tracking-wider">Masa Lamaran</p>
                      <p class="text-sm font-bold">{{ formatDate(selectedJob.tanggal_mulai) }} - {{ formatDate(selectedJob.batas_lamaran) }}</p>
                    </div>
                  </div>
                  <Separator class="bg-border/50" />
                  <Button variant="outline" class="w-full rounded-lg font-medium border-border hover:border-primary/30">
                    Lihat Profil Perusahaan
                  </Button>
                </CardContent>
              </Card>

              <div class="bg-primary text-primary-foreground rounded-xl p-6 relative overflow-hidden">
                <h4 class="text-lg font-bold mb-3">Tingkatkan Peluangmu!</h4>
                <p class="text-sm font-medium mb-6 opacity-80 leading-relaxed">Arsitek dengan portofolio lengkap memiliki peluang 4x lebih besar.</p>
                <Button variant="secondary" class="w-full rounded-lg font-medium h-12 bg-white text-primary hover:bg-white">
                  Lengkapi Profil Now
                </Button>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty -->
        <div v-else class="h-full flex flex-col items-center justify-center text-center p-12 space-y-6">
          <div class="w-24 h-24 bg-muted rounded-full flex items-center justify-center">
             <Briefcase class="w-10 h-10 text-muted-foreground/40" />
          </div>
          <div class="max-w-xs space-y-2">
            <h3 class="text-xl font-bold">Pilih Lowongan</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">Silakan pilih lowongan di sebelah kiri untuk melihat detail pekerjaan.</p>
          </div>
        </div>
      </section>
    </main>

    <!-- Apply Job Modal -->
    <Dialog v-model:open="applyModalOpen">
      <DialogContent class="sm:max-w-[500px]">
        <form @submit.prevent="submitApply">
          <DialogHeader>
            <DialogTitle>Lamar Lowongan</DialogTitle>
            <DialogDescription v-if="selectedJob">
              Kirimkan lamaran Anda untuk posisi <strong>{{ selectedJob.posisi }}</strong> di <strong>{{ selectedJob.perusahaan }}</strong>.
            </DialogDescription>
          </DialogHeader>

          <div v-if="selectedJob" class="mt-5 rounded-lg border border-border bg-muted/30 p-4">
            <p class="text-xs font-bold uppercase tracking-wider text-muted-foreground">{{ selectedJob.perusahaan }}</p>
            <p class="mt-1 text-sm font-semibold text-foreground">{{ selectedJob.posisi }}</p>
            <div class="mt-3 flex flex-wrap gap-2 text-xs text-muted-foreground">
              <span class="inline-flex items-center gap-1"><MapPin class="h-3 w-3" /> {{ selectedJob.kota }}</span>
              <span class="inline-flex items-center gap-1"><Briefcase class="h-3 w-3" /> {{ selectedJob.tipe }}</span>
            </div>
          </div>
          
          <div class="grid gap-6 py-6">
            <div class="grid gap-2">
              <Label htmlFor="cv">Curriculum Vitae</Label>
              <Input 
                :key="cvInputKey"
                id="cv" 
                type="file" 
                accept=".pdf,.doc,.docx"
                @change="handleFileChange"
                :class="{ 'border-destructive': applyForm.errors.cv }"
              />
              <p class="text-[11px] text-muted-foreground">Wajib diisi. Format yang didukung: PDF, DOC, DOCX. Maksimal 5MB.</p>
              <p v-if="applyForm.errors.cv" class="text-xs text-destructive mt-1">{{ applyForm.errors.cv }}</p>
            </div>
            
            <div class="grid gap-2">
              <Label htmlFor="notes">Catatan Tambahan (Opsional)</Label>
              <Textarea 
                id="notes" 
                v-model="applyForm.notes"
                placeholder="Tuliskan catatan singkat, pengalaman relevan, atau mengapa Anda cocok untuk posisi ini..."
                rows="4"
                :class="{ 'border-destructive': applyForm.errors.notes }"
              />
              <p v-if="applyForm.errors.notes" class="text-xs text-destructive mt-1">{{ applyForm.errors.notes }}</p>
            </div>
          </div>
          
          <DialogFooter>
            <Button type="button" variant="outline" @click="closeApplyModal" :disabled="applyForm.processing">
              Batal
            </Button>
            <Button type="submit" :disabled="applyForm.processing || !selectedJob">
              <span v-if="applyForm.processing">Mengirim...</span>
              <span v-else>Kirim Lamaran</span>
            </Button>
          </DialogFooter>
        </form>
      </DialogContent>
    </Dialog>

  </PublicLayout>
</template>

<style scoped>
.font-display {
  font-family: 'Outfit', sans-serif;
}
</style> 
