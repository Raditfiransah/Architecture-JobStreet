<script setup>
import { Head, router } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  Monitor, 
  AlertCircle, 
  Trash2, 
  RefreshCcw,
  Clock,
  Database,
  Layers,
  Activity
} from "lucide-vue-next";
import { 
  Table, 
  TableBody, 
  TableCell, 
  TableHead, 
  TableHeader, 
  TableRow 
} from "@/Components/UI/ui/table";
import { Card, CardContent, CardHeader, CardTitle } from "@/Components/UI/ui/card";
import { Button } from "@/Components/UI/ui/button";
import { Badge } from "@/Components/UI/ui/badge";

const props = defineProps({
  failedJobs: Array,
  jobsCount: Number,
  jobs: Array,
});

const handleClearFailed = () => {
  if (confirm("Bersihkan semua antrian yang gagal?")) {
    router.post(route('admin.system.clear-failed'));
  }
};
</script>

<template>
  <Head title="System Monitoring" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-foreground">System Monitoring</h1>
          <p class="text-muted-foreground mt-1">Pantau performa sistem, antrian jobs, dan kesalahan teknis.</p>
        </div>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden">
          <CardContent class="p-6 flex items-center gap-4">
             <div class="p-3 bg-blue-500/10 text-blue-500 rounded-xl">
                <Layers class="w-6 h-6" />
             </div>
             <div>
                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Antrian Aktif</p>
                <h3 class="text-2xl font-bold text-foreground mt-0.5">{{ jobsCount }} Jobs</h3>
             </div>
          </CardContent>
        </Card>
        <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden">
          <CardContent class="p-6 flex items-center gap-4">
             <div class="p-3 bg-rose-500/10 text-rose-500 rounded-xl">
                <AlertCircle class="w-6 h-6" />
             </div>
             <div>
                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">Jobs Gagal</p>
                <h3 class="text-2xl font-bold text-foreground mt-0.5">{{ failedJobs.length }} Errors</h3>
             </div>
          </CardContent>
        </Card>
        <Card class="border-border/60 shadow-sm rounded-2xl overflow-hidden">
          <CardContent class="p-6 flex items-center gap-4">
             <div class="p-3 bg-emerald-500/10 text-emerald-500 rounded-xl">
                <Activity class="w-6 h-6" />
             </div>
             <div>
                <p class="text-[10px] font-bold text-muted-foreground uppercase tracking-wider">System Load</p>
                <h3 class="text-2xl font-bold text-foreground mt-0.5">Optimal</h3>
             </div>
          </CardContent>
        </Card>
      </div>

      <!-- Failed Jobs Table -->
      <div class="space-y-4">
         <div class="flex items-center justify-between px-2">
            <h2 class="text-xl font-bold flex items-center gap-2">
               <AlertCircle class="w-5 h-5 text-rose-500" />
               Daftar Failed Jobs
            </h2>
            <Button @click="handleClearFailed" variant="destructive" size="sm" class="rounded-xl h-9 px-4 gap-2 font-bold text-[10px] uppercase tracking-wider shadow-lg shadow-rose-500/20">
               <Trash2 class="w-3.5 h-3.5" />
               Clear All
            </Button>
         </div>
         
         <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
            <Table>
               <TableHeader class="bg-muted/30">
                  <TableRow>
                     <TableHead class="font-bold text-xs uppercase tracking-wider py-4">ID / Queue</TableHead>
                     <TableHead class="font-bold text-xs uppercase tracking-wider">Payload</TableHead>
                     <TableHead class="font-bold text-xs uppercase tracking-wider">Failed At</TableHead>
                  </TableRow>
               </TableHeader>
               <TableBody>
                  <TableRow v-for="job in failedJobs" :key="job.id" class="group hover:bg-muted/5 transition-colors">
                     <TableCell class="py-4">
                        <div class="font-bold text-sm text-foreground">#{{ job.id }}</div>
                        <div class="text-[10px] font-bold text-primary uppercase mt-1">{{ job.queue }}</div>
                     </TableCell>
                     <TableCell class="max-w-md">
                        <p class="text-xs text-muted-foreground truncate font-mono bg-muted/50 p-2 rounded-lg border border-border/40">
                           {{ job.payload }}
                        </p>
                     </TableCell>
                     <TableCell>
                        <div class="flex items-center gap-1.5 text-xs text-muted-foreground font-medium">
                           <Clock class="w-3.5 h-3.5" />
                           {{ new Date(job.failed_at).toLocaleString() }}
                        </div>
                     </TableCell>
                  </TableRow>
                  <TableRow v-if="failedJobs.length === 0">
                     <TableCell colspan="3" class="py-20 text-center">
                        <div class="flex flex-col items-center justify-center space-y-3">
                           <div class="p-4 bg-emerald-500/10 rounded-full">
                              <RefreshCcw class="w-10 h-10 text-emerald-500/40" />
                           </div>
                           <p class="text-sm font-bold text-muted-foreground">Tidak ada kegagalan antrian saat ini.</p>
                        </div>
                     </TableCell>
                  </TableRow>
               </TableBody>
            </Table>
         </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
