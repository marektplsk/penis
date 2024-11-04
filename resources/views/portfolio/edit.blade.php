<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Portfolio Item</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex">
    <main class="w-full p-4">
        <h1 class="text-2xl font-bold mb-4">Edit Portfolio Item</h1>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <form action="{{ route('portfolio.update', $portfolio->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Specify the PUT method -->
                
                <div class="mb-4">
                    <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
                    <input type="number" name="amount" id="amount" value="{{ old('amount', $portfolio->amount) }}" 
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-500" required>
                </div>

                <div class="mb-4">
                    <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
                    <input type="text" name="type" id="type" value="{{ old('type', $portfolio->type) }}" 
                           class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring focus:ring-yellow-500" required>
                </div>

                <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 rounded-md">
                    Update Portfolio Item
                </button>
            </form>
        </div>
    </main>
</body>
</html>
