<ol class="dd-list">
    @foreach ($pages as $section)
        <li class="dd-item cursor-move border-2   border-solid rounded-2xl
    @if (count($section->children) > 0) acordion @endif"
            data-id="{{ $section->id }}">
            <div class="dd-handle">
                {{ $section->title }}
            </div>
            <div class="change-icons d-flex gap-2">
                <a href="{{ route('menu.edit', $section->id) }}" class="fas fa-pencil-alt"></a>

                <form action="{{ route('menu.destroy', $section->id) }}" method="post" class="inline delete"
                    onsubmit="return confirm('Do you want to delete this product?');">

                    @csrf

                    @method('DELETE')

                    <button type="submit"
                        class="btn btn-danger btn-sm p-0 d-flex align-items-center justify-content-center">
                        <i class="fas fa-trash"></i>
                    </button>

                </form>
            </div>

            @if (count($section->children) > 0)
                @include('admin.pages.list', ['pages' => $section->children])
            @endif
        </li>
    @endforeach
</ol>
