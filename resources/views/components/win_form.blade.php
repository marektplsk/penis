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

        <input type="text" id="tag-input" placeholder="Add tags" class="border rounded p-2 w-full">
        <div id="tags-container" class="mt-2"></div>
        <input type="hidden" name="tags" id="tags" value="">

        <button type="submit" class="bg-blue-500 text-white rounded p-2 w-full">Submit</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tagInput = document.getElementById('tag-input');
        const tagsContainer = document.getElementById('tags-container');
        const tagsInput = document.getElementById('tags');
        let tags = [];

        tagInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const tag = tagInput.value.trim();
                if (tag && !tags.includes(tag)) {
                    tags.push(tag);
                    updateTags();
                }
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

        document.addEventListener('DOMContentLoaded', function () {
            fetch('/tags')
                .then(response => response.json())
                .then(data => {
                    // Handle the fetched tags data
                    console.log(data);
                });
        });
    });
</script>

