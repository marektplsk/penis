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
            <div class="flex items-center border rounded p-2 w-full" id="tag-input-container">
                <input type="text" id="tag-input" name="tags" placeholder="Add tags" class="flex-grow border-none focus:outline-none">
            </div>
            <div id="tag-dropdown" class="absolute bg-white border rounded w-full mt-1 hidden"></div>
        </div>

        <button type="submit" class="bg-blue-500 text-white rounded p-2 w-full">Submit</button>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tagInput = document.getElementById('tag-input');
        const tagDropdown = document.getElementById('tag-dropdown');
        const tagInputContainer = document.getElementById('tag-input-container');
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
                if (tag) {
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
                        updateDropdown(allTags);
                    }
                });
        }

        function updateDropdown(tags) {
            tagDropdown.innerHTML = '';
            tags.forEach(tag => {
                const option = document.createElement('div');
                option.className = 'p-2 cursor-pointer hover:bg-gray-200 flex justify-between items-center';
                option.textContent = tag;

                option.addEventListener('click', function () {
                    tagInput.value = tag;
                    tagDropdown.classList.add('hidden');
                });
                tagDropdown.appendChild(option);
            });
        }
    });
</script>

<style>
    #tag-input-container {
        display: flex;
        flex-wrap: nowrap;
    }
</style>
