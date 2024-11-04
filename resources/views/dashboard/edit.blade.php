<!-- edit.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trade</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col items-center">
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Edit Trade</h1>
        
        <form action="{{ route('dashboard.update', $win->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="text" name="description" value="{{ $win->description }}" placeholder="Description" class="border rounded p-2 w-full" required>
            <select name="is_win" class="border rounded p-2 w-full" required>
                <option value="1" {{ $win->is_win ? 'selected' : '' }}>Win</option>
                <option value="0" {{ !$win->is_win ? 'selected' : '' }}>Loss</option>
            </select>
            <input type="number" step="0.01" name="risk" value="{{ $win->risk }}" placeholder="Risk (%)" class="border rounded p-2 w-full" required>
            <input type="number" step="0.01" name="risk_reward_ratio" value="{{ $win->risk_reward_ratio }}" placeholder="Risk Reward Ratio (e.g., 2 for 1:2)" class="border rounded p-2 w-full" required>
            
            <select name="hour_session" class="border rounded p-2 w-full" required>
                <option value="Asian" {{ $win->hour_session == 'Asian' ? 'selected' : '' }}>Asian Session</option>
                <option value="London" {{ $win->hour_session == 'London' ? 'selected' : '' }}>London Session</option>
                <option value="New York" {{ $win->hour_session == 'New York' ? 'selected' : '' }}>New York Session</option>
                <option value="NY PM" {{ $win->hour_session == 'NY PM' ? 'selected' : '' }}>NY PM Session</option>
            </select>
            
            <button type="submit" class="bg-blue-500 text-white rounded p-2 w-full">Update</button>
        </form>
    </div>
</body>
</html>
