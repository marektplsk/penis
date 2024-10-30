<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Splash Page')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Ensure the success message is positioned at the top */
        #success-message {
            transition: opacity 0.5s ease; /* Smooth transition for opacity */
        }
    </style>

    <script>
        // JavaScript to hide the success message after 3 seconds
        document.addEventListener('DOMContentLoaded', function () {
            const successMessage = document.getElementById('success-message');
            if (successMessage) {
                setTimeout(() => {
                    successMessage.style.opacity = '0'; // Fade out
                    setTimeout(() => {
                        successMessage.style.display = 'none'; // Remove from view after fade
                    }, 300); // Wait for fade out duration
                }, 3000); // 3 seconds delay before hiding
            }
        });
    </script>
</head>
<body class="flex">
    @include('_inc.sidebar') <!-- Include the sidebar -->

    <!-- Success message moved to the top -->
    @if (session('success'))
        <div id="success-message" class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex-1 container mx-auto p-4">
        @include('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs]) <!-- Include breadcrumbs here -->

        @include('components.win_form')

        <div class="mt-10"></div> <!-- 40 pixels space -->
        @include('_inc.charts')
        <div class="mt-10"></div> <!-- 40 pixels space -->

        @include('components.dashboardTable', ['wins' => $wins]) <!-- Pass the $wins variable -->

        <!-- Include the search bar -->
        @include('search.searchbar') <!-- Ensure the path to searchbar.blade.php is correct -->

    </div>
    
    <!-- Chart Scripts -->
    <script>
        // Sample data for Portfolio Chart
        const portfolioData = {
            labels: ['Investment 1', 'Investment 2', 'Investment 3', 'Investment 4'],
            datasets: [{
                label: 'Portfolio Value ($)',
                data: [5000, 15000, 10000, 25000], // Replace with your real portfolio data
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }]
        };

        const portfolioConfig = {
            type: 'doughnut',
            data: portfolioData,
            options: {}
        };

        const portfolioChart = new Chart(
            document.getElementById('portfolioChart'),
            portfolioConfig
        );

        // Win Rate Chart Data
        const winCount = {{ $wins->where('is_win', 1)->count() }};
        const lossCount = {{ $wins->where('is_win', 0)->count() }};
        const totalCount = winCount + lossCount; // Total trades
        const winRate = (winCount / totalCount) * 100 || 0; // Win rate in percentage
        const lossRate = (lossCount / totalCount) * 100 || 0; // Loss rate in percentage

        const winRateData = {
            labels: ['Win Rate', 'Loss Rate'],
            datasets: [{
                label: 'Win Rate (%)',
                data: [winRate, lossRate],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                ],
                borderWidth: 1,
            }]
        };

        const winRateConfig = {
            type: 'doughnut',
            data: winRateData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                const label = tooltipItem.label || '';
                                const value = tooltipItem.raw || 0; // Use raw value
                                return `${label}: ${value.toFixed(2)}%`; // Format value with 2 decimal places
                            }
                        }
                    }
                }
            }
        };

        // Create the win rate chart
        const winRateChart = new Chart(
            document.getElementById('winRateChart'),
            winRateConfig
        );

        // Risk Reward Chart Data
        const trades = @json($wins); // Pass trades data to JS

        let totalWinningAmount = 0;
        let totalLosingAmount = 0;

        // Define the amounts for calculations
        const amountPerPercent = 1; // Amount per 1%

        trades.forEach(trade => {
            const risk = trade.risk; // Percentage risk
            const riskRewardRatio = trade.risk_reward_ratio; // Risk reward ratio

            if (trade.is_win) {
                const winMultiplier = riskRewardRatio > 1 ? riskRewardRatio : 1; // Only use 1 for loss
                const overallWin = risk * winMultiplier; // Calculate total win
                totalWinningAmount += overallWin; // Aggregate total winning amount
            } else {
                const overallLoss = risk; // Loss calculated as is
                totalLosingAmount += overallLoss; // Aggregate total losing amount
            }
        });

        // Convert to dollar amounts
        const totalWinningAmountInDollars = totalWinningAmount * amountPerPercent;
        const totalLosingAmountInDollars = totalLosingAmount * amountPerPercent;

        // Data for the risk reward chart
        const riskRewardData = {
            labels: ['Total RR Wins', 'Total RR Losses'],
            datasets: [{
                label: 'Risk Reward Win Rate',
                data: [totalWinningAmountInDollars, totalLosingAmountInDollars],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Light teal for total wins
                    'rgba(255, 99, 132, 0.2)'  // Light red for total losses
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)', // Dark teal
                    'rgba(255, 99, 132, 1)'  // Dark red
                ],
                borderWidth: 1,
            }]
        };

        const riskRewardConfig = {
            type: 'doughnut',
            data: riskRewardData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    
                }
            }
        };

        // Create the risk reward chart
        const riskRewardChart = new Chart(
            document.getElementById('riskRewardChart'),
            riskRewardConfig
        );

        //Radar Chart
        const sessionData = {
            labels: ['New York AM', 'London', 'New York PM', 'Other', 'Asian'], // Use defined labels
            datasets: [{
                label: 'Session Counts',
                data: [
                    {{ $sessionData['NY AM'] }},
                    {{ $sessionData['London'] }},
                    {{ $sessionData['NY PM'] }},
                    {{ $sessionData['Other'] }},
                    {{ $sessionData['Asian'] }},
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1,
            }]
        };

        const sessionConfig = {
            type: 'radar',
            data: sessionData,
            options: {
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1 // Adjust this to set the step size of ticks on the radar chart
                        }
                    }
                }
            }
        };

        // Create the radar chart
        const sessionChart = new Chart(
            document.getElementById('sessionChart'),
            sessionConfig
        );
    </script>
</body>
</html>
