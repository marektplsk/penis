<!-- sidebar.blade.php -->
<div class="sidebar p-4">
    <h1 class="text-lg font-bold">My Application</h1>
    <ul class="mt-4">
        <li><a href=" {{ route('app.index')}}" class="block py-2 px-4 hover:bg-gray-200">Home</a></li>
        <li><a href=" {{ route('dashboard') }}" class="block py-2 px-4 hover:bg-gray-200">Dashboard</a></li>
        <li><a href="" class="block py-2 px-4 hover:bg-gray-200">Search</a></li>
        <li><a href=" {{ route('portfolio.index' )}}" class="block py-2 px-4 hover:bg-gray-200">Portfolio</a></li>
        <li><a href="" class="block py-2 px-4 hover:bg-gray-200">AI Feedback</a></li>

    @if (Auth::check())
            <li><a href="{{ route('profile.show') }}" class="block py-2 px-4 hover:bg-gray-200">Profile</a></li>
        @else
            <li><a href="{{ route('login') }}" class="block py-2 px-4 hover:bg-gray-200">Login</a></li>
            <li><a href="{{ route('register') }}" class="block py-2 px-4 hover:bg-gray-200">Register</a></li>
        @endif

    </ul>
</div>

<style>
    /* Custom styles */
    .sidebar {
        background-color: #E3E3E3;
        width: 250px; /* Fixed width for the sidebar */
        border-top-right-radius: 24px;
        border-bottom-right-radius: 24px;
        position: sticky;
        top: 0; /* Stick to the top of the viewport */
        height: 100vh; /* Full height of the viewport */
        overflow-y: auto; /* Allow scrolling if content overflows */
    }
</style>
