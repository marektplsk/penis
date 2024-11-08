<!-- win_form.blade.php -->
<div class="mt-8">
    <h2 class="text-2xl font-bold mb-4">Add Win/Loss</h2>
    <form action="{{ route('app.store') }}" method="POST" class="space-y-4">
        @csrf
        <input type="text" name="description" placeholder="Name" class="border rounded p-2 w-full" required>
        <select name="is_win" class="border rounded p-2 w-full" required>
            <option value="1">Win</option>
            <option value="0">Loss</option>
        </select>
        <input type="number" step="0.01" name="risk" placeholder="Risk (%)" class="border rounded p-2 w-full" required>
        <input type="number" step="0.01" name="risk_reward_ratio" placeholder="Risk Reward Ratio (e.g., 2 for 1:2)" class="border rounded p-2 w-full" required>
        <select name="hour_session" class="border rounded p-2 w-full" required>
            <option value="">Select Trading Session</option>
            <option value="NY AM">New York AM</option>
            <option value="London">London</option>
            <option value="NY PM">New York PM</option>
            <option value="Other">Other</option>
            <option value="Asian">Asian</option>
        </select>
        <input type="text" name="data" placeholder="Description" class="border rounded p-2 w-full h-32 focus:outline-none focus:ring-2 focus:ring-blue-500 align-top" style="vertical-align: top; text-align: start;" required>
        <select name="trade_type" class="border rounded p-2 w-full" required>
            <option value="short">Short</option>
            <option value="long">Long</option>
        </select>

        <div class="flex items-center">
            <input type="text" id="tag-input" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter a tag">
            <button type="button" id="add-tag" class="ml-2 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add Tag</button>
        </div>
        <div id="tags-container" class="mt-2">
            <!-- Tags will be displayed here -->
        </div>

        <div class="mb-4">
            <label for="saved-tags" class="block text-gray-700 text-sm font-bold mb-2">Saved Tags:</label>
            <select id="saved-tags" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select a tag</option>
            </select>
        </div>

        <input type="hidden" name="tags" id="tags" value="">
        <button type="submit" class="bg-blue-500 text-white rounded p-2 w-full">Submit</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tagInput = document.getElementById('tag-input');
        const addTagButton = document.getElementById('add-tag');
        const tagsContainer = document.getElementById('tags-container');
        const tagsInput = document.getElementById('tags');
        const savedTagsDropdown = document.getElementById('saved-tags');
        let tags = [];

        // Fetch saved tags from the server
        fetch('/tags')
            .then(response => response.json())
            .then(savedTags => {
                savedTags.forEach(tag => {
                    const option = document.createElement('option');
                    option.value = tag.name;
                    option.textContent = tag.name;
                    savedTagsDropdown.appendChild(option);

                    // Add delete button for each tag
                    const deleteButton = document.createElement('button');
                    deleteButton.textContent = 'Delete';
                    deleteButton.className = 'ml-2 bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded';
                    deleteButton.addEventListener('click', function () {
                        fetch(`/tags/${tag.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    option.remove();
                                    deleteButton.remove();
                                }
                            });
                    });

                    savedTagsDropdown.parentNode.appendChild(deleteButton);
                });
            });

        addTagButton.addEventListener('click', function () {
            const tag = tagInput.value.trim();
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                updateTags();
                tagInput.value = '';
            }
        });

        function updateTags() {
            tagsContainer.innerHTML = '';
            tags.forEach(tag => {
                const tagElement = document.createElement('span');
                tagElement.className = 'inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2';
                tagElement.textContent = tag;
                tagsContainer.appendChild(tagElement);
            });
            tagsInput.value = JSON.stringify(tags);
        }
    });
</script>
