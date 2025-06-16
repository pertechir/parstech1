@foreach($services as $service)
<tr>
    <td>
        <button
            type="button"
            class="btn btn-success btn-sm add-product-btn"
            data-id="{{ $service->id }}"
            data-type="service"
        >
            <i class="fa fa-plus"></i>
        </button>
    </td>
    <td>{{ $service->code ?? '-' }}</td>
    <td>{{ $service->title ?? $service->name ?? '-' }}</td>
    <td>{{ $service->category->name ?? '-' }}</td>
    <td>{{ $service->price ? number_format($service->price) : '-' }}</td>
    <td>{{ $service->unit ?? '-' }}</td>
    <td>{{ $service->description ?? '' }}</td>
</tr>
@endforeach
