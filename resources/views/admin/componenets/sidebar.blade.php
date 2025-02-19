<div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar">
    <ul class="nav flex-column text-white w-100 overflow-auto">
        <a href="#" class="nav-link h3 text-white my-2" style="text-decoration: none;">
            <img src="{{ asset('storage/admin/logo/logo.png') }}" alt="logo" class="w-1/2 h-100">
        </a>
        <a href="{{ route('admin.dashboard') }}" style="text-decoration: none;">
            <li class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i
                    class='bx bxs-dashboard {{ request()->routeIs('admin.dashboard') ? 'bx-flashing' : 'bx-spin' }} bx-rotate-180'></i>
                <span class="mx-2">Dashboard</span>
            </li>
        </a>
        <a href="{{ route('admin.activity') }}" style="text-decoration: none;">
            <li class="nav-link {{ request()->routeIs('admin.activity') ? 'active' : '' }}">
                <i class='bx bxs-bookmarks {{ request()->routeIs('admin.activity') ? 'bx-flashing' : 'bx-tada' }}'></i>
                <span class="mx-2">Activity Log</span>
            </li>
        </a>
        <a href="/ioka_admin/menu" style="text-decoration: none;">
            <li class="nav-link {{ request()->is('ioka_admin/menu*') ? 'active' : '' }}">
                <i
                    class='bx bx-menu {{ request()->is('ioka_admin/menu*') ? 'bx-flashing' : 'bx-tada' }} bx-rotate-180'></i>
                <span class="mx-2">Frontend Menu Controller</span>
            </li>
        </a>
        <a href="{{ route('blogposts.index') }}" style="text-decoration: none;">
            <li class="nav-link {{ request()->routeIs('blogposts.*') ? 'active' : '' }}">
                <i
                    class='bx bx-menu {{ request()->routeIs('blogposts.*') ? 'bx-flashing' : 'bx-tada' }} bx-rotate-180'></i>
                <span class="mx-2">Blog Posts</span>
            </li>
        </a>

        <!-- Homepage Section Dropdown -->
        @php
            $pageTypes = collect(Config::get('PageTypes'))->sortBy('id');
        @endphp
        @foreach ($pageTypes as $type)
            @if (in_array($type['id'], [1, 3, 2, 7]))
                <div class="accordion-item bg-dark border-0">
                    <h2 class="accordion-header" id="heading{{ $type['id'] }}">
                        <button class="accordion-button text-white bg-dark collapsed custom-accordion-button"
                            type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $type['id'] }}"
                            aria-expanded="false" aria-controls="collapse{{ $type['id'] }}">
                            <i class='bx bx-intersect bx-spin'></i>
                            <span class="mx-2 text-truncate">{{ $type['name'] }}</span>
                        </button>
                    </h2>
                    <div id="collapse{{ $type['id'] }}" class="accordion-collapse collapse"
                        aria-labelledby="heading{{ $type['id'] }}" data-bs-parent="#sidebar">
                        <ul class="accordion-body list-unstyled">
                            @if ($type['id'] == 3)
                                @php
                                    $page = \App\Models\Page::where('type_id', $type['id'])->first();
                                @endphp
                                @if ($page)
                                    <li>
                                        <a href="{{ route('admin.sections.index', ['pageId' => $page->id]) }}"
                                            class="dropdown-item text-white text-truncate"
                                            style="text-decoration: none; max-width: 250px;">
                                            Sections List
                                        </a>
                                    </li>
                                @endif
                            @else
                                @if (isset($type['sections']) && is_array($type['sections']))
                                    @foreach ($type['sections'] as $sectionKey => $section)
                                        <li>
                                            @php
                                                $page = \App\Models\Page::where('type_id', $type['id'])->first();
                                                $sectionExists =
                                                    $page &&
                                                    \App\Models\Section::where('section_key', $sectionKey)->exists();

                                                $route = $sectionExists
                                                    ? 'admin.sections.edit'
                                                    : 'admin.sections.create';
                                            @endphp
                                            @if ($page)
                                                <a href="{{ route($route, [
                                                    'pageId' => $page->id,
                                                    'sectionKey' => $sectionKey,
                                                ]) }}"
                                                    class="dropdown-item text-white text-truncate"
                                                    style="text-decoration: none; max-width: 250px;">
                                                    {{ $section['label'] }}
                                                    @if (!$sectionExists)
                                                        <small class="text-warning ms-1">(Not Created)</small>
                                                    @endif
                                                </a>
                                            @else
                                                <small class="text-danger ms-1">(Page Not Found)</small>
                                            @endif
                                        </li>
                                    @endforeach
                                @endif
                            @endif
                        </ul>
                    </div>
                </div>
            @endif
        @endforeach
        <a href="{{ url('ioka_admin/partners') }}" style="text-decoration: none;">
            <li class="nav-link {{ request()->is('ioka_admin/partners*') ? 'active' : '' }}">
                <i class='bx bx-slideshow {{ request()->is('ioka_admin/partners*') ? 'bx-flashing' : 'bx-tada' }}'></i>
                <span class="mx-2">Partners</span>
            </li>
        </a>
        <a href="{{ url('ioka_admin/messages') }}" style="text-decoration: none;">
            <li class="nav-link {{ request()->is('ioka_admin/messages*') ? 'active' : '' }}">
                <i class='bx bx-message-rounded-dots bx-tada bx-rotate-90 bx-spin'></i>
                <span class="mx-2">Messages</span>
            </li>
        </a>
        <a href="{{ url('ioka_admin/postypes/rental_resale') }}" style="text-decoration: none;">
            <li class="nav-link ">
                <i class='bx bx-menu'></i>
                <span class="mx-2">Rental Resale</span>
            </li>
        </a>
        <a href="{{ url('ioka_admin/postypes/offplan') }}" style="text-decoration: none;">
            <li class="nav-link ">
                <i class='bx bx-menu'></i>
                <span class="mx-2">Offplan</span>
            </li>
        </a>
    </ul>

</div>
