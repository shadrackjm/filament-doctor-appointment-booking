<x-layouts.app :title="__('Dashboard')">
        <div class="py-12 bg-gray-100">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <livewire:patient.featured-doctors :speciality_id="0" />
                    <livewire:patient.specialist-cards />
                    {{-- <livewire:recent-appointments /> --}}
                </div>
            </div>
        </div>
</x-layouts.app>