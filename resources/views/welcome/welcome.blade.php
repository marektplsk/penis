<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our App</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Background animations */
        .animated-bg {
            position: absolute;
            border-radius: 50%;
            opacity: 0.4;
            animation: float 6s infinite ease-in-out;
        }
        .bg-1 { width: 300px; height: 300px; top: 10%; left: 5%; background-color: #3b82f6; }
        .bg-2 { width: 200px; height: 200px; bottom: 10%; right: 10%; background-color: #f59e0b; }
        .bg-3 { width: 250px; height: 250px; top: 50%; left: 50%; background-color: #10b981; }

        /* Keyframes for animations */
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fadeIn {
            animation: fadeInUp 1s ease-out;
        }
        .bounce {
            animation: bounce 1.5s infinite ease;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
    </style>
</head>
<body class="relative min-h-screen flex items-center justify-center bg-gray-900 overflow-hidden">

<!-- Background animated circles -->
<div class="bg-1 animated-bg"></div>
<div class="bg-2 animated-bg"></div>
<div class="bg-3 animated-bg"></div>

<!-- Main welcome container -->
<div class="relative z-10 text-center p-8 text-white fadeIn">
    <h1 class="text-5xl font-bold mb-4">Welcome to Our NIGGER App!</h1>
    <p class="text-lg text-gray-300 mb-8">Your journey begins here. Experience the best we have to offer.</p>
    <a href="{{ route('register') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded-full bounce transition-all duration-300">Get Started</a>
    <a href="{{ route('login') }}" class="bg-gray-700 hover:bg-gray-800 text-white font-semibold py-2 px-6 rounded-full transition-all duration-300">Login</a>


    <h3 class="text-2xl font-bold mb-4">
        Dorobit-> lepsie name, potom dat desription a nasledne dat tam tiez aj nejaky fade, nasledne dat tam ze chyby a nasledne spravit chart na chyby :D
    </h3>

    <h1>kokOOOOOT</h1>
</div>
<!-- Footer -->
<footer class="absolute bottom-4 w-full text-center text-gray-400 text-sm">
    <p>&copy; {{ date('Y,M,D,H') }} OurApp. All rights reserved.</p>
</footer>

</body>
</html>
