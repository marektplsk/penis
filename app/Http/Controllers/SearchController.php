<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WinModel; // Import your WinModel

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Ensure the query has at least 3 characters
        if (strlen($query) < 3) {
            return response()->json([]); // Return empty results
        }

        // Perform the search in the wins table
        $results = WinModel::where('description', 'LIKE', "%{$query}%")
            ->orWhere('hour_session', 'LIKE', "%{$query}%") // Searching by hour_session if needed
            ->get(['id', 'description', 'hour_session']); // Customize fields as necessary

        // Format the results for the frontend
        $formattedResults = $results->map(function($win) {
            return [
                'title' => $win->description, // Display description as the title
                'url' => route('wins.show', $win->id), // Adjust the route to point to the detail view of Win
            ];
        });

        return response()->json($formattedResults);
    }
}

