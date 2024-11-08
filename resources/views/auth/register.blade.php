<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Add some custom animations for the form */
        @keyframes slideIn {
            0% { transform: translateY(-20px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .form-container {
            animation: slideIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-purple-500 to-pink-400 min-h-screen flex items-center justify-center">

<div class="form-container max-w-md w-full p-8 bg-white rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-center text-purple-600 mb-6">Register</h1>

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6 shadow-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name</label>
            <input type="text" name="name" class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500" required>
        </div>

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" name="email" class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500" required>
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" name="password" class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500" required>
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
            <input type="password" name="password_confirmation" class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-purple-500" required>
        </div>

        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-md focus:outline-none transition duration-300">Register</button>
    </form>

    <!-- Login Button -->
    <div class="mt-4 text-center">
        <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">Already have an account? <span class="font-semibold">Login</span></a>
    </div>

    <!-- Back to Welcome Button -->
    <div class="mt-4 text-center">
        <a href="{{ route('welcome') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">Back to <span class="font-semibold">Welcome</span></a>
    </div>
</div>

</body>
</html>
