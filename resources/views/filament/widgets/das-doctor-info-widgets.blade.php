<x-filament-widgets::widget>
    <x-filament::section>
        <div class="custom-widget bg-cover bg-center p-6 rounded-lg shadow-md">
            <h2>𝓗𝓮𝓵𝓵𝓸 𝓓𝓸𝓬𝓽𝓸𝓻</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach ($this->getStats() as $stat)
                    <div class="stat-card p-4 bg-white bg-opacity-80 rounded-lg shadow-md">
                        <h3 class="text-lg font-bold">{{ $stat->getLabel() }}</h3>
                        <p class="text-4xl font-extrabold text-{{ $stat->getColor() }}">{{ $stat->getValue() }}</p>
                        <p class="text-sm text-gray-700">{{ $stat->getDescription() }}</p>
                        {{-- Include chart or additional information if needed --}}
                    </div>
                @endforeach
            </div>
        </div>
        <style>
            .custom-widget {
                background-image: url('images/hospital.jpg');
                height: 300px;


    padding: 2.5rem;
    border-radius: 0.75rem;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }
            .stat-card {
                background-color: rgba(255, 255, 255, 0.8); /* White background with some transparency */
                border-radius: 10px;
               padding: 30px;

            }
            h2{
    margin-top: 10px;
    font-size: 60px;
    color:rgb(210, 67, 210);
    text-align: center
}

        </style>

    </x-filament::section>
</x-filament-widgets::widget>
