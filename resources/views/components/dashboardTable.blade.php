<div class="mt-8">
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>

    <table class="min-w-full bg-white border border-gray-300 mt-8">
        <thead>
        <tr>
            <th class="border px-4 py-2">Name</th>
            <th class="border px-4 py-2">Pair</th>
            <th class="border px-4 py-2">Result</th>
            <th class="border px-4 py-2">Risk</th>
            <th class="border px-4 py-2">Risk Reward Ratio</th>
            <th class="border px-4 py-2">Created At</th>
            <th class="border px-4 py-2">Session</th>
            <th class="border px-4 py-2">Data</th>
            <th class="border px-4 py-2">Trade Type</th>
            <th class="border px-4 py-2">Tags</th>
            <th class="border px-4 py-2">Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($wins as $win)
            <tr class="cursor-pointer hover:bg-gray-100" onclick="window.location='{{ route('dashboard.show', $win->id) }}'">
                <td class="border px-4 py-2">{{ $win->description }}</td>
                <td class="border px-4 py-2">{{ $win->pair }}</td>
                <td class="border px-4 py-2">{{ $win->is_win ? 'Win' : 'Loss' }}</td>
                <td class="border px-4 py-2">{{ $win->risk }}</td>
                <td class="border px-4 py-2">{{ $win->risk_reward_ratio }}</td>
                <td class="border px-4 py-2">{{ $win->created_at }}</td>
                <td class="border px-4 py-2">{{ $win->hour_session }}</td>
                <td class="border px-4 py-2">{{ $win->data }}</td>
                <td class="border px-4 py-2">{{ $win->trade_type }}</td>
                <td class="border px-4 py-2">{{ implode(', ', json_decode($win->tags, true) ?? []) }}</td>
                <td class="border px-4 py-2">
                    <form action="{{ route('wins.destroy', $win->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');" class="inline-block">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500 hover:text-red-700 py-2 px-4" onclick="event.stopPropagation();">X</button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <div class="container mx-auto p-4 flex-grow py-8">
        <a href="{{ route('dashboard')}}" class="mt-4 px-4 py-4">Go to dashboard -></a>
    </div>
</div>
