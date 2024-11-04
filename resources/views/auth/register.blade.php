<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Register</h1>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block">Name</label>
                <input type="text" name="name" class="border rounded p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block">Email</label>
                <input type="email" name="email" class="border rounded p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block">Password</label>
                <input type="password" name="password" class="border rounded p-2 w-full" required>
            </div>
            <div class="mb-4">
                <label for="password_confirmation" class="block">Confirm Password</label>
                <input type="password" name="password_confirmation" class="border rounded p-2 w-full" required>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Register</button>
        </form>
    </div>
</body>
</html>
