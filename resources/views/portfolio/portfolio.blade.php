<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="flex">
    <!-- Sidebar -->
    @include('_inc.sidebar')

    <!-- Main content -->
    <main class="w-3/4 p-4">
        <h1 class="text-2xl font-bold mb-4">Portfolio</h1>

        <!-- Breadcrumbs -->
        @include('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs]) <!-- Include breadcrumbs -->

        <!-- Search Bar -->
        @include('search.searchbar') <!-- Include the search bar partial -->

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('portfolio.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
                    <input type="number" name="amount" id="amount" placeholder="Enter amount" 
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-500" required>
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
                    <input type="text" name="type" id="type" placeholder="Enter type (e.g., stocks, bonds)" 
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-500" required>
                </div>

                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 rounded-md">
                    Add to Portfolio
                </button>
            </form>
        </div>

        <h2 class="text-xl font-semibold mt-6">Portfolio Items</h2>
            <ul id="portfolioList" class="mt-4">
                @foreach($portfolios as $portfolio)
                    <li class="bg-gray-200 p-4 mb-2 rounded-md flex justify-between items-center">
                        <span>
                            Amount: ${{ number_format($portfolio->amount, 2) }} | Type: {{ $portfolio->type }}
                        </span>
                        <form action="{{ route('portfolio.destroy', $portfolio->id) }}" method="POST" class="ml-4">
                            @csrf
                            @method('DELETE') <!-- Specify the DELETE method -->
                            <button type="submit" class="text-red-600 hover:text-red-800">
                                X
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>

        <!-- Portfolio Chart -->
        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-2 text-center">Portfolio Value</h2>
            <!-- Wrap canvas in a div to control size and responsiveness -->
            <div class="flex justify-center items-center" style="max-height: 200px;">
                <canvas id="portfolioChart" width="150" height="150" class="mx-auto"></canvas>
            </div>
        </div>
    </main>

    <!-- Search Bar Modal -->
    

    <!-- Include your JavaScript code for search here -->
    <script>
        // Chart.js configuration
        const portfolioLabels = @json($portfolioLabels);
        const portfolioValues = @json($portfolioValues);

        const portfolioData = {
            labels: portfolioLabels,
            datasets: [{
                label: 'Portfolio Value ($)',
                data: portfolioValues,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }]
        };

        const portfolioConfig = {
            type: 'doughnut',
            data: portfolioData,
            options: {
                responsive: true, // Ensure chart is responsive
                maintainAspectRatio: false, // Allow width/height to change
            }
        };

        const portfolioChart = new Chart(
            document.getElementById('portfolioChart'),
            portfolioConfig
        );
    </script>
</body>
</html>
