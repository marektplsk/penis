
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

        <div class="mt-8">
            @if(request('from') === 'dashboard')
                <a href="{{ route('dashboard') }}" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Back to Dashboard</a>
            @else
                <a href="{{ route('app.index') }}" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded">Back to Home</a>
            @endif
        </div>

        <!-- Include the search bar -->
        @include('searchbar')

    </div>
</body>
</html>
