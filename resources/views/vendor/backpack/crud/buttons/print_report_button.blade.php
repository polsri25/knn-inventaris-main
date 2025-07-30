@if ($crud->hasAccess('list'))
    <a href="{{ url($crud->route.'/print-report') }}" target="_blank" class="btn btn-primary" data-style="zoom-in">
        <span class="ladda-label">
            <i class="la la-print"></i> Cetak Laporan
        </span>
    </a>
@endif
