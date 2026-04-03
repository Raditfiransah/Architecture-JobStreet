<x-dashboard>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Selamat datang, {{ auth()->user()->companyProfile->company_name ?? auth()->user()->name }}.</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-stat-card title="Lowongan Aktif" value="0" color="blue" />
        <x-stat-card title="Total Pelamar" value="0" color="green" />
        <x-stat-card title="Lowongan Segera Expire" value="0" color="red" />
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Post Lowongan Pertama</h3>
                <p class="text-gray-500 mt-1">Mulai cari arsitek terbaik untuk perusahaan Anda.</p>
            </div>
            <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                Post Lowongan
            </a>
        </div>
    </div>

    <div class="bg-gray-50 rounded-xl border border-gray-200 p-6">
        <h3 class="text-sm font-medium text-gray-700">Paket Gratis</h3>
        <p class="text-2xl font-bold text-gray-900 mt-2">0/2 lowongan terpakai</p>
    </div>

    <x-empty-state title="Belum ada lowongan aktif" description="Buat lowongan pertama Anda untuk mulai menerima lamaran." :action="'#'" actionText="Post Lowongan" />
</x-dashboard>
