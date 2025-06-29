@extends('layouts.app')

@section('title', 'لیست محصولات')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/products-index.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <style>
        .dt-buttons .btn {margin-left: 8px;}
        .product-thumb {width: 52px; height: 52px; object-fit: cover; border-radius: 8px; border: 1px solid #eee;}
        .filter-row input, .filter-row select {min-width: 70px;}
        .badge-in-stock {background: #16a34a;}
        .badge-out-stock {background: #dc2626;}
        .badge-low-stock {background: #f59e42;}
        /* اسکرول جدول رفع */
        .dataTables_wrapper .dataTables_paginate { margin-top: 1rem; }
        .dataTables_wrapper .dataTables_info { margin-top: 1rem; }
        .dataTables_scrollBody { overflow-x: auto !important; overflow-y: visible !important; }
        table.dataTable { width: 100% !important; }
        /* بارکدها */
        .barcode-label-td { border: 0.5pt dashed #bbb; width: 50mm; height: 35mm; padding: 2mm; vertical-align: top;}
        .barcode-label-content { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;}
        .barcode-label-name { font-size: 12px; font-weight: bold; margin-bottom: 2mm; }
        .barcode-text { font-size: 10px; margin-top: 2mm;}
        svg.barcode-svg { width: 41mm !important; height: 22mm !important; }
        .barcode-grid-table { width:100%; border-collapse:collapse; table-layout: fixed;}
    </style>
@endsection

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="mb-0">لیست محصولات</h4>
            <div>
                <button class="btn btn-outline-primary me-2" id="printSelectedBarcodesBtn">
                    <i class="fas fa-barcode"></i> چاپ بارکد محصولات انتخابی
                </button>
                <a href="{{ route('products.create') }}" class="btn btn-success">افزودن محصول جدید</a>
            </div>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-3 row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">دسته‌بندی</label>
                    <select id="filter-category" class="form-select">
                        <option value="">همه</option>
                        @foreach(\App\Models\Category::where('category_type', 'product')->get() as $cat)
                            <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">برند</label>
                    <select id="filter-brand" class="form-select">
                        <option value="">همه</option>
                        @foreach(\App\Models\Brand::all() as $brand)
                            <option value="{{ $brand->name }}">{{ $brand->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">وضعیت موجودی</label>
                    <select id="filter-stock" class="form-select">
                        <option value="">همه</option>
                        <option value="in">موجود</option>
                        <option value="low">کم</option>
                        <option value="out">ناموجود</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">بازه تاریخ ثبت</label>
                    <input type="text" id="filter-date" class="form-control" autocomplete="off" placeholder="انتخاب بازه">
                </div>
            </div>

            <div class="table-responsive">
                <table id="productsTable" class="table table-bordered table-hover align-middle w-100">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="selectAllProducts" title="انتخاب همه">
                            </th>
                            <th>تصویر</th>
                            <th>نام</th>
                            <th>کد</th>
                            <th>دسته‌بندی</th>
                            <th>برند</th>
                            <th>قیمت فروش</th>
                            <th>موجودی</th>
                            <th>تاریخ ثبت</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th>تصویر</th>
                            <th>نام</th>
                            <th>کد</th>
                            <th>دسته‌بندی</th>
                            <th>برند</th>
                            <th>قیمت فروش</th>
                            <th>موجودی</th>
                            <th>تاریخ ثبت</th>
                            <th>عملیات</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse($products as $product)
                            <tr data-id="{{ $product->id }}"
                                data-name="{{ $product->name }}"
                                data-barcode="{{ $product->barcode }}"
                                data-code="{{ $product->code }}"
                                data-category="{{ $product->category?->name ?? '-' }}"
                                data-brand="{{ $product->brand?->name ?? '-' }}"
                            >
                                <td>
                                    <input type="checkbox" class="product-select-checkbox" value="{{ $product->id }}">
                                </td>
                                <td>
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" class="product-thumb" alt="">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}">{{ $product->name }}</a>
                                    @if(!$product->is_active)
                                        <span class="badge bg-secondary ms-2">غیرفعال</span>
                                    @endif
                                </td>
                                <td>{{ $product->code }}</td>
                                <td>{{ $product->category?->name ?? '-' }}</td>
                                <td>{{ $product->brand?->name ?? '-' }}</td>
                                <td>{{ number_format($product->sell_price) }}</td>
                                <td>
                                    @if($product->stock > $product->min_stock)
                                        <span class="badge badge-in-stock">موجود: {{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                        <span class="badge badge-low-stock">کم: {{ $product->stock }}</span>
                                    @else
                                        <span class="badge badge-out-stock">ناموجود</span>
                                    @endif
                                </td>
                                <td>{{ jdate($product->created_at)->format('Y/m/d') }}</td>
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm" title="نمایش"><i class="fas fa-eye"></i></a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm" title="ویرایش"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-danger btn-sm" onclick="return confirm('آیا مطمئن هستید؟')" title="حذف"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-danger">محصولی یافت نشد.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

<!-- MODAL نمایش و پرینت بارکدها -->
<div class="modal fade" id="printBarcodesModal" tabindex="-1" aria-labelledby="printBarcodesModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl barcode-modal-print" style="min-width:900px">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="printBarcodesModalLabel"><i class="fas fa-barcode"></i> بارکد محصولات انتخابی</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="بستن"></button>
      </div>
      <div class="modal-body" id="barcodesModalBody" style="overflow:auto;">
        <!-- بارکدها اینجا تولید می‌شود -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="printBarcodesNowBtn"><i class="fas fa-print"></i> پرینت</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="{{ asset('js/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/vfs_fonts.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
<script>
$(function() {
    // DataTable
    var table = $('#productsTable').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/fa.json'
        },
        dom: 'Bfrtip',
        buttons: [],
        order: [[2, 'asc']],
        pageLength: 25,
        scrollX: false,
        scrollY: false,
        autoWidth: false,
        columnDefs: [
            { orderable: false, targets: [0, 9] }
        ],
        initComplete: function() {
            $('#filter-category, #filter-brand, #filter-stock').trigger('change');
        }
    });

    // رفع اسکرول اضافی
    $('.dataTables_scrollBody').css({ 'overflow-x': 'auto', 'overflow-y': 'visible' });

    // فیلتر دسته‌بندی
    $('#filter-category').on('change', function () {
        var val = $(this).val();
        table.column(4).search(val ? '^'+val+'$' : '', true, false).draw();
    });
    // فیلتر برند
    $('#filter-brand').on('change', function () {
        var val = $(this).val();
        table.column(5).search(val ? '^'+val+'$' : '', true, false).draw();
    });
    // فیلتر موجودی
    $('#filter-stock').on('change', function () {
        var val = $(this).val();
        if(val == "in")
            table.column(7).search('موجود', true, false).draw();
        else if(val == "low")
            table.column(7).search('کم', true, false).draw();
        else if(val == "out")
            table.column(7).search('ناموجود', true, false).draw();
        else
            table.column(7).search('').draw();
    });

    // فیلتر تاریخ ثبت
    $('#filter-date').daterangepicker({
        autoUpdateInput: false,
        locale: { cancelLabel: 'پاک کن', applyLabel: 'اعمال', format: 'YYYY/MM/DD' }
    });
    $('#filter-date').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            let date = data[8];
            let range = $('#filter-date').val().split(' - ');
            if(range.length != 2) return true;
            return (date >= range[0] && date <= range[1]);
        });
        table.draw();
        $.fn.dataTable.ext.search.pop();
    });
    $('#filter-date').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        table.draw();
    });

    // انتخاب/عدم انتخاب همه محصولات
    $('#selectAllProducts').on('change', function(){
        $('.product-select-checkbox').prop('checked', this.checked);
    });
    $(document).on('change', '.product-select-checkbox', function(){
        if (!this.checked) $('#selectAllProducts').prop('checked', false);
    });

    // دکمه چاپ بارکد محصولات انتخابی
    $('#printSelectedBarcodesBtn').on('click', function() {
        let selectedRows = $('.product-select-checkbox:checked').closest('tr');
        if(selectedRows.length === 0) {
            alert('حداقل یک محصول را انتخاب کنید.');
            return;
        }
        let modalBody = $('#barcodesModalBody').empty();
        // مشخصات لیبل
        const labelsPerRow = 4; // در هر ردیف
        const labelWidthMM = 50;
        const labelHeightMM = 35;
        let html = '<table class="barcode-grid-table" cellspacing="0" cellpadding="0"><tbody>';

        selectedRows.each(function(i) {
            if (i % labelsPerRow === 0) html += '<tr>';
            let $tr = $(this);
            let name = $tr.data('name');
            let code = $tr.data('code');
            let barcode = $tr.data('barcode') || code;
            let id = $tr.data('id');
            let barcodeId = 'barcode-svg-' + id + '-' + Math.random().toString(36).substr(2,5);
            html += `<td class="barcode-label-td">
                <div class="barcode-label-content">
                    <div class="barcode-label-name">${name}</div>
                    <svg id="${barcodeId}" class="barcode-svg"></svg>
                    <div class="barcode-text">${barcode}</div>
                </div>
            </td>`;
            if ((i+1) % labelsPerRow === 0) html += '</tr>';
        });
        // اگر ردیف ناقص بود با سلول خالی پر کن
        let remain = selectedRows.length % labelsPerRow;
        if (remain > 0) {
            for(let j=0;j<labelsPerRow-remain;j++) html += '<td></td>';
            html += '</tr>';
        }
        html += '</tbody></table>';
        modalBody.html(html);

        // بارکدها را بساز
        let rendered = 0;
        selectedRows.each(function(i) {
            let $tr = $(this);
            let code = $tr.data('code');
            let barcode = $tr.data('barcode') || code;
            let id = $tr.data('id');
            let cell = modalBody.find('svg').eq(i);
            if (cell.length) {
                JsBarcode(cell[0], barcode, {
                    format: "CODE128",
                    lineColor: "#222",
                    width: 1.2,
                    height: 22,
                    displayValue: false,
                    margin: 0,
                    fontSize: 0,
                });
            }
        });

        $('#printBarcodesModal').modal('show');
    });

    // پرینت بارکدها در مودال
    $('#printBarcodesNowBtn').on('click', function() {
        let printContents = $('#barcodesModalBody').html();
        let win = window.open('', '', 'width=900,height=1100');
        win.document.write(`
            <html>
            <head>
                <title>چاپ بارکد محصولات</title>
                <style>
                    @media print {
                        @page { size: A4; margin: 7mm 5mm 7mm 5mm; }
                        body { direction: rtl; background: #fff; }
                        .barcode-grid-table { width: 100%; border-collapse: collapse; table-layout: fixed;}
                        .barcode-label-td { border: 0.5pt dashed #bbb; width: 50mm; height: 35mm; padding: 2mm; vertical-align: top;}
                        .barcode-label-content { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;}
                        .barcode-label-name { font-size: 12px; font-weight: bold; margin-bottom: 2mm; }
                        .barcode-text { font-size: 10px; margin-top: 2mm;}
                        svg.barcode-svg { width: 41mm !important; height: 22mm !important; }
                    }
                    .barcode-grid-table { width:100%; border-collapse:collapse; }
                    .barcode-label-td { border: 0.5pt dashed #bbb; width: 50mm; height: 35mm; padding: 2mm; vertical-align: top; }
                    .barcode-label-content { width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center;}
                    .barcode-label-name { font-size: 12px; font-weight: bold; margin-bottom: 2mm; }
                    .barcode-text { font-size: 10px; margin-top: 2mm;}
                    svg.barcode-svg { width: 41mm !important; height: 22mm !important; }
                </style>
            </head>
            <body onload="window.print(); setTimeout(()=>window.close(),750);">
                ${printContents}
            </body>
            </html>
        `);
        win.document.close();
    });
});
</script>
@endsection
