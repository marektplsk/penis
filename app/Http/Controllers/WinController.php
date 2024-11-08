<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WinModel;
use Illuminate\Support\Facades\DB;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag; // Add a Tag model if you don't have one yet


class WinController extends Controller
{
    // Apply the auth middleware only to the 'store' method

    // Display the list of wins
    public function index()
    {
        // Get only the wins related to the authenticated user
        $wins = WinModel::where('user_id', Auth::id())->get();

        // Initialize chart data
        $chartData = session('chartData', [
            'winPoints' => 0,
            'lossPoints' => 0,
        ]);

        // Process session data
        $possibleSessions = ['NY AM', 'London', 'NY PM', 'Other', 'Asian'];
        $sessionCounts = $wins->groupBy('hour_session')->map->count();

        $sessionData = [];
        foreach ($possibleSessions as $session) {
            $sessionData[$session] = $sessionCounts->get($session, 0);
        }

        $breadcrumbs = [
            ['name' => 'Home', 'url' => ''],
        ];

        // Fetch portfolio data only if the user is authenticated
        if (Auth::check()) {
            $portfolios = Portfolio::where('user_id', Auth::id())->get();
            if ($portfolios->isEmpty()) {
                $portfolioLabels = [];
                $portfolioValues = [];
            } else {
                $portfolioLabels = $portfolios->pluck('type')->toArray();
                $portfolioValues = $portfolios->pluck('amount')->toArray();
            }
        } else {
            $portfolioLabels = [];
            $portfolioValues = [];
        }

        return view('app', compact('wins', 'chartData', 'sessionData', 'breadcrumbs', 'portfolioLabels', 'portfolioValues'));
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
            'hour_session' => 'required|string|max:50',
            'user_id' => Auth::id(),
            'data' => 'required|string|max:255',
            'trade_type' => 'required|string|in:short,long',




        ]);

        // Check if the user is authenticated
        if (Auth::check()) {
            // Create a new win record including user_id
            WinModel::create([
                'description' => $data['description'],
                'is_win' => $data['is_win'],
                'risk' => $data['risk'],
                'risk_reward_ratio' => $data['risk_reward_ratio'],
                'hour_session' => $data['hour_session'],
                'user_id' => Auth::id(), // Add user_id explicitly
                'data' => $data['data'],
                'trade_type' => $data['trade_type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Redirect to the index page after storing
            return redirect()->route('app.index');
        } else {
            // Handle the case when the user is not authenticated
            return redirect()->route('login')->withErrors('You must be logged in to create a win record.');
        }
    }

    // Calculate win and loss points for chart data
    private function calculateChartData()
    {
        // Only get the wins related to the authenticated user
        $wins = WinModel::where('user_id', Auth::id())->get();
        $winPoints = 0;
        $lossPoints = 0;

        foreach ($wins as $trade) {
            $risk = $trade->risk;
            $rewardRatio = $trade->risk_reward_ratio;

            if ($trade->is_win) {
                $winPoints += $risk * $rewardRatio;
            } else {
                $lossPoints += $risk;
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
        $win = WinModel::findOrFail($id);

        // Insert the deleted record into win_history
        DB::table('win_history')->insert([
            'description' => $win->description,
            'is_win' => $win->is_win,
            'risk' => $win->risk,
            'risk_reward_ratio' => $win->risk_reward_ratio,
            'hour_session' => $win->hour_session,
            'created_at' => $win->created_at,
            'deleted_at' => now(),
        ]);

        $win->delete();

        return redirect()->route('app.index')->with('success', 'Record deleted successfully.');
    }

    // Dashboard view
    public function dashboard()
    {
        // Only get the wins related to the authenticated user
        $wins = WinModel::where('user_id', Auth::id())->get();
        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('app.index')],
            ['name' => 'Dashboard', 'url' => '']
        ];

        return view('dashboard.dashboard', compact('wins', 'breadcrumbs'));
    }

    // Show a specific win record
    public function show($id)
    {
        $win = WinModel::findOrFail($id);
        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('app.index')],
        ];

        if (session('previous_route') === 'dashboard') {
            $breadcrumbs[] = ['name' => 'Dashboard', 'url' => route('dashboard')];
        }

        $breadcrumbs[] = ['name' => 'Trade ' . $win->id, 'url' => route('dashboard.show', ['id' => $win->id])];

        session(['previous_route' => request('from')]);

        return view('dashboard.show', compact('win', 'breadcrumbs'));
    }

    // Edit a specific win record
    public function edit($id)
    {
        $win = WinModel::findOrFail($id);
        return view('dashboard.edit', compact('win'));
    }

    // Update a win record
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'is_win' => 'required|boolean',
            'risk' => 'required|numeric|min:0',
            'risk_reward_ratio' => 'required|numeric|min:0',
            'hour_session' => 'required|string|max:50','data' => 'required|string|max:255', // Add this line
            'trade_type' => 'required|string|in:short,long',

        ]);

        $win = WinModel::findOrFail($id);
        $win->update($data);

        return redirect()->route('app.index')->with('success', 'Trade updated successfully.');
    }

    public function getTags()
    {
        // Fetch all tags and return as JSON
        $tags = Tag::all(['id', 'name']); // Ensure you have a 'Tag' model with 'id' and 'name' fields
        return response()->json($tags);
    }
    public function deleteTag($id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();

            return response()->json(['success' => true, 'message' => 'Tag deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Tag deletion failed.'], 500);
        }
    }
}
