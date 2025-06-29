@props(['category', 'level' => 0])

<tr data-category-id="{{ $category->id }}" class="tree-row tree-level-{{ $level }}">
    <td class="text-start" style="padding-right: {{ $level * 32 }}px;">
        @if($category->children && $category->children->count())
            <button class="btn btn-sm btn-link p-0 tree-toggle collapsed" data-bs-toggle="collapse" data-bs-target=".tree-parent-{{ $category->id }}">
                <i class="bi bi-caret-left-fill"></i>
            </button>
        @else
            <span class="d-inline-block" style="width: 24px;"></span>
        @endif
        <span class="fw-semibold text-primary">{{ $category->name }}</span>
    </td>
    <td>
        <span class="badge bg-info text-dark">{{ $category->code }}</span>
    </td>
    <td>
        <span class="badge
            @if($category->category_type == 'person') bg-type-person
            @elseif($category->category_type == 'product') bg-type-product
            @elseif($category->category_type == 'service') bg-type-service
            @endif">
            {{ $category->category_type == 'person' ? 'اشخاص' : ($category->category_type == 'product' ? 'کالا' : 'خدمات') }}
        </span>
    </td>
    <td>
        @if($category->children && $category->children->count())
            <span class="badge bg-secondary">{{ $category->children->count() }} زیر دسته</span>
        @else
            <span class="text-muted">ندارد</span>
        @endif
    </td>
    <td>
        <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-sm btn-light border text-primary" title="ویرایش">
            <i class="bi bi-pencil"></i>
        </a>
        <form method="POST" action="{{ route('categories.destroy', $category->id) }}" class="d-inline" onsubmit="return confirm('آیا مطمئن به حذف هستید؟');">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm btn-light border text-danger" title="حذف">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    </td>
</tr>
@if($category->children && $category->children->count())
    @foreach($category->children as $child)
        <tr class="collapse tree-parent-{{ $category->id }}">
            @component('categories.partials.table-tree-row', ['category' => $child, 'level' => $level + 1]) @endcomponent
        </tr>
    @endforeach
@endif
