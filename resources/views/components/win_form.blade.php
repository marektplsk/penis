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

        <div class="relative">
            <input type="text" id="tag-input" placeholder="Add tags" class="border rounded p-2 w-full">
            <select id="tag-dropdown" class="border rounded p-2 w-full mt-2">
                <option value="">Select existing tag</option>
                <!-- Options will be populated by JavaScript -->
            </select>
            <div id="tags-container" class="mt-2"></div>
            <input type="hidden" name="tags" id="tags" value="">
        </div>

        <button type="submit" class="bg-blue-500 text-white rounded p-2 w-full">Submit</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tagInput = document.getElementById('tag-input');
        const tagDropdown = document.getElementById('tag-dropdown');
        const tagsContainer = document.getElementById('tags-container');
        const tagsInput = document.getElementById('tags');
        let tags = [];

        // Fetch existing tags from the server
        fetch('/tags')
            .then(response => response.json())
            .then(data => {
                data.forEach(tag => {
                    const option = document.createElement('option');
                    option.value = tag.name;
                    option.textContent = tag.name;
                    tagDropdown.appendChild(option);
                });
            });

        tagInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tag = tagInput.value.trim();
                if (tag && !tags.includes(tag)) {
                    saveTag(tag);
                }
                tagInput.value = '';
            }
        });

        tagDropdown.addEventListener('change', function () {
            const tag = tagDropdown.value;
            if (tag && !tags.includes(tag)) {
                tags.push(tag);
                updateTags();
            }
            tagDropdown.value = '';
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
                    tags.push(tag);
                    updateTags();
                    addTagToDropdown(tag);
                }
            });
        }

        function updateTags() {
            tagsContainer.innerHTML = '';
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
                tagsContainer.appendChild(tagElement);
            });
            tagsInput.value = JSON.stringify(tags);
        }

        function addTagToDropdown(tag) {
            const option = document.createElement('option');
            option.value = tag;
            option.textContent = tag;
            tagDropdown.appendChild(option);
        }
    });
</script>
