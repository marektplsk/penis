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

        // Prepare data for the chart
        $portfolioLabels = $portfolios->pluck('type')->toArray();
        $portfolioValues = $portfolios->pluck('amount')->toArray();

        if (empty($portfolioLabels)) {
            $portfolioLabels = ['No investments'];
            $portfolioValues = [0];
        }

        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('app.index')],
            ['name' => 'Portfolio', 'url' => '']
        ];

        return view('portfolio.portfolio', compact('portfolios', 'breadcrumbs', 'portfolioLabels', 'portfolioValues'));
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

    // Other methods (destroy, edit, update) remain unchanged
}
