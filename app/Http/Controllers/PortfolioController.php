<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    // Display the list of portfolios
    public function index()
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('You must be logged in to view portfolio data.');
        }

        // Retrieve only the portfolios for the authenticated user
        $portfolios = Portfolio::where('user_id', Auth::id())->get();

        // Prepare data for the chart and list display
        $portfolioLabels = $portfolios->pluck('type')->toArray();
        $portfolioValues = $portfolios->pluck('amount')->toArray();
        $transactionTypes = $portfolios->pluck('transaction_type')->toArray(); // This will store 'gain' or 'loss'

        // Default empty data if no portfolios exist
        if (empty($portfolioLabels)) {
            $portfolioLabels = ['No investments'];
            $portfolioValues = [0];
            $transactionTypes = ['gain'];  // Default to 'gain' for a fallback chart
        }

        // Prepare the breadcrumbs
        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('app.index')],
            ['name' => 'Portfolio', 'url' => '']
        ];

        // Pass the data to the view
        return view('portfolio.portfolio', compact('portfolios', 'breadcrumbs', 'portfolioLabels', 'portfolioValues', 'transactionTypes'));
    }



    // Store a new portfolio item
    public function store(Request $request)
    {
        // Validate incoming request data
        $data = $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|string|max:255',
        ]);

        // Ensure the user is authenticated
        if (Auth::check()) {
            // Create a new portfolio item including user_id
            Portfolio::create([
                'amount' => $data['amount'],
                'type' => $data['type'],
                'user_id' => Auth::id(), // Add user_id explicitly
            ]);

            // Redirect to the portfolio index page after storing
            return redirect()->route('portfolio.index')->with('success', 'Portfolio item added successfully.');
        } else {
            // Handle the case when the user is not authenticated
            return redirect()->route('login')->withErrors('You must be logged in to add a portfolio item.');
        }
    }


    public function destroy($id)
    {
        // Ensure the user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors('You must be logged in to delete a portfolio item.');
        }

        // Find the portfolio item by ID
        $portfolio = Portfolio::where('user_id', Auth::id())->findOrFail($id);

        // Delete the portfolio item
        $portfolio->delete();

        // Redirect to the portfolio index page with a success message
        return redirect()->route('portfolio.index')->with('success', 'Portfolio item deleted successfully.');
    }

    public function getUserPortfolioData()
    {
        $portfolios = Portfolio::where('user_id', Auth::id())->get();

        $currentValue = $portfolios->sum('amount');
        $previousValue = $portfolios->where('transaction_type', 'loss')->sum('amount');

        $performance = $previousValue > 0 ? (($currentValue - $previousValue) / $previousValue) * 100 : 0;
        $trend = $performance > 0 ? 'gain' : 'loss';

        return [
            'currentValue' => $currentValue,
            'performance' => $performance,
            'trend' => $trend
        ];
    }


}
