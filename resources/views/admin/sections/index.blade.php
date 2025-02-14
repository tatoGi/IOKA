@extends('admin.layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Contact Page Sections</h1>
            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="addSectionDropdown"
                    data-bs-toggle="dropdown">
                    <i class="fas fa-plus fa-sm"></i> Add Section
                </button>
                <ul class="dropdown-menu" aria-labelledby="addSectionDropdown">
                    @foreach ($availableSections as $section)
                        <li>
                            <a class="dropdown-item"
                                href="{{ route('admin.sections.create', ['pageId' => $pageId, 'sectionKey' => $section['key']]) }}">
                                {{ $section['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Content Row -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="sectionsTable">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Section Type</th>
                                <th>Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="sortable">
                            @foreach ($sections as $section)
                                <tr data-section-id="{{ $section->id }}">
                                    <td class="handle"><i class="fas fa-grip-vertical"></i></td>
                                    <td>{{ $availableSections->firstWhere('key', $section->section_key)['label'] ?? $section->section_key }}
                                    </td>
                                    <td>{{ $section->title }}</td>
                                    <td>
                                        <a href="{{ route('admin.sections.edit', ['pageId' => $pageId, 'sectionKey' => $section->section_key]) }}"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form
                                            action="{{ route('admin.sections.destroy', ['pageId' => $section->id, 'sectionKey' => $section->section_key]) }}"
                                            method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"
                                                onclick="return confirm('Are you sure you want to delete this section?')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        new Sortable(document.querySelector('.sortable'), {
            handle: '.handle',
            animation: 150,
            onEnd: function(evt) {
                let sections = [];
                document.querySelectorAll('[data-section-id]').forEach((el, index) => {
                    sections.push(el.dataset.sectionId);
                });

                fetch('{{ route('admin.sections.reorder', ['pageId' => $pageId]) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        sections: sections
                    })
                });
            }
        });
    </script>
@endpush
