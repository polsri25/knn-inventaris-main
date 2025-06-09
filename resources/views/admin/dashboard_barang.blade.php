@extends(backpack_view('layouts.plain'))

@section('header')
	<section class="container-fluid">
		<h2 class="font-semibold text-xl">Dashboard Barang</h2>
	</section>
@endsection

@section('content')
	<div class="card p-4">
		<div class="table-responsive">
			<table class="table table-striped" id="dashboardTable">
				<thead>
					<tr>
						<th>Jenis Barang</th>
						<th>Kode Barang</th>
						<th>Gudang</th>
						<th>Total Masuk</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($entries as $entry)
						<tr>
							<td>{{ $entry->jenis_barang }}</td>
							<td>{{ $entry->kode_barang }}</td>
							<td>{{ $entry->gudang }}</td>
							<td>{{ $entry->total_masuk }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection

@push('after_scripts')
	<script>
		new DataTable('#dashboardTable');
	</script>
@endpush
