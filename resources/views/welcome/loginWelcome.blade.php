<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
<body class="bg-gradient-to-r from-blue-500 to-teal-400 min-h-screen flex items-center justify-center">

<div class="form-container max-w-md w-full p-8 bg-white rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Login</h1>

    @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-6 shadow-lg">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-500 text-white p-4 rounded mb-6 shadow-lg">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" name="email" class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
            <input type="password" name="password" class="border border-gray-300 rounded-md p-3 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-6 flex items-center">
            <input type="checkbox" name="remember" id="remember" class="mr-3 h-4 w-4 text-blue-500">
            <label for="remember" class="text-sm text-gray-700">Remember Me</label>
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-md focus:outline-none transition duration-300">Login</button>
    </form>

    <!-- Register Button -->
    <div class="mt-4 text-center">
        <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Don't have an account? <span class="font-semibold">Register</span></a>
    </div>

    <!-- Back to Welcome Button -->
    <div class="mt-4 text-center">
        <a href="{{ route('welcome') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">Back to <span class="font-semibold">Welcome</span></a>
    </div>
</div>

</body>
</html>
