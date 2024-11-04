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
        return view('portfolio.portfolio', compact('portfolios'));
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
}