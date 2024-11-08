<!-- show.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Win</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col items-center">
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Win Details</h1>

    <p>Description: {{ $win->description }}</p>
    <p>Result: {{ $win->is_win ? 'Win' : 'Loss' }}</p>
    <p>Risk: {{ $win->risk }}</p>
    <p>Risk Reward Ratio: {{ $win->risk_reward_ratio }}</p>
    <p>Created At: {{ $win->created_at }}</p>
    <p>Session: {{ $win->hour_session }}</p>
    <p><strong>Data:</strong> {{ $win->data }}</p>
    <p>Trade Type: {{ $win->trade_type }}</p>
    <p>Tags: {{ implode(', ', json_decode($win->tags, true) ?? []) }}</p>

    <!-- Edit Button with Pencil Icon -->
    <a href="{{ route('dashboard.edit', $win->id) }}" class="mt-4 px-4 py-2 bg-yellow-500 text-white rounded inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M17.414 2.586a2 2 0 010 2.828l-10 10A2 2 0 016.586 16H4a1 1 0 01-1-1v-2.586a2 2 0 01.586-1.414l10-10a2 2 0 012.828 0zM5 15h1.586L15 6.586 13.414 5 5 13.414V15z"/>
        </svg>
        Edit
    </a>

    <div class="mt-8">
        @if(request('from') === 'dashboard')
            <a href="{{ route('dashboard.show', $win->id) }}" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Back to Dashboard</a>
        @else
            <a href="{{ route('app.index') }}" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Back to Home</a>
        @endif
    </div>
</div>
</body>
</html>
