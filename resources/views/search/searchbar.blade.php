<!-- Search Bar Modal -->
<div id="searchModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-md p-4 rounded-lg shadow-lg relative">
        <input type="text" 
               id="searchInput" 
               placeholder="Search..." 
               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500 transition duration-200"
               autofocus>
        
        <!-- Search Results -->
        <div id="searchResults" class="mt-2"></div>
    </div>
</div>

<style>
/* General styles for modal */
#searchModal {
    backdrop-filter: blur(10px); /* Optional: adds a blur effect to the background */
}

/* Input field styles */
input {
    color: transparent; /* Hide the text color */
    text-shadow: 0 0 0 black; /* Keep the text visible */
    font-size: 16px; /* Font size for input text */
}

/* Highlight selected text */
input::selection {
    background-color: rgba(251, 191, 36, 0.5); /* Highlight color for selected text */
}

/* Input field focus effect */
input:focus {
    box-shadow: 0 0 5px rgba(251, 191, 36, 0.5); /* Shadow effect on focus */
    border-color: rgba(251, 191, 36, 0.5); /* Border color on focus */
}

/* Search results styles */
#searchResults {
    max-height: 300px; /* Limit the height of results */
    overflow-y: auto; /* Scroll if too many results */
    border-radius: 0 0 8px 8px; /* Rounded corners for results */
    border: 1px solid #E0E0E0; /* Light border around results */
    background-color: white; /* Background for results */
}

/* Individual result item styles */
#searchResults div {
    padding: 12px; /* Padding for each result item */
    border-bottom: 1px solid #E0E0E0; /* Border between items */
    transition: background-color 0.2s; /* Transition effect for hover */
}

/* Hover effect for result items */
#searchResults div:hover {
    background-color: rgba(0, 0, 0, 0.05); /* Light gray on hover */
}

/* Font styling for results */
#searchResults a {
    text-decoration: none; /* Remove underline */
    color: #333; /* Dark text color */
    display: flex; /* Flexbox for icon and text */
    align-items: center; /* Center align items */
}

/* Icon styles (if any) */
#searchResults i {
    margin-right: 8px; /* Spacing between icon and text */
}
</style>

<script>
// Toggle the search modal visibility
function toggleSearch() {
    const modal = document.getElementById('searchModal');
    modal.classList.toggle('hidden');
    if (!modal.classList.contains('hidden')) {
        document.getElementById('searchInput').focus();
    }
}

// Close the search modal when ESC is pressed
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeSearch();
    }
    if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
        event.preventDefault();
        toggleSearch();
    }
});

// Close the search modal
function closeSearch() {
    document.getElementById('searchModal').classList.add('hidden');
    document.getElementById('searchInput').value = ''; // Clear input on close
    document.getElementById('searchResults').innerHTML = ''; // Clear search results
}

// Perform the search query
async function performSearch(query) {
    // Allow for 2 characters instead of 3
    if (query.length < 2) {
        document.getElementById('searchResults').innerHTML = ''; // Clear if less than 2 chars
        return;
    }

    try {
        const response = await fetch(`/search?q=${encodeURIComponent(query)}`); // Encode the query
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        const results = await response.json();
        displaySearchResults(results, query); // Pass query for highlighting
    } catch (error) {
        console.error("Search failed:", error);
        document.getElementById('searchResults').innerHTML = '<p class="text-red-500">An error occurred while searching. Please try again.</p>';
    }
}

// Update the input field with highlighted suggestion
function updateInputWithHighlight(query, suggestion) {
    const input = document.getElementById('searchInput');

    // Allow the user to clear the input completely
    if (query.length === 0) {
        input.value = ''; // Clear the input if query is empty
        return;
    }

    if (suggestion.toLowerCase().startsWith(query.toLowerCase())) {
        const remainingText = suggestion.slice(query.length); // Get the remaining part of the suggestion
        input.value = query + remainingText; // Set the input value to the query plus remaining text
        // Set selection range for highlighting effect
        input.setSelectionRange(query.length, input.value.length);
    } else {
        input.value = query; // If no suggestion, just show the query
        input.setSelectionRange(query.length, query.length); // Reset selection
    }
}

// Display search results with icons
function displaySearchResults(results, query) {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = ''; // Clear previous results

    if (results.length === 0) {
        resultsContainer.innerHTML = '<p class="text-gray-500">No results found</p>';
        // Update input to show just the query since there are no results
        const input = document.getElementById('searchInput');
        input.value = query; // Ensure input shows the current query
        input.setSelectionRange(query.length, query.length); // Reset selection
        return;
    }

    // Populate the results with clickable items
    results.forEach(result => {
        const resultItem = document.createElement('div');
        resultItem.classList.add('p-2', 'border-b', 'border-gray-200', 'hover:bg-gray-100', 'cursor-pointer');

        // Add icons based on result type
        let icon = '';
        if (result.type === 'container-title') {
            icon = '<i class="fas fa-newspaper text-blue-600 mr-2"></i>'; // Example for container title
        } else if (result.type === 'win-description') {
            icon = '<i class="fas fa-file-alt text-green-600 mr-2"></i>'; // Example for win description
        }

        // Set the click handler for the result item
        resultItem.innerHTML = `<a href="${result.url}" class="text-blue-600 hover:underline">${icon} ${escapeHtml(result.title)}</a>`;
        resultItem.onclick = () => {
            document.getElementById('searchInput').value = result.title; // Update input with selected title
            closeSearch(); // Optionally close the search modal
        };
        
        resultsContainer.appendChild(resultItem);
    });
}

// Escape HTML characters for safety
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Add event listener to the input for capturing input changes
document.getElementById('searchInput').addEventListener('input', function(event) {
    const query = this.value; // Get current value of input
    performSearch(query); // Perform search with current input value
});
</script>
