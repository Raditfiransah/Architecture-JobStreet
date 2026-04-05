<script setup>
import { Link } from "@inertiajs/vue3";

const footerSections = [
    {
        title: "Platform",
        links: [
            { name: "Lowongan Kerja", route: "lowongan.index" },
            { name: "Proyek", route: "proyek.index" },
            { name: "Direktori Arsitek", route: "home" }, // Placeholder
            { name: "Info Hub", route: "info.index" },
        ]
    },
    {
        title: "Perusahaan",
        links: [
            { name: "Tentang Kami", route: "home" },
            { name: "Pasang Lowongan", route: "perusahaan.dashboard" },
            { name: "Pricelist", route: "home" },
        ]
    },
    {
        title: "Bantuan",
        links: [
            { name: "Pusat Bantuan", route: "home" },
            { name: "Kontak", route: "home" },
            { name: "Panduan", route: "home" },
        ]
    }
];
</script>

<template>
    <footer class="bg-white border-t border-[#e4ede8] py-16 md:py-24">
        <div class="max-w-[1440px] mx-auto px-4 md:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-12">
                <!-- Brand -->
                <div class="col-span-2">
                    <Link :href="route('home')" class="flex items-center gap-2 mb-6 group">
                        <span class="w-8 h-8 rounded-lg bg-[#00a032] flex items-center justify-center text-white shadow-lg shadow-[#00a032]/20 group-hover:scale-110 transition">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 15h-2v-6h2v6zm0-8h-2V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-6h2v6z"/>
                            </svg>
                        </span>
                        <span class="text-2xl font-black tracking-tight text-[#00a032]">LokerArsitek</span>
                    </Link>
                    <p class="text-ink-muted text-base max-w-sm leading-relaxed mb-8">
                        Platform karir dan kolaborasi terpadu untuk arsitek, desainer interior, dan profesional industri bangunan di Indonesia.
                    </p>
                    <div class="flex items-center gap-4">
                        <a href="#" class="w-10 h-10 rounded-full border border-[#e4ede8] flex items-center justify-center text-ink-muted hover:bg-[#00a032] hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full border border-[#e4ede8] flex items-center justify-center text-ink-muted hover:bg-[#00a032] hover:text-white transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Link Sections -->
                <div v-for="section in footerSections" :key="section.title">
                    <h4 class="text-ink font-bold uppercase tracking-widest text-[13px] mb-8">{{ section.title }}</h4>
                    <ul class="space-y-4">
                        <li v-for="link in section.links" :key="link.name">
                            <Link :href="route(link.route)" class="text-ink-muted hover:text-[#00a032] transition text-sm">
                                {{ link.name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Bottom -->
            <div class="mt-20 pt-8 border-t border-[#e4ede8] flex flex-col md:flex-row items-center justify-between gap-6 text-sm text-ink-muted">
                <p>&copy; 2024 Loker Arsitek Indonesia. Semua hak dilindungi.</p>
                <div class="flex items-center gap-8">
                    <a href="#" class="hover:text-ink transition">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-ink transition">Syarat & Ketentuan</a>
                </div>
            </div>
        </div>
    </footer>
</template>
