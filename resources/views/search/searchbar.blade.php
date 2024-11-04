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
    position: relative;
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

/* Highlight the filled-in portion */
input::after {
    content: attr(data-highlight);
    color: rgba(251, 191, 36, 0.5); /* Highlight color */
    position: absolute;
    left: 0;
    top: 0;
    pointer-events: none;
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

<!-- HTML structure and styles remain the same -->



<script>
let currentIndex = -1; // Track the selected result

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
    currentIndex = -1; // Reset index
}

// Perform the search query
async function performSearch(query) {
    if (query.length < 2) {
        document.getElementById('searchResults').innerHTML = ''; // Clear if less than 2 chars
        return;
    }

    try {
        const response = await fetch(`/search?q=${encodeURIComponent(query)}`);
        if (!response.ok) {
            throw new Error("Network response was not ok");
        }
        const results = await response.json();
        displaySearchResults(results, query);
    } catch (error) {
        console.error("Search failed:", error);
        document.getElementById('searchResults').innerHTML = '<p class="text-red-500">An error occurred while searching. Please try again.</p>';
    }
}

// Update input with highlighted suggestion
function updateInputWithHighlight(query, suggestion) {
    const input = document.getElementById('searchInput');
    if (query.length === 0 || !suggestion) {
        input.value = query;
        input.setAttribute('data-highlight', '');
        return;
    }

    if (suggestion.toLowerCase().startsWith(query.toLowerCase())) {
        const remainingText = suggestion.slice(query.length);
        input.value = query + remainingText;
        input.setSelectionRange(query.length, input.value.length);
        input.setAttribute('data-highlight', remainingText);
    } else {
        input.value = query;
        input.setAttribute('data-highlight', '');
    }
}

// Display search results in categories
function displaySearchResults(results, query) {
    const resultsContainer = document.getElementById('searchResults');
    resultsContainer.innerHTML = '';

    currentIndex = -1; // Reset index when new results are shown

    if (results.length === 0) {
        resultsContainer.innerHTML = '<p class="text-gray-500">No results found</p>';
        return;
    }

    // Group results by type (e.g., Pages, Trades)
    const groupedResults = {
        "Pages": results.filter(result => result.type === 'container-title'),
        "Trades": results.filter(result => result.type === 'win-description')
    };

    Object.keys(groupedResults).forEach(section => {
        const sectionHeader = document.createElement('div');
        sectionHeader.classList.add('font-semibold', 'text-gray-700', 'mt-4', 'mb-2');
        sectionHeader.innerText = section;
        resultsContainer.appendChild(sectionHeader);

        groupedResults[section].forEach(result => {
            const resultItem = document.createElement('div');
            resultItem.classList.add('p-2', 'border-b', 'border-gray-200', 'hover:bg-gray-100', 'cursor-pointer', 'result-item');

            let icon = '';
            if (section === "Pages") {
                icon = '<i class="fas fa-newspaper text-blue-600 mr-2"></i>';
            } else if (section === "Trades") {
                icon = '<i class="fas fa-file-alt text-green-600 mr-2"></i>';
            }

            resultItem.innerHTML = `<a href="${result.url}" class="text-blue-600 hover:underline">${icon} ${escapeHtml(result.title)}</a>`;
            resultItem.onclick = () => {
                updateInputWithHighlight(query, result.title);
                closeSearch();
            };

            resultsContainer.appendChild(resultItem);
        });
    });

    // Autocomplete and highlight the first result
    if (results.length > 0) {
        updateInputWithHighlight(query, results[0].title);
    }
}

// Select a result by index
function selectResult(index) {
    const items = document.querySelectorAll('.result-item');
    items.forEach((item, i) => {
        item.classList.toggle('bg-gray-100', i === index);
    });
    currentIndex = index;

    if (currentIndex >= 0) {
        const selectedItem = items[currentIndex].querySelector('a');
        document.getElementById('searchInput').value = selectedItem.textContent;
    }
}

// Capture arrow key navigation and Enter selection
document.getElementById('searchInput').addEventListener('keydown', function(event) {
    const items = document.querySelectorAll('.result-item');

    if (event.key === 'ArrowDown') {
        event.preventDefault();
        currentIndex = (currentIndex + 1) % items.length; // Navigate down
        selectResult(currentIndex);
    } else if (event.key === 'ArrowUp') {
        event.preventDefault();
        currentIndex = (currentIndex - 1 + items.length) % items.length; // Navigate up
        selectResult(currentIndex);
    } else if (event.key === 'Enter') {
        event.preventDefault();
        // If there are results and none is selected, select the first item
        if (items.length > 0) {
            if (currentIndex === -1) {
                currentIndex = 0; // Always select the first item on Enter if none is selected
            }
            selectResult(currentIndex);
            items[currentIndex].click(); // Click the selected item
        }
    }
});

// Escape HTML characters for safety
function escapeHtml(unsafe) {
    return unsafe
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

// Perform search when input changes
document.getElementById('searchInput').addEventListener('input', function(event) {
    const query = this.value;
    const highlight = this.getAttribute('data-highlight');

    // If the user is deleting characters, clear the highlight
    if (event.inputType === 'deleteContentBackward' || event.inputType === 'deleteContentForward') {
        this.setAttribute('data-highlight', '');
    } else {
        performSearch(query);
    }
});
</script>
    
    <style>
    .result-item.bg-gray-100 {
        background-color: rgba(251, 191, 36, 0.3); /* Highlight color for selected result */
    }
    </style>
    
