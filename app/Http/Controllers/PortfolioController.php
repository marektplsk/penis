<?php

namespace App\Http\Controllers;

use App\Models\Portfolio; // Ensure this matches the model class name
use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    // Display a listing of portfolios
    public function index()
    {
        $portfolios = Portfolio::all(); // Retrieve all portfolios

        // Define breadcrumbs
        $breadcrumbs = [
            ['name' => 'Home', 'url' => route('app.index')],
            ['name' => 'Portfolio', 'url' => ''] // Current page (no URL)
        ];
    
        // Prepare data for the chart
        $portfolioLabels = $portfolios->pluck('type')->toArray();
        $portfolioValues = $portfolios->pluck('amount')->toArray();
    
        // Check if portfolios exist, if not, provide default values
        if (empty($portfolioLabels)) {
            $portfolioLabels = ['No investments'];
            $portfolioValues = [0]; // Assuming no investments have zero value
        }
    
        return view('portfolio.portfolio', compact('portfolios', 'breadcrumbs', 'portfolioLabels', 'portfolioValues'));
    }

    // Store a newly created portfolio
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'amount' => 'required|numeric',
            'type' => 'required|string|max:255',
        ]);

        // Create a new portfolio entry
        Portfolio::create([
            'amount' => $request->amount,
            'type' => $request->type,
        ]);

        // Redirect back to the portfolio page
        return redirect()->route('portfolio.index')->with('success', 'Portfolio item added successfully.');
    }

    public function destroy($id)
    {
    // Find the portfolio entry by ID and delete it
    $portfolio = Portfolio::findOrFail($id);
    $portfolio->delete();

    // Redirect back to the portfolio page with a success message
    return redirect()->route('portfolio.index')->with('success', 'Portfolio item deleted successfully.');
    }

    public function edit($id)
    {
    $portfolio = Portfolio::findOrFail($id); // Find the portfolio by ID
    return view('portfolio.edit', compact('portfolio')); // Return the edit view with the portfolio data
    }

    // Update the specified portfolio in storage
    public function update(Request $request, $id)
    {
    // Validate incoming request data
    $request->validate([
        'amount' => 'required|numeric',
        'type' => 'required|string|max:255',
    ]);

    // Find the portfolio entry by ID and update it
    $portfolio = Portfolio::findOrFail($id);
    $portfolio->update([
        'amount' => $request->amount,
        'type' => $request->type,
    ]);

    // Redirect back to the portfolio page with a success message
    return redirect()->route('portfolio.index')->with('success', 'Portfolio item updated successfully.');
    }
}