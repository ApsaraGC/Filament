{{-- resources/views/partials/schedules_table.blade.php --}}
<table class="min-w-full divide-y divide-gray-200 bg-white shadow-md rounded-lg">
    <thead class="bg-gray-100">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available From</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available To</th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @foreach ($schedules as $schedule)
            <tr>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->available_from->format('Y-m-d H:i') }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $schedule->available_to->format('Y-m-d H:i') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
