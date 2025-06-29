@php
    $hasChildren = $category->childrenRecursive && $category->childrenRecursive->count();
@endphp
<tr data-id="{{ $category->id }}" @if($level>0) data-parent="{{ $category->parent_id }}" style="display:none;" @endif class="tree-level-{{ $level }}">
    <td class="text-start" style="padding-right: {{ $level * 32 }}px;">
        @if($hasChildren)
            <button class="tree-toggle-btn" data-id="{{ $category->id }}">
                <i class="bi bi-caret-left-fill"></i>
            </button>
        @else
            <span class="tree-indent"></span>
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
            {{ $category->category_type == 'person'
                ? 'اشخاص'
                : ($category->category_type == 'product' ? 'کالا' : 'خدمات') }}
        </span>
    </td>
    <td>
        @if($hasChildren)
            <span class="badge bg-secondary">{{ $category->childrenRecursive->count() }} زیر دسته</span>
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
@if($hasChildren)
    @foreach($category->childrenRecursive as $child)
        @include('categories.partials.tree-table-row', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif
