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
    @include('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs])

    <!-- Search Bar -->
    @include('search.searchbar')

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

            <!-- Gain/Loss Selection -->
            <div class="mb-4">
                <span class="block text-sm font-medium text-gray-700">Transaction Type:</span>
                <div class="flex items-center mt-2">
                    <label class="inline-flex items-center mr-6">
                        <input type="radio" name="transaction_type" value="gain" class="form-radio text-green-500" required>
                        <span class="ml-2">Gain</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="transaction_type" value="loss" class="form-radio text-red-500" required>
                        <span class="ml-2">Loss</span>
                    </label>
                </div>
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
                @if ($portfolio->transaction_type == 'gain')
                    | <span class="text-green-500">Gain</span>: ${{ number_format($portfolio->amount, 2) }}
                @elseif ($portfolio->transaction_type == 'loss')
                    | <span class="text-red-500">Loss</span>: ${{ number_format($portfolio->amount, 2) }}
                @endif
            </span>
                <div class="flex items-center">
                    <a href="{{ route('portfolio.edit', $portfolio->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded mr-4">
                        Edit
                    </a>
                    <form action="{{ route('portfolio.destroy', $portfolio->id) }}" method="POST" class="ml-4">
                        @csrf
                        @method('DELETE') <!-- Specify the DELETE method -->
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            X
                        </button>
                    </form>
                </div>
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

<!-- Include your JavaScript code for chart here -->
<script>
    const portfolioLabels = @json($portfolioLabels); // Portfolio types (e.g., 'stocks', 'bonds')
    const portfolioValues = @json($portfolioValues); // Portfolio amounts (e.g., 100, 200)
    const transactionTypes = @json($transactionTypes); // Gain or loss types ('gain', 'loss')

    // Set colors based on transaction type (green for gain, red for loss)
    const portfolioColors = transactionTypes.map(type =>
        type === 'gain' ? 'rgba(75, 192, 92, 0.2)' : 'rgba(255, 99, 132, 0.2)' // Green for gain, Red for loss
    );

    // Border colors for the chart (solid border color based on type)
    const portfolioBorderColors = transactionTypes.map(type =>
        type === 'gain' ? 'rgba(75, 192, 92, 1)' : 'rgba(255, 99, 132, 1)' // Green for gain, Red for loss
    );

    const portfolioData = {
        labels: portfolioLabels,
        datasets: [{
            label: 'Portfolio Value ($)',
            data: portfolioValues,
            backgroundColor: portfolioColors, // Dynamic background color
            borderColor: portfolioBorderColors, // Border color based on transaction type
            borderWidth: 1,
        }]
    };

    const portfolioConfig = {
        type: 'doughnut', // Doughnut chart type
        data: portfolioData,
        options: {
            responsive: true, // Ensure chart is responsive
            maintainAspectRatio: false, // Allow width/height to change
        }
    };

    // Render the chart
    const portfolioChart = new Chart(
        document.getElementById('portfolioChart'),
        portfolioConfig
    );
</script>

</body>
</html>
