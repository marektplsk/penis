<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WinModel; // Ensure this model is correctly defined
use Illuminate\Support\Facades\DB;
use App\Models\Portfolio;

class WinController extends Controller
{
    // Display the list of wins
    public function index()
    {
        $wins = WinModel::all();
        $chartData = session('chartData', [
            'winPoints' => 0,
            'lossPoints' => 0,
        ]);

        // New session data processing
        $possibleSessions = ['NY AM', 'London', 'NY PM', 'Other', 'Asian'];
        $sessionCounts = $wins->groupBy('hour_session')->map->count();

        $sessionData = [];
        foreach ($possibleSessions as $session) {
            $sessionData[$session] = $sessionCounts->get($session, 0);
        }

        \Log::info($sessionData); // Log sessionCounts to see the output

        $breadcrumbs = [
            ['name' => 'Home', 'url' => ''], // Home will be the current page, no URL needed
        ];

        // Fetch portfolio data
        $portfolios = Portfolio::all(); // Retrieve all portfolios

        // Check if portfolios are fetched
        if ($portfolios->isEmpty()) {
            \Log::info('No portfolios found'); // Log if no portfolios
            $portfolioLabels = [];
            $portfolioValues = [];
        } else {
            $portfolioLabels = $portfolios->pluck('type')->toArray(); // Get labels from portfolio
            $portfolioValues = $portfolios->pluck('amount')->toArray(); // Get values from portfolio
            
            // Log the portfolio labels and values
            \Log::info('Portfolio Labels: ', $portfolioLabels);
            \Log::info('Portfolio Values: ', $portfolioValues);
        }

        return view('app', compact('wins', 'chartData', 'sessionData', 'breadcrumbs', 'portfolioLabels', 'portfolioValues')); // Pass data to the view
    }

    // Store a new win record
    public function store(Request $request)
    {
        // Validate incoming request data
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'is_win' => 'required|boolean',
            'risk' => 'required|numeric|min:0',
            'risk_reward_ratio' => 'required|numeric|min:0',
            'hour_session' => 'required|string|max:50', // Validate hour_session
        ]);

        // Create a new win record using the validated data
        WinModel::create([
            'description' => $data['description'],
            'is_win' => $data['is_win'],
            'risk' => $data['risk'],
            'risk_reward_ratio' => $data['risk_reward_ratio'],
            'created_at' => now(),
            'updated_at' => now(),
            'hour_session' => $data['hour_session'],
        ]);

        return redirect()->route('app.index'); // Redirect to the index page after storing
    }

    // Calculate win and loss points for chart data
    private function calculateChartData()
    {
        $wins = WinModel::all(); // Fetch all wins from the database
        $winPoints = 0;
        $lossPoints = 0;

        // Calculate win and loss points
        foreach ($wins as $trade) {
            $risk = $trade->risk;
            $rewardRatio = $trade->risk_reward_ratio;

            if ($trade->is_win) {
                $winPoints += $risk * $rewardRatio; // Calculate win points
            } else {
                $lossPoints += $risk; // Calculate loss points
            }
        }

        return [
            'winPoints' => $winPoints,
            'lossPoints' => $lossPoints,
        ];
    }

    // Delete a win record and log it in win_history
    public function destroy($id)
    {
        $win = WinModel::findOrFail($id); // Find the win record by ID

        // Insert the deleted record into win_history
        DB::table('win_history')->insert([
            'description' => $win->description,
            'is_win' => $win->is_win,
            'risk' => $win->risk,
            'risk_reward_ratio' => $win->risk_reward_ratio,
            'hour_session' => $win->hour_session, // Correctly reference hour_session from the $win object
            'created_at' => $win->created_at,
            'deleted_at' => now(), // Log the deletion timestamp
        ]);

        $win->delete(); // Delete the original win record

        return redirect()->route('app.index')->with('success', 'Record deleted successfully.'); // Redirect with success message
    }
    
    public function dashboard()
    {
        $wins = WinModel::all(); // Fetch all wins for the dashboard

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('app.index')],
            ['name' => 'Dashboard', 'url' => ''] // No trade ID here
        ];

        return view('dashboard.dashboard', compact('wins', 'breadcrumbs'));
    }
    
    public function show($id)
    {
        // Fetch the trade details
        $win = WinModel::findOrFail($id);

        // Initialize breadcrumbs
        $breadcrumbs = [];

        // Add Home breadcrumb
        $breadcrumbs[] = ['name' => 'Home', 'url' => route('app.index')];

        // Add Dashboard breadcrumb
        if (session('previous_route') === 'dashboard') {
            $breadcrumbs[] = ['name' => 'Dashboard', 'url' => route('dashboard')];
        }

        // Add current trade
        $currentRoute = ['name' => 'Trade ' . $win->id, 'url' => route('dashboard.show', ['id' => $win->id])];
        $breadcrumbs[] = $currentRoute;

        // Save the current route in session for navigation
        session(['previous_route' => request('from')]);

        // Return the view with the win details and breadcrumbs
        return view('dashboard.show', compact('win', 'breadcrumbs'));
    }
}
