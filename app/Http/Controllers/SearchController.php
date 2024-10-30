<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WinModel;

class SearchController extends Controller
{
    public function search(Request $request)
{
    $query = $request->input('q');

    // Allow search queries with at least 2 characters
    if (strlen($query) < 2) {
        return response()->json([]); // Return empty results if less than 2 characters
    }

    // Perform the search in the wins table
    $results = WinModel::whereRaw('LOWER(description) LIKE ?', ["%{$query}%"])
        ->orWhereRaw('LOWER(hour_session) LIKE ?', ["%{$query}%"])
        ->get(['id', 'description', 'hour_session']); // Customize fields as necessary

    // Prepare formatted results
    $formattedResults = [];

    // Add hardcoded container titles for the search
    $containerTitles = [
        'Dashboard' => route('dashboard'), // Change this to your actual dashboard route
        'Wins List' => route('wins.index'), // Change this to your actual wins list route
    ];

    // Check against container titles
    foreach ($containerTitles as $title => $url) {
        if (stripos($title, $query) !== false) {
            $formattedResults[] = [
                'title' => $title,
                'url' => $url,
                'type' => 'container-title', // Mark this as a container title
            ];
        }
    }

    // Add search results from WinModel
    foreach ($results as $win) {
        $formattedResults[] = [
            'title' => $win->description,
            'url' => route('wins.show', $win->id), // Adjust this to your detail view route
            'type' => 'win-description', // Mark this as a win description
        ];
    }

    return response()->json($formattedResults);
}

}