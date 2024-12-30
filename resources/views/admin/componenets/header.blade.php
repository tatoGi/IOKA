<nav class="navbar navbar-expand-lg navbar-light bg-light px-4 shadow-sm">
    <div class="container-fluid">
        <!-- Sidebar Toggle Button -->
        <button class="btn btn-light border-0" id="menu-btn">
            <i class="bx bx-menu"></i>
        </button>

        <!-- Page Title -->
        <span class="navbar-brand ms-3 fw-bold">Admin Panel</span>

        <!-- Right-Side Actions -->
        <div class="d-flex align-items-center ms-auto">
            <!-- User Info Dropdown -->
            <div class="dropdown">
                <!-- Make the username clickable like a link -->
                <a href="#" class="me-3 fw-semibold d-flex align-items-center" id="dropdownMenuButton"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Hello, {{ auth()->user()->name ?? 'Guest' }} <i class="bi bi-caret-down-fill ms-1"></i>
                </a>

                <!-- Dropdown Menu -->
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>


    </div>
</nav>
