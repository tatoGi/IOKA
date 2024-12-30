<div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar">
    <ul class="nav flex-column text-white w-100">
        <a href="#" class="nav-link h3 text-white my-2" style="text-decoration: none;">
            <img src="{{ asset('storage/admin/logo/logo.png') }}" alt="logo" class="w-1/2 h-100">
        </a>
        <a href="{{ route('admin.dashboard') }}" style="text-decoration: none;">
            <li class="nav-link">
                <i class='bx bxs-dashboard bx-spin bx-rotate-180'></i>
                <span class="mx-2">Dashboard</span>
            </li>
        </a>
        <a href="{{ route('admin.activity') }}" style="text-decoration: none;">
            <li class="nav-link">
                <i class='bx bxs-bookmarks bx-tada'></i>
                <span class="mx-2">Activity Log</span>
            </li>
        </a>
        <a href="/ioka_admin/menu" style="text-decoration: none;">
            <li class="nav-link">
                <i class='bx bx-menu bx-tada bx-rotate-180'></i>
                <span class="mx-2">Frontend Menu Controller</span>
            </li>
        </a>

        <!-- Loop through PageTypes to create dropdowns -->
        @foreach (Config::get('PageTypes') as $type)
        @if(!in_array($type['id'], [5, 6]))
            <li class="nav-link dropdown">
                <a
                    href="#"
                    class="text-white dropdown-toggle d-flex align-items-center"
                    data-bs-toggle="dropdown"
                    aria-expanded="false"
                    style="text-decoration: none;"
                >
                    <i class='bx bx-intersect bx-spin'></i>
                    <span class="mx-2">{{ $type['name'] }}</span>
                </a>
                <ul class="dropdown-menu bg-dark text-white border-0">
                    @foreach ($type['items'] as $item)
                        <li>
                            <a
                                href="{{ $item['url'] }}"
                                class="dropdown-item text-white"
                                style="text-decoration: none;"
                            >
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    @endforeach

    </ul>
</div>
