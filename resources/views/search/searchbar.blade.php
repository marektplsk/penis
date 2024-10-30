<!-- Search Bar Modal -->
<div id="searchModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white w-full max-w-md p-6 rounded-lg shadow-lg relative">
        <!-- Close button -->
        <button onclick="closeSearch()" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
            &times;
        </button>

        <input type="text" 
               id="searchInput" 
               placeholder="Search..." 
               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
               oninput="performSearch(this.value)" 
               autofocus>
        
        <!-- Search Results -->
        <div id="searchResults" class="mt-4"></div>
    </div>
</div>

<script>
    // Toggle the search modal visibility
    function toggleSearch() {
        const modal = document.getElementById('searchModal');
        modal.classList.toggle('hidden');
        if (!modal.classList.contains('hidden')) {
            document.getElementById('searchInput').focus();
        }
    }

    // Close the search modal
    function closeSearch() {
        document.getElementById('searchModal').classList.add('hidden');
    }

    // Listen for cmd+k or ctrl+k
    document.addEventListener('keydown', function(event) {
        if ((event.metaKey || event.ctrlKey) && event.key === 'k') {
            event.preventDefault();
            toggleSearch();
        }
    });

    // Perform the search query
    async function performSearch(query) {
        if (query.length < 3) {
            document.getElementById('searchResults').innerHTML = ''; // Clear if less than 3 chars
            return;
        }
        
        try {
            const response = await fetch(`/search?q=${query}`);
            const results = await response.json();
            displaySearchResults(results);
        } catch (error) {
            console.error("Search failed:", error);
        }
    }

    // Display search results
    function displaySearchResults(results) {
        const resultsContainer = document.getElementById('searchResults');
        const sidebarResultsContainer = document.getElementById('searchResultsSidebar');
        
        resultsContainer.innerHTML = ''; // Clear previous results
        sidebarResultsContainer.innerHTML = ''; // Clear previous sidebar results

        if (results.length === 0) {
            resultsContainer.innerHTML = '<p class="text-gray-500">No results found</p>';
            return;
        }

        results.forEach(result => {
            const resultItem = document.createElement('div');
            resultItem.classList.add('p-2', 'border-b', 'border-gray-200', 'hover:bg-gray-100', 'cursor-pointer');
            resultItem.innerHTML = `<a href="${result.url}" class="text-blue-600 hover:underline">${result.title}</a>`;
            resultsContainer.appendChild(resultItem);
            
            // Also add to sidebar results
            const sidebarResultItem = document.createElement('div');
            sidebarResultItem.classList.add('p-2', 'border-b', 'border-gray-200', 'hover:bg-gray-100', 'cursor-pointer');
            sidebarResultItem.innerHTML = `<a href="${result.url}" class="text-blue-600 hover:underline">${result.title}</a>`;
            sidebarResultsContainer.appendChild(sidebarResultItem);
        });
    }
</script>
