<x-dashboard>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Admin</h1>
        <p class="text-gray-500 mt-1">Kelola platform Web Architect</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card title="Lowongan Pending Review" value="0" color="yellow" />
        <x-stat-card title="Proyek Pending" value="0" color="blue" />
        <x-stat-card title="Verifikasi Arsitek Pending" value="0" color="purple" />
        <x-stat-card title="User Baru Hari Ini" value="0" color="green" />
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900">Item Terbaru yang Butuh Aksi</h3>
        </div>
        <div class="p-6">
            <x-empty-state title="Tidak ada item yang perlu diaksi" description="Semua item sudah ditangani. Periksa kembali nanti." />
        </div>
    </div>
</x-dashboard>
