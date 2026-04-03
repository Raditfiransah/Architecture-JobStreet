<x-dashboard>
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Halo, {{ auth()->user()->name }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <x-stat-card title="Proyek Aktif" value="0" color="blue" />
        <x-stat-card title="Proposal Masuk" value="0" color="green" />
        <x-stat-card title="Proyek Selesai" value="0" color="purple" />
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">Post Proyek</h3>
                <p class="text-gray-500 mt-1">Buat proyek baru dan temukan arsitek yang tepat.</p>
            </div>
            <a href="#" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                Post Proyek
            </a>
        </div>
    </div>

    <x-empty-state title="Belum ada proyek" description="Buat proyek pertama Anda untuk mulai menerima proposal dari arsitek." :action="'#'" actionText="Post Proyek" />
</x-dashboard>
