<nav class="sticky top-0 z-10 p-2 mb-4 bg-white bg-opacity-50 backdrop-blur">
    <ol class="list-reset flex text-xs space-x-1">
        @foreach ($breadcrumbs as $breadcrumb)
            <li>
                <a href="{{ $breadcrumb['url'] }}" 
                   class="hover:text-gray-900 underline 
                   {{ request()->is('dashboard/show/'.$breadcrumb['name']) ? 'text-gray-600 font-bold' : 'text-gray-600' }}" 
                   style="{{ request()->is('dashboard/show/'.$breadcrumb['name']) ? 'color: #696969;' : 'color: #A7A7A7;' }}">
                   {{ $breadcrumb['name'] }}
                </a>
            </li>
            @if (!$loop->last)
                <li class="mx-1" style="color: {{ request()->is('dashboard/show/'.$breadcrumb['name']) ? '#696969' : '#A7A7A7' }};">/</li>
            @endif
        @endforeach
    </ol>
</nav>
