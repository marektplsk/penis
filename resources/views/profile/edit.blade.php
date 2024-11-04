<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Edit Profile</h1>
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="name" value="{{ old('name', $user->name) }}" placeholder="Name" class="border rounded p-2 w-full" required>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Email" class="border rounded p-2 w-full" required>
            <!-- Include other fields you want to edit here -->

            <button type="submit" class="bg-blue-500 text-white rounded p-2 w-full">Update Profile</button>
        </form>
    </div>
</body>
</html>
