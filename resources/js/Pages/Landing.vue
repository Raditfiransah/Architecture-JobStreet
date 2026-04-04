<script setup>
import { ref, computed } from 'vue';
import { Head, Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);

const mobileOpen = ref(false);
const userDropdownOpen = ref(false);
const activeTab = ref('Semua');
const caraKerjaRole = ref('arsitek');

const dummyJobs = [
    { posisi: 'Senior Arsitek', perusahaan: 'Arkindo Studio', kota: 'Jakarta Selatan', tipe: 'Full Time', gaji: 'Rp 8–12 jt', waktu: '2 jam lalu', featured: true, inisial: 'A' },
    { posisi: 'BIM Specialist', perusahaan: 'PT Graha Design', kota: 'Surabaya', tipe: 'Full Time', gaji: 'Rp 6–9 jt', waktu: '5 jam lalu', featured: false, inisial: 'G' },
    { posisi: 'Interior Designer', perusahaan: 'Kana Interiors', kota: 'Bandung', tipe: 'Freelance', gaji: 'Nego', waktu: '1 hari lalu', featured: false, inisial: 'K' },
    { posisi: 'Arsitek Landscape', perusahaan: 'Hijau Lestari', kota: 'Bali', tipe: 'Full Time', gaji: 'Rp 7–10 jt', waktu: '1 hari lalu', featured: true, inisial: 'H' },
    { posisi: 'Junior Arsitek', perusahaan: 'Studio Satu', kota: 'Yogyakarta', tipe: 'Magang', gaji: 'Rp 2–3 jt', waktu: '2 hari lalu', featured: false, inisial: 'S' },
    { posisi: 'Urban Planner', perusahaan: 'Kota Baru Corp', kota: 'Jakarta Pusat', tipe: 'Remote', gaji: 'Rp 10–15 jt', waktu: '3 hari lalu', featured: false, inisial: 'KB' },
];

const stats = [
    ['1.200+', 'Arsitek Terdaftar'],
    ['340+', 'Lowongan Aktif'],
    ['180+', 'Proyek Tersedia'],
    ['95%', 'Arsitek Terverifikasi'],
];

const tabs = ['Semua', 'Full Time', 'Freelance', 'Magang', 'Remote'];

const getBadgeColor = (tipe) => {
    switch(tipe) {
        case 'Full Time': return 'bg-primary-100 text-primary-700';
        case 'Freelance': return 'bg-amber-50 text-amber-700';
        case 'Magang': return 'bg-blue-50 text-blue-700';
        case 'Remote': return 'bg-purple-50 text-purple-700';
        default: return 'bg-surface-muted text-ink-muted';
    }
};

const arsitekSteps = [
    { num: '1', title: 'Buat profil & portofolio', desc: 'Upload karya terbaik dan lengkapi profil profesionalmu dalam hitungan menit.' },
    { num: '2', title: 'Temukan peluang', desc: 'Cari lowongan kerja atau proyek freelance yang sesuai keahlian dan lokasimu.' },
    { num: '3', title: 'Lamar & berkembang', desc: 'Kirim lamaran langsung dengan portofolio terintegrasi. Pantau status di dashboard.' },
];

const perusahaanSteps = [
    { num: '1', title: 'Buat profil perusahaan', desc: 'Daftarkan perusahaan dan lengkapi informasi untuk menarik arsitek berkualitas.' },
    { num: '2', title: 'Posting lowongan atau proyek', desc: 'Buat listing lowongan kerja atau proyek freelance dalam beberapa menit.' },
    { num: '3', title: 'Temukan kandidat terbaik', desc: 'Review lamaran masuk, lihat portofolio, dan hubungi arsitek yang tepat.' },
];

const popularTags = ['Arsitek Residensial', 'BIM Specialist', 'Interior Designer', 'Urban Planner', 'Fresh Graduate'];
</script>

<template>
    <Head>
        <title>Loker Arsitek — Platform Arsitek #1 di Indonesia</title>
        <meta name="description" content="Temukan lowongan kerja, proyek freelance, dan portofolio digital untuk arsitek Indonesia. Bergabung dengan 1.200+ arsitek terverifikasi.">
    </Head>

    <div class="bg-white text-ink font-sans antialiased min-h-screen">
        <!-- Navbar -->
        <nav class="sticky top-0 z-50 w-full bg-white border-b border-[#e4ede8]">
            <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
                <!-- Logo -->
                <Link :href="route('home')" class="flex items-center gap-2">
                    <span class="w-5 h-5 rounded border-2 border-primary-300 flex items-center justify-center">
                        <span class="w-2 h-2 bg-primary-300 rounded-sm"></span>
                    </span>
                    <span class="text-xl font-bold tracking-tight">
                        <span class="text-ink">Loker</span><span class="text-primary-400">Arsitek</span>
                    </span>
                </Link>

                <!-- Center nav links (desktop) -->
                <div class="hidden md:flex items-center gap-8">
                    <Link :href="route('lowongan.index')" class="text-sm text-ink-muted hover:text-ink transition-colors duration-150">Lowongan Kerja</Link>
                    <Link :href="route('proyek.index')" class="text-sm text-ink-muted hover:text-ink transition-colors duration-150">Proyek</Link>
                    <Link :href="route('arsitek.direktori')" class="text-sm text-ink-muted hover:text-ink transition-colors duration-150">Arsitek</Link>
                    <Link :href="route('info.index')" class="text-sm text-ink-muted hover:text-ink transition-colors duration-150">Info Hub</Link>
                </div>

                <!-- Right actions -->
                <div class="hidden md:flex items-center gap-3">
                    <template v-if="!user">
                        <Link :href="route('login')" class="text-sm px-4 py-2 rounded-lg border border-[#e4ede8] bg-white text-ink hover:bg-surface-muted transition-colors duration-150">Masuk</Link>
                        <Link :href="route('register')" class="text-sm px-4 py-2 rounded-lg bg-primary-300 text-white font-medium hover:bg-primary-400 transition-colors duration-150">Daftar</Link>
                    </template>
                    <template v-else>
                        <div class="relative">
                            <button @click="userDropdownOpen = !userDropdownOpen" class="w-9 h-9 rounded-full bg-primary-100 text-primary-700 font-semibold text-sm flex items-center justify-center hover:ring-2 hover:ring-primary-200 transition">
                                {{ user.name.charAt(0).toUpperCase() }}
                            </button>
                            <div v-if="userDropdownOpen" v-click-away="() => userDropdownOpen = false" class="absolute right-0 mt-2 w-44 bg-white border border-[#e4ede8] rounded-xl shadow-lg py-1 z-50">
                                <Link :href="route(user.dashboard_route)" class="block px-4 py-2 text-sm text-ink-soft hover:bg-surface-muted transition">Dashboard</Link>
                                <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-4 py-2 text-sm text-ink-soft hover:bg-surface-muted transition">Logout</Link>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Hamburger (mobile) -->
                <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 -mr-2 text-ink-muted hover:text-ink transition" aria-label="Toggle menu">
                    <svg v-if="!mobileOpen" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Mobile menu -->
            <transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 -translate-y-2" enter-to-class="opacity-100 translate-y-0" leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100 translate-y-0" leave-to-class="opacity-0 -translate-y-2">
                <div v-if="mobileOpen" class="md:hidden border-t border-[#e4ede8] bg-white px-6 py-4 space-y-3">
                    <Link :href="route('lowongan.index')" class="block text-sm text-ink-muted hover:text-ink py-1">Lowongan Kerja</Link>
                    <Link :href="route('proyek.index')" class="block text-sm text-ink-muted hover:text-ink py-1">Proyek</Link>
                    <Link :href="route('arsitek.direktori')" class="block text-sm text-ink-muted hover:text-ink py-1">Arsitek</Link>
                    <Link :href="route('info.index')" class="block text-sm text-ink-muted hover:text-ink py-1">Info Hub</Link>
                    <div class="pt-3 border-t border-[#e4ede8] flex gap-3">
                        <template v-if="!user">
                            <Link :href="route('login')" class="flex-1 text-center text-sm px-4 py-2 rounded-lg border border-[#e4ede8] text-ink hover:bg-surface-muted transition">Masuk</Link>
                            <Link :href="route('register')" class="flex-1 text-center text-sm px-4 py-2 rounded-lg bg-primary-300 text-white font-medium hover:bg-primary-400 transition">Daftar</Link>
                        </template>
                        <template v-else>
                            <Link :href="route(user.dashboard_route)" class="flex-1 text-center text-sm px-4 py-2 rounded-lg bg-primary-300 text-white font-medium hover:bg-primary-400 transition">Dashboard</Link>
                        </template>
                    </div>
                </div>
            </transition>
        </nav>

        <!-- Hero -->
        <section class="min-h-[580px] bg-gradient-to-b from-primary-50 to-white py-20 md:py-28">
            <div class="max-w-6xl mx-auto px-6 flex flex-col lg:flex-row items-center gap-12 lg:gap-16">
                <div class="flex-1 max-w-xl lg:max-w-none">
                    <span class="inline-block bg-primary-100 text-primary-700 text-xs font-medium px-3 py-1 rounded-full mb-5">
                        Platform Arsitek #1 di Indonesia
                    </span>
                    <h1 class="text-[32px] md:text-[48px] font-bold text-ink leading-[1.15] tracking-tight">
                        Temukan Peluang Terbaik untuk <span class="text-primary-400">Arsitek Indonesia</span>
                    </h1>
                    <p class="mt-4 text-lg text-ink-muted leading-relaxed">
                        Lowongan kerja, proyek freelance, dan portofolio digital — semua dalam satu platform.
                    </p>

                    <div class="mt-8 bg-white border border-[#e4ede8] rounded-2xl p-2 shadow-md shadow-primary-100/50">
                        <form @submit.prevent class="flex flex-col md:flex-row items-stretch md:items-center">
                            <input type="text" placeholder="Cari posisi, keahlian, atau perusahaan..." class="flex-1 border-0 bg-transparent px-4 py-3 text-[15px] text-ink placeholder:text-ink-muted/60 focus:ring-0 focus:outline-none">
                            <div class="hidden md:block w-px h-6 bg-[#e4ede8] self-center shrink-0"></div>
                            <select class="hidden md:block border-0 bg-transparent px-4 py-3 text-[15px] text-ink-muted focus:ring-0 focus:outline-none appearance-none cursor-pointer">
                                <option value="">Semua Kota</option>
                                <option value="jakarta">Jakarta</option>
                                <option value="surabaya">Surabaya</option>
                            </select>
                            <button type="submit" class="mt-2 md:mt-0 bg-primary-300 hover:bg-primary-400 text-white font-semibold text-[15px] px-6 py-3 rounded-xl transition-colors duration-150 shrink-0">
                                Cari Lowongan
                            </button>
                        </form>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <span class="text-xs text-ink-muted">Pencarian populer:</span>
                        <Link v-for="tag in popularTags" :key="tag" :href="route('lowongan.index', { q: tag })" class="text-xs px-3 py-1 rounded-full bg-surface-muted text-ink-muted hover:bg-primary-100 hover:text-primary-700 transition-colors duration-150">{{ tag }}</Link>
                    </div>
                </div>

                <!-- Card illustration (desktop) -->
                <div class="hidden lg:block flex-1 relative" style="perspective: 1200px">
                    <div class="relative w-full max-w-sm mx-auto h-[360px]">
                        <div class="absolute top-6 left-6 right-0 bg-white border border-[#e4ede8] rounded-2xl p-5 rotate-2 opacity-60 shadow-sm">
                            <div class="flex items-start gap-3">
                                <div class="w-11 h-11 rounded-xl bg-primary-50 flex items-center justify-center text-primary-500 font-semibold text-sm">DS</div>
                                <div><p class="text-sm font-semibold text-ink">Desain Studio</p><p class="text-xs text-ink-muted mt-0.5">Junior Arsitek</p></div>
                            </div>
                            <div class="mt-10 h-3 bg-surface-muted rounded-full w-3/4"></div>
                        </div>
                        <div class="absolute inset-x-0 top-0 bg-white border border-[#e4ede8] rounded-2xl p-5 shadow-md z-10">
                            <span class="absolute top-3 right-3 bg-primary-300 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">Baru</span>
                            <div class="flex items-start gap-3">
                                <div class="w-11 h-11 rounded-xl bg-primary-100 flex items-center justify-center text-primary-500 font-bold text-base">A</div>
                                <div><p class="text-base font-semibold text-ink">Senior Arsitek</p><p class="text-[13px] text-ink-muted mt-0.5">Arkindo Studio</p></div>
                            </div>
                            <div class="mt-3 flex items-center gap-3 text-xs text-ink-muted">
                                <span class="flex items-center gap-1">Jakarta Selatan</span>
                                <span>•</span>
                                <span>Remote OK</span>
                            </div>
                            <div class="mt-4 border-t border-[#e4ede8] pt-3 flex items-center justify-between">
                                <span class="text-xs font-medium px-3 py-1.5 rounded-lg border border-primary-300 text-primary-500">Lihat Lowongan</span>
                                <span class="text-[11px] text-ink-muted">2 jam lalu</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Bar -->
        <section class="bg-ink py-8">
            <div class="max-w-6xl mx-auto px-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-0 lg:divide-x lg:divide-white/10">
                    <div v-for="stat in stats" :key="stat[1]" class="text-center lg:px-8">
                        <div class="text-[32px] font-bold text-white">{{ stat[0] }}</div>
                        <div class="text-[13px] text-white/60 mt-1">{{ stat[1] }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Lowongan section -->
        <section class="py-20">
            <div class="max-w-6xl mx-auto px-6">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between mb-8">
                    <div>
                        <span class="inline-block bg-primary-100 text-primary-700 text-xs font-medium px-3 py-1 rounded-full mb-3">Lowongan Terbaru</span>
                        <h2 class="text-[32px] font-bold text-ink tracking-tight">Temukan posisi yang tepat untukmu</h2>
                    </div>
                    <Link :href="route('lowongan.index')" class="mt-4 sm:mt-0 text-sm text-primary-500 hover:text-primary-600 font-medium transition">
                        Lihat semua lowongan →
                    </Link>
                </div>

                <div class="flex gap-2 overflow-x-auto pb-1 mb-8 scrollbar-none">
                    <button v-for="tab in tabs" :key="tab" @click="activeTab = tab" :class="activeTab === tab ? 'bg-ink text-white' : 'bg-surface-muted text-ink-muted hover:text-ink'" class="text-[13px] px-4 py-2 rounded-full font-medium transition-colors">
                        {{ tab }}
                    </button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link v-for="(job, i) in dummyJobs" :key="i" :href="route('lowongan.show', { id: i+1 })" class="group block bg-white border border-[#e4ede8] rounded-xl overflow-hidden hover:shadow-md hover:border-primary-200 transition-all">
                        <div v-if="job.featured" class="bg-primary-300 text-white text-[10px] font-semibold text-center py-1 tracking-wide">Featured</div>
                        <div class="p-5">
                            <div class="flex items-start justify-between">
                                <div class="w-12 h-12 rounded-xl bg-primary-50 flex items-center justify-center text-primary-500 font-semibold text-base">{{ job.inisial }}</div>
                                <span :class="getBadgeColor(job.tipe)" class="text-xs px-2.5 py-0.5 rounded-full font-medium">{{ job.tipe }}</span>
                            </div>
                            <h3 class="mt-3 text-base font-semibold text-ink group-hover:text-primary-500 transition-colors">{{ job.posisi }}</h3>
                            <p class="text-[13px] text-ink-muted mt-0.5">{{ job.perusahaan }}</p>
                            <div class="mt-3 flex items-center justify-between">
                                <span class="text-sm font-medium">{{ job.gaji }}</span>
                                <span class="text-[11px] text-ink-muted">{{ job.waktu }}</span>
                            </div>
                        </div>
                    </Link>
                </div>
            </div>
        </section>

        <!-- How it works -->
        <section class="bg-surface-soft py-20">
            <div class="max-w-6xl mx-auto px-6">
                <div class="text-center mb-12">
                    <span class="inline-block bg-primary-100 text-primary-700 text-xs font-medium px-3 py-1 rounded-full mb-3">Cara Kerja</span>
                    <h2 class="text-[32px] font-bold text-ink">Cara kerja Loker Arsitek</h2>
                </div>
                <div class="flex justify-center gap-6 mb-10">
                    <button @click="caraKerjaRole = 'arsitek'" :class="caraKerjaRole === 'arsitek' ? 'text-ink border-primary-300' : 'text-ink-muted border-transparent'" class="pb-2 border-b-2 text-sm font-semibold transition-colors">Saya Arsitek</button>
                    <button @click="caraKerjaRole = 'perusahaan'" :class="caraKerjaRole === 'perusahaan' ? 'text-ink border-primary-300' : 'text-ink-muted border-transparent'" class="pb-2 border-b-2 text-sm font-semibold transition-colors">Saya Perusahaan</button>
                </div>

                <div v-show="caraKerjaRole === 'arsitek'" class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                    <div v-for="step in arsitekSteps" :key="step.num" class="bg-white rounded-xl border border-[#e4ede8] p-6 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-primary-300 text-white flex items-center justify-center font-bold text-base mb-4">{{ step.num }}</div>
                        <h3 class="text-base font-semibold text-ink mb-2">{{ step.title }}</h3>
                        <p class="text-sm text-ink-muted">{{ step.desc }}</p>
                    </div>
                </div>
                <div v-show="caraKerjaRole === 'perusahaan'" class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                    <div v-for="step in perusahaanSteps" :key="step.num" class="bg-white rounded-xl border border-[#e4ede8] p-6 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-primary-300 text-white flex items-center justify-center font-bold text-base mb-4">{{ step.num }}</div>
                        <h3 class="text-base font-semibold text-ink mb-2">{{ step.title }}</h3>
                        <p class="text-sm text-ink-muted">{{ step.desc }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA section -->
        <section class="bg-ink py-20 text-center">
            <div class="max-w-3xl mx-auto px-6">
                <h2 class="text-[32px] md:text-[40px] font-bold text-white">Siap memulai karirmu sebagai arsitek?</h2>
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
                    <Link :href="route('register')" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-primary-300 text-white font-semibold">Daftar sebagai Arsitek</Link>
                    <Link :href="route('register')" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-white/10 text-white font-medium border border-white/30">Post Lowongan</Link>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-ink border-t border-white/10 py-12">
            <div class="max-w-6xl mx-auto px-6">
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-10">
                    <div>
                        <Link :href="route('home')" class="flex items-center gap-2 mb-3">
                            <span class="text-lg font-bold text-white">Loker<span class="text-primary-300">Arsitek</span></span>
                        </Link>
                        <p class="text-sm text-white/60">Platform arsitek terpadu untuk Indonesia.</p>
                    </div>
                    <div>
                        <h4 class="text-[13px] font-semibold text-white uppercase tracking-wider mb-4">Platform</h4>
                        <ul class="space-y-2 text-sm text-white/60">
                            <li><Link :href="route('lowongan.index')">Lowongan Kerja</Link></li>
                            <li><Link :href="route('proyek.index')">Marketplace Proyek</Link></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
