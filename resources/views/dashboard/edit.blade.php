<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trade</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col items-center">
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Trade</h1>

    <form action="{{ route('dashboard.show', $win->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <!-- Description Field -->
        <input type="text" name="description" value="{{ $win->description }}" placeholder="Description" class="border rounded p-2 w-full" required>

        <!-- Pair Input Field -->
        <div class="relative">
            <input
                type="text"
                name="pair"
                value="{{ $win->pair }}"
            id="pair-input"
            placeholder="Enter pair (e.g., XAUUSD)"
            class="border rounded p-2 w-full"
            autocomplete="off"
            required
            >
            <div id="pair-dropdown" class="absolute bg-white border rounded w-full mt-1 hidden shadow-lg z-10"></div>
        </div>

        <!-- Win/Loss -->
        <select name="is_win" class="border rounded p-2 w-full" required>
            <option value="1" {{ $win->is_win ? 'selected' : '' }}>Win</option>
            <option value="0" {{ !$win->is_win ? 'selected' : '' }}>Loss</option>
        </select>

        <!-- Risk -->
        <input type="number" step="0.01" name="risk" value="{{ $win->risk }}" placeholder="Risk (%)" class="border rounded p-2 w-full" required>

        <!-- Risk-Reward Ratio -->
        <input type="number" step="0.01" name="risk_reward_ratio" value="{{ $win->risk_reward_ratio }}" placeholder="Risk Reward Ratio (e.g., 2 for 1:2)" class="border rounded p-2 w-full" required>

        <!-- Hour Session -->
        <select name="hour_session" class="border rounded p-2 w-full" required>
            <option value="Asian" {{ $win->hour_session == 'Asian' ? 'selected' : '' }}>Asian Session</option>
            <option value="London" {{ $win->hour_session == 'London' ? 'selected' : '' }}>London Session</option>
            <option value="New York" {{ $win->hour_session == 'New York' ? 'selected' : '' }}>New York Session</option>
            <option value="NY PM" {{ $win->hour_session == 'NY PM' ? 'selected' : '' }}>NY PM Session</option>
        </select>

        <!-- Data -->
        <input type="text" name="data" value="{{ $win->data }}" placeholder="Data" class="border rounded p-2 w-full" required>

        <!-- Trade Type -->
        <select name="trade_type" class="border rounded p-2 w-full" required>
            <option value="short" {{ $win->trade_type == 'short' ? 'selected' : '' }}>Short</option>
            <option value="long" {{ $win->trade_type == 'long' ? 'selected' : '' }}>Long</option>
        </select>

        <!-- Tags -->
        <input type="text" id="tag-input" name="tags[]" value="{{ implode(',', json_decode($win->tags, true) ?? []) }}" placeholder="Tags (comma separated)" class="border rounded p-2 w-full">

        <!-- Update Button -->
        <button type="submit" class="bg-blue-500 text-white rounded p-2 w-full">Update</button>
    </form>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tagInput = document.getElementById('tag-input');
        const tagDropdown = document.getElementById('tag-dropdown');
        const tagInputContainer = document.getElementById('tag-input-container');
        const tagsInput = document.getElementById('tags');
        let tags = [];
        let allTags = [];

        // Fetch existing tags from the server
        fetch('/tags')
            .then(response => response.json())
            .then(data => {
                allTags = data.map(tag => tag.name);
            });

        tagInput.addEventListener('focus', function () {
            updateDropdown(allTags);
            tagDropdown.classList.remove('hidden');
        });

        tagInput.addEventListener('input', function () {
            const query = tagInput.value.trim().toLowerCase();
            const filteredTags = allTags.filter(tag => tag.toLowerCase().includes(query));
            updateDropdown(filteredTags);
        });

        tagInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tag = tagInput.value.trim();
                if (tag && !tags.includes(tag)) {
                    saveTag(tag);
                }
                tagInput.value = '';
                tagDropdown.classList.add('hidden');
            }
        });

        document.addEventListener('click', function (e) {
            if (!tagInputContainer.contains(e.target)) {
                tagDropdown.classList.add('hidden');
            }
        });

        function saveTag(tag) {
            fetch('/tags', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ name: tag })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        allTags.push(tag);
                        addTag(tag);
                        updateDropdown(allTags);
                    }
                });
        }

        function addTag(tag) {
            tags.push(tag);
            updateTags();
        }

        function updateTags() {
            tagInputContainer.innerHTML = '';
            tagInputContainer.appendChild(tagInput);
            tags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = 'inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2';
                tagElement.textContent = tag;

                const deleteButton = document.createElement('button');
                deleteButton.className = 'ml-2 text-red-500';
                deleteButton.textContent = 'x';
                deleteButton.addEventListener('click', function () {
                    tags = tags.filter(t => t !== tag);
                    updateTags();
                });

                tagElement.appendChild(deleteButton);
                tagInputContainer.appendChild(tagElement);
            });
            tagsInput.value = JSON.stringify(tags);
        }

        function updateDropdown(tags) {
            tagDropdown.innerHTML = '';
            tags.forEach(tag => {
                const option = document.createElement('div');
                option.className = 'p-2 cursor-pointer hover:bg-gray-200 flex justify-between items-center';
                option.textContent = tag;

                const deleteButton = document.createElement('button');
                deleteButton.className = 'ml-2 text-red-500';
                deleteButton.textContent = 'x';
                deleteButton.addEventListener('click', function (e) {
                    e.stopPropagation();
                    allTags = allTags.filter(t => t !== tag);
                    updateDropdown(allTags);
                });

                option.appendChild(deleteButton);
                option.addEventListener('click', function () {
                    addTag(tag);
                    tagInput.value = '';
                    tagDropdown.classList.add('hidden');
                });
                tagDropdown.appendChild(option);
            });
        }
    });
    ///kokotkoooo


    document.addEventListener('DOMContentLoaded', function () {
        const pairInput = document.getElementById('pair-input');
        const pairDropdown = document.getElementById('pair-dropdown');
        const pairs = ["Ym", "Es", "Nq", "XAUUSD", "EURUSD", "BTCUSDT"];

        let autofilledValue = '';     // Track the autofilled part of the pair
        let userTypedValue = '';      // Track the manually typed part of the pair

        // Function to highlight the autofilled part in the input field
        function highlightText(input, start, length) {
            input.setSelectionRange(start, start + length);  // Highlight the matched part
        }

        // Handle user typing in the pair input field
        pairInput.addEventListener('input', function () {
            const query = pairInput.value.trim().toUpperCase();

            // Filter pairs based on the query typed by the user
            const filteredPairs = query
                ? pairs.filter(pair => pair.toUpperCase().startsWith(query))
                : pairs;

            if (filteredPairs.length === 1 && filteredPairs[0].toUpperCase().startsWith(query)) {
                autofilledValue = filteredPairs[0].substring(query.length);  // Capture the autofilled part
                userTypedValue = query;  // Store the user typed part
                pairInput.value = userTypedValue + autofilledValue;  // Update the input field
                highlightText(pairInput, userTypedValue.length, autofilledValue.length);  // Highlight the autofilled part
            } else {
                autofilledValue = '';  // Reset autofilled part if no match
            }

            // Show the dropdown with filtered pairs
            updatePairDropdown(filteredPairs);
        });

        // Handle key events for delete and backspace
        pairInput.addEventListener('keydown', function (e) {
            if (e.key === "Backspace" || e.key === "Delete") {
                const currentLength = pairInput.value.length;

                // If there is an autofilled part and the user presses backspace/delete, delete the autofilled part first
                if (currentLength > userTypedValue.length && autofilledValue !== '') {
                    autofilledValue = '';  // Remove the autofilled part
                    pairInput.value = userTypedValue + autofilledValue;  // Update input field with remaining manually typed part
                    highlightText(pairInput, userTypedValue.length, autofilledValue.length);  // Remove highlight
                    e.preventDefault();  // Prevent default backspace to avoid deleting the user typed part prematurely
                }
                // If there is no autofilled part left, delete the manually typed part
                else if (currentLength > 0) {
                    userTypedValue = userTypedValue.slice(0, -1);  // Remove the last typed character
                    pairInput.value = userTypedValue + autofilledValue;  // Update input field
                    highlightText(pairInput, userTypedValue.length, autofilledValue.length);  // Update highlight
                }
            }
        });

        // Show dropdown when input is focused
        pairInput.addEventListener('focus', function () {
            const query = pairInput.value.trim().toUpperCase();
            const filteredPairs = query
                ? pairs.filter(pair => pair.toUpperCase().startsWith(query))
                : pairs;
            updatePairDropdown(filteredPairs);
            pairDropdown.classList.remove('hidden');
        });

        // Hide dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!pairInput.contains(e.target) && !pairDropdown.contains(e.target)) {
                pairDropdown.classList.add('hidden');
            }
        });

        // Handle dropdown item selection
        pairDropdown.addEventListener('click', function (e) {
            if (e.target && e.target.dataset.pair) {
                pairInput.value = e.target.dataset.pair;
                autofilledValue = e.target.dataset.pair.substring(pairInput.value.length);  // Update autofilled part
                userTypedValue = pairInput.value;
                pairDropdown.classList.add('hidden');
            }
        });

        // Update dropdown with filtered pairs
        function updatePairDropdown(filteredPairs) {
            pairDropdown.innerHTML = ''; // Clear existing options
            if (filteredPairs.length === 0) {
                pairDropdown.innerHTML = '<div class="p-2 text-gray-500">No matches</div>';
                return;
            }

            filteredPairs.forEach(pair => {
                const option = document.createElement('div');
                option.className = 'p-2 cursor-pointer hover:bg-gray-200';
                option.textContent = pair;
                option.dataset.pair = pair;
                pairDropdown.appendChild(option);
            });

            pairDropdown.classList.remove('hidden');
        }
    });



</script>

<style>
    #tag-input-container {
        display: flex;
        flex-wrap: nowrap;
    }

    #pair-dropdown, #tag-dropdown {
        max-height: 150px;
        overflow-y: auto;
    }
</style>

</html>
