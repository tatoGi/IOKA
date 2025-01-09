<div class="side-navbar active-nav d-flex justify-content-between flex-wrap flex-column" id="sidebar">
    <ul class="nav flex-column text-white w-100 overflow-auto" style="max-height: calc(100vh - 60px);">
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
        <a href="{{ route('blogposts.index') }}" style="text-decoration: none;">
            <li class="nav-link">
                <i class='bx bx-menu bx-tada bx-rotate-180'></i>
                <span class="mx-2">Blog Posts</span>
            </li>
        </a>
        <!-- Homepage Section Dropdown -->
        @php
            $pageTypes = collect(Config::get('PageTypes'))->sortBy('id');
        @endphp
        @foreach ($pageTypes as $type)
            @if (in_array($type['id'], [1]))
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
                        @if (isset($type['sections']) && is_array($type['sections']))
                            @foreach ($type['sections'] as $sectionKey => $section)

                                <li>
                                    @php
                                        $page = \App\Models\Page::where('type_id', $type['id'])->first();
                                        $sectionExists = $page && \App\Models\Section::where('section_key', $sectionKey)->exists();

                                        $route = $sectionExists ? 'admin.sections.edit' : 'admin.sections.create';
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
                    </ul>
                </div>

                </div>
            @endif
        @endforeach
    </ul>
</div>
