<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex flex-col items-center">
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Profile</h1>
        
        <div class="bg-white shadow-md rounded p-6">
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <!-- Add more profile fields as needed -->
        </div>
        
        <div class="mt-6">
            <a href="{{ route('profile.edit') }}" class="mt-4 inline-block bg-blue-500 text-white px-4 py-2 rounded">Edit Profile</a>
            <a href="{{ route('app.index') }}" class="mt-4 inline-block bg-green-500 text-white px-4 py-2 rounded">Go to App</a> <!-- Button to go to /app -->
        </div>

        <!-- Log out button -->
        <form action="{{ route('logout') }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Logout</button>
        </form>
    </div>
</body>
</html>
