<script setup>
import { Head } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { 
  ShieldCheck, 
  Lock, 
  Mail, 
  AlertTriangle,
  Clock,
  User,
  Fingerprint,
  History
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
import { Badge } from "@/Components/UI/ui/badge";

const props = defineProps({
  verificationCodes: Array,
  passwordResets: Array,
});
</script>

<template>
  <Head title="Security Log" />

  <AuthenticatedLayout>
    <div class="space-y-8">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-bold tracking-tight text-foreground">Security & Verification</h1>
          <p class="text-muted-foreground mt-1">Pantau upaya verifikasi email dan reset password untuk mendeteksi penyalahgunaan.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
         <!-- Email Verification Log -->
         <div class="space-y-4">
            <h2 class="text-xl font-bold flex items-center gap-2 px-2">
               <Mail class="w-5 h-5 text-blue-500" />
               Email Verification Codes
            </h2>
            <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
               <Table>
                  <TableHeader class="bg-muted/30">
                     <TableRow>
                        <TableHead class="font-bold text-xs uppercase tracking-wider py-4">User</TableHead>
                        <TableHead class="font-bold text-xs uppercase tracking-wider">Status</TableHead>
                        <TableHead class="font-bold text-xs uppercase tracking-wider">Created</TableHead>
                     </TableRow>
                  </TableHeader>
                  <TableBody>
                     <TableRow v-for="code in verificationCodes" :key="code.id" class="group hover:bg-muted/5 transition-colors">
                        <TableCell class="py-4">
                           <div class="flex items-center gap-3">
                              <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500 shrink-0 border border-blue-500/10">
                                 <Fingerprint class="w-4 h-4" />
                              </div>
                              <div class="min-w-0">
                                 <p class="font-bold text-xs text-foreground truncate">{{ code.user?.email || 'Unknown' }}</p>
                                 <p class="text-[10px] text-muted-foreground font-mono">{{ code.code }}</p>
                              </div>
                           </div>
                        </TableCell>
                        <TableCell>
                           <Badge :variant="code.is_used ? 'outline' : 'secondary'" class="rounded-lg font-bold text-[9px] uppercase">
                              {{ code.is_used ? 'Terpakai' : 'Aktif' }}
                           </Badge>
                        </TableCell>
                        <TableCell class="text-[10px] font-medium text-muted-foreground">
                           {{ new Date(code.created_at).toLocaleString() }}
                        </TableCell>
                     </TableRow>
                  </TableBody>
               </Table>
            </div>
         </div>

         <!-- Password Reset Log -->
         <div class="space-y-4">
            <h2 class="text-xl font-bold flex items-center gap-2 px-2">
               <Lock class="w-5 h-5 text-orange-500" />
               Password Reset Tokens
            </h2>
            <div class="bg-card border border-border/60 rounded-2xl shadow-sm overflow-hidden">
               <Table>
                  <TableHeader class="bg-muted/30">
                     <TableRow>
                        <TableHead class="font-bold text-xs uppercase tracking-wider py-4">Email</TableHead>
                        <TableHead class="font-bold text-xs uppercase tracking-wider">Created</TableHead>
                     </TableRow>
                  </TableHeader>
                  <TableBody>
                     <TableRow v-for="reset in passwordResets" :key="reset.email" class="group hover:bg-muted/5 transition-colors">
                        <TableCell class="py-4">
                           <div class="flex items-center gap-3">
                              <div class="w-8 h-8 rounded-lg bg-orange-500/10 flex items-center justify-center text-orange-500 shrink-0 border border-orange-500/10">
                                 <AlertTriangle class="w-4 h-4" />
                              </div>
                              <p class="font-bold text-xs text-foreground truncate">{{ reset.email }}</p>
                           </div>
                        </TableCell>
                        <TableCell class="text-[10px] font-medium text-muted-foreground">
                           {{ new Date(reset.created_at).toLocaleString() }}
                        </TableCell>
                     </TableRow>
                     <TableRow v-if="passwordResets.length === 0">
                        <TableCell colspan="2" class="py-20 text-center">
                           <p class="text-xs font-bold text-muted-foreground">Tidak ada aktivitas reset password.</p>
                        </TableCell>
                     </TableRow>
                  </TableBody>
               </Table>
            </div>
         </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
