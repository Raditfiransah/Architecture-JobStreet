<x-dashboard>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}.</h1>
        <p class="text-gray-500 mt-1">Lengkapi profilmu untuk mulai melamar.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-stat-card title="Lamaran Dikirim" value="0" color="blue" />
        <x-stat-card title="Proposal Aktif" value="0" color="green" />
        <x-stat-card title="Profil Dilihat" value="0" color="purple" />
    </div>

    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-blue-900">Profil belum lengkap</h3>
                <p class="text-blue-700 mt-1">Lengkapi profil Anda untuk meningkatkan peluang mendapatkan proyek.</p>
            </div>
            <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                Lengkapi Sekarang
            </a>
        </div>
    </div>

    <x-empty-state title="Belum ada aktivitas" description="Aktivitas Anda akan muncul di sini setelah Anda mulai melamar proyek." />
</x-dashboard>
