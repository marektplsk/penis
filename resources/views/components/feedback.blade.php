<div class="feedback-container bg-gray-100 p-4 mt-6 rounded-lg shadow">
    <h2 class="text-lg font-semibold text-blue-600 mb-2">Feedback</h2>
    <div id="chat-feedback" class="text-gray-700">
        <p class="text-gray-500">Loading feedback...</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        fetch('/chat/generate-feedback')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to fetch feedback');
                }
                return response.json();
            })
            .then(data => {
                const feedbackContainer = document.getElementById('chat-feedback');
                if (data.error) {
                    // Show error message if any
                    feedbackContainer.innerHTML = `<p class="text-red-500">${data.error}</p>`;
                } else {
                    // Assuming the response contains the feedback as raw text
                    typeWriter(data, feedbackContainer); // Adjusted to use data directly
                }
            })
            .catch(error => {
                const feedbackContainer = document.getElementById('chat-feedback');
                feedbackContainer.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
            });

        // Function to create typewriter effect
        function typeWriter(text, container, speed = 50) {
            let i = 0;

            const cursor = document.createElement('span');
            cursor.className = 'cursor';
            container.innerHTML = ''; // Clear existing content
            container.appendChild(cursor); // Add cursor to the container

            const interval = setInterval(() => {
                if (i < text.length) {
                    cursor.insertAdjacentHTML('beforebegin', text.charAt(i)); // Add character before the cursor
                    i++;
                } else {
                    clearInterval(interval);
                    cursor.remove(); // Remove cursor after animation finishes
                }
            }, speed);
        }
    });
</script>

<style>
    .cursor {
        display: inline-block;
        background-color: black;
        width: 2px;
        height: 1em;
        animation: blink 0.7s steps(2, start) infinite;
        margin-left: 2px;
    }

    @keyframes blink {
        50% {
            opacity: 0;
        }
    }
</style>
