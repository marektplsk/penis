<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha384-O8Yy7xwAkT0n2hoYB8PB3zrV7syT/0J27JX30I6lf3D9s9huVfg6UWxgT+AkY6r8" crossorigin="anonymous">

    <style>
        #success-message {
            transition: opacity 0.5s ease;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0';
                    setTimeout(() => {
                        successMessage.style.display = 'none';
                    }, 300);
                }, 3000);
            }
        });
    </script>
</head>
<body class="flex items-start">
@include('_inc.sidebar')
<!-- Include breadcrumbs here -->
<div class="container mx-auto p-4">
    <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4" style="{{ session('success') ? '' : 'display:none;' }}">
        {{ session('success') }}
    </div>

    <nav class="sticky top-0 z-10 p-2 mb-4 bg-white bg-opacity-50 backdrop-blur">
        <ol class="list-reset flex text-xs">
            @foreach ($breadcrumbs as $breadcrumb)
                <li>
                    <a href="{{ $breadcrumb['url'] }}" class="text-gray-600 hover:text-gray-900 underline" style="color: #6A6A6A;">{{ $breadcrumb['name'] }}</a>
                </li>
                @if (!$loop->last)
                    <li class="mx-2">/</li>
                @endif
            @endforeach
        </ol>
    </nav>

    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    <a href="{{ route('app.index') }}" class="mb-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Back to Home</a>

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

    <!-- Include the search bar -->
    @include('search.searchbar')
    <i class="fas fa-newspaper"></i>
    <i class="fas fa-file-alt"></i>
</div>
</body>
</html>
