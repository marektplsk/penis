<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
<body class="flex flex-col items-center">
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
                    <th class="border px-4 py-2">Description</th>
                    <th class="border px-4 py-2">Result</th>
                    <th class="border px-4 py-2">Risk</th>
                    <th class="border px-4 py-2">Risk Reward Ratio</th>
                    <th class="border px-4 py-2">Session</th>
                    <th class="border px-4 py-2">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($wins as $win)
                    <tr class="hover:bg-gray-100 cursor-pointer" onclick="window.location='{{ route('dashboard.show', ['id' => $win->id, 'from' => 'dashboard']) }}'">
                        <td class="border px-4 py-2">{{ $win->description }}</td>
                        <td class="border px-4 py-2">{{ $win->is_win ? 'Win' : 'Loss' }}</td>
                        <td class="border px-4 py-2">{{ $win->risk }}</td>
                        <td class="border px-4 py-2">{{ $win->risk_reward_ratio }}</td>
                        <td class="border px-4 py-2">{{ $win->hour_session }}</td>
                        <td class="border px-4 py-2">{{ $win->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Include the search bar -->
        @include('searchbar')

    </div>
</body>
</html>
