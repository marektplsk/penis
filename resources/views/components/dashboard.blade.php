<div class="mt-8">
    <h2 class="text-2xl font-bold mb-4">Dashboard</h2>
    
    <table class="min-w-full bg-white border border-gray-300 mt-8">
        <thead>
            <tr>
                <th class="border px-4 py-2">Description</th>
                <th class="border px-4 py-2">Result</th>
                <th class="border px-4 py-2">Risk</th>
                <th class="border px-4 py-2">Risk Reward Ratio</th>
                <th class="border px-4 py-2">Session</th>
                <th class="border px-4 py-2">Created At</th>
                <th class="border px-4 py-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wins as $win)
                <tr>
                    <td class="border px-4 py-2">{{ $win->description }}</td>
                    <td class="border px-4 py-2">{{ $win->is_win ? 'Win' : 'Loss' }}</td>
                    <td class="border px-4 py-2">{{ $win->risk }}</td>
                    <td class="border px-4 py-2">{{ $win->risk_reward_ratio }}</td>
                    <td class="border px-4 py-2">{{ $win->created_at }}</td>
                    <td class="border px-4 py-2">{{ $win->hour_session}}</td>
                    <td class="border px-4 py-2">
                        <form action="{{ route('wins.destroy', $win->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">X</button> <!-- Delete button -->
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
