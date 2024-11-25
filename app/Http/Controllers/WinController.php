<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WinModel;
use Illuminate\Support\Facades\DB;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import the Log facade
use App\Models\Tag;

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
            'pair' => 'required|string|max:20', // Validate the pair field
            'is_win' => 'required|boolean',
            'risk' => 'required|numeric|min:0',
            'risk_reward_ratio' => 'required|numeric|min:0',
            'data' => 'required|string|max:255',
            'trade_type' => 'required|string|in:short,long',
            'tags' => 'nullable|string', // Validate tags as a JSON string
            'hour_session' => 'required|string|max:50',
        ]);

        // Check if the user is authenticated
        if (Auth::check()) {
            // Decode the tags JSON string
            $tags = json_decode($data['tags'], true);

            // Create a new win record including user_id
            $win = WinModel::create([
                'description' => $data['description'],
                'pair' => $data['pair'], // Save the pair
                'is_win' => $data['is_win'],
                'risk' => $data['risk'],
                'risk_reward_ratio' => $data['risk_reward_ratio'],
                'hour_session' => $data['hour_session'],
                'user_id' => Auth::id(), // Add user_id explicitly
                'data' => $data['data'],
                'trade_type' => $data['trade_type'],
                'tags' => json_encode($tags), // Save tags as a JSON string
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    Tag::firstOrCreate(['name' => $tag]);
                }
            }



            Log::info('Win created successfully', ['win' => $win]);

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

    // Update a win recordwe
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'description' => 'required|string|max:255',
            'pair' => 'required|string|max:20',
            'is_win' => 'required|boolean',
            'risk' => 'required|numeric|min:0',
            'risk_reward_ratio' => 'required|numeric|min:0',
            'hour_session' => 'required|string|max:50',
            'data' => 'required|string|max:255',
            'trade_type' => 'required|string|in:short,long',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',



        ]);

        $win = WinModel::findOrFail($id);
        $win->update($data);

        return redirect()->route('dashboard.show', $win->id)->with('success', 'Trade updated successfully.');
    }

    public function getTags()
    {
        $tags = Tag::all();
        return response()->json($tags);
    }

    public function storeTag(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Save the tag to the database
        DB::table('tags')->updateOrInsert(['name' => $request->name]);

        return response()->json(['success' => true, 'tag' => $request->name]);
    }

    public function getUserTradeData($user)
    {
        $wins = WinModel::where('user_id', $user->id)->get();

        // Calculate win rate
        $winsCount = $wins->where('is_win', 1)->count();
        $totalTrades = $wins->count();
        $winRate = $totalTrades > 0 ? ($winsCount / $totalTrades) * 100 : 0;

        // Analyze tag usage
        $tagUsage = $wins->groupBy('tags');
        $underUsedTags = [];
        foreach ($tagUsage as $tag => $tagTrades) {
            $tagWinRate = $tagTrades->where('is_win', 1)->count() / $tagTrades->count() * 100;
            if ($tagWinRate > 50) {
                $underUsedTags[] = ['tag' => $tag, 'winRate' => $tagWinRate];
            }
        }

        // Session data
        $sessions = $wins->groupBy('hour_session')->map->count();

        // Extract emotional patterns from the 'data' field
        $emotions = [];
        foreach ($wins as $win) {
            if (str_contains($win->data, 'stress')) {
                $emotions[] = 'stressful';
            }
            if (str_contains($win->data, 'confidence')) {
                $emotions[] = 'confident';
            }
        }

        return [
            'winRate' => $winRate,
            'underUsedTags' => $underUsedTags,
            'sessionData' => $sessions,
            'emotions' => $emotions
        ];
    }



}
