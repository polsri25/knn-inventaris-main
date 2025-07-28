@extends(backpack_view('blank')) {{-- Atau layout Backpack v6: backpack_view('theme-tabler::layouts.top_left') --}}

@section('content')
	<div class="container-fluid py-4">
		<!-- Header -->
		<section class="container-fluid">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<h2>
						<span class="text-capitalize">Hasil Klasifikasi KNN</span>
						<br><small class="text-muted">Detail klasifikasi Barang Baru.</small>
					</h2>
				</div>
				<div>
					{{-- <a href="{{ backpack_url('candidate/create') }}" class="btn btn-secondary me-2">Input Kandidat Lain</a> --}}
					<a href="{{ backpack_url('new-item') }}" class="btn btn-outline-danger">Tutup</a>
				</div>
			</div>
		</section>
		@if (!empty($deskripsiPengaturan))
			<div class="row mt-3">
				<div class="col-md-12">
					<div class="alert alert-warning border-warning shadow-sm" role="alert"
						style="font-size: 1.15rem; font-weight: 500;">
						<i class="la la-exclamation-triangle me-2" style="font-size: 1.5rem; vertical-align: middle;"></i>
						<span class="text-dark">
							<strong class="text-danger" style="font-size: 1.1rem;">
								<i class="la la-lightbulb me-1"></i>
								Saran Pengaturan Barang Prioritas
								@php
									$badgeClass = 'bg-secondary';
									switch (strtolower($predictedClass)) {
									    case 'tinggi':
									        $badgeClass = 'bg-danger';
									        break;
									    case 'sedang':
									        $badgeClass = 'bg-warning text-dark';
									        break;
									    case 'rendah':
									        $badgeClass = 'bg-success';
									        break;
									}
								@endphp
								<span class="badge {{ $badgeClass }} text-uppercase ms-1" style="font-size: 1rem;">
									{{ $predictedClass }}
								</span>:
							</strong>
							<br>
							{{ $deskripsiPengaturan }}
						</span>
					</div>
				</div>
			</div>
		@endif
		<!-- Hasil Prediksi Card -->
		<div class="row">
			<!-- Blok 2: Jumlah K -->
			<div class="col-md-4 d-flex align-items-stretch">
				<div class="card w-100">
					<div class="card-header">
						Jumlah K
					</div>
					<div class="card-body text-center d-flex flex-column justify-content-center">
						<h1 id="jumlah-k" class="display-4"></h1>
						<p class="text-muted">Jumlah tetangga terdekat yang digunakan</p>
					</div>
				</div>
			</div>
			<!-- Blok 3: Pie Chart Jarak Neighbors -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						Distribusi Prioritas Tetangga (Pie Chart)
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-8">
								<canvas id="pieJarakNeighbors" style="max-height: 200px;"></canvas>
							</div>
							<div class="col-md-4">
								<div id="chart-legend" class="chart-legend">
									<!-- Legend akan diisi oleh JavaScript -->
								</div>
							</div>
						</div>
						<p class="text-muted mt-2">Persentase prioritas dari tetangga terdekat</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Visualisasi Chart -->
		<div class="row mt-3">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">Visualisasi Scatter Plot</div>
					<div class="card-body">
						<canvas id="knnChart" style="height: 400px;"></canvas>
					</div>
				</div>
			</div>
		</div>

		<!-- Tetangga Terdekat Card -->
		<div class="row mt-3">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header"><span id="neighbors-count">5</span> Tetangga Terdekat yang Ditemukan:</div>
					<div class="card-body">
						<div class="table-responsive">
							<table class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>#</th>
										<th>ID Barang</th>
										<th>Gudang</th>
										<th>Kuantitas</th>
										<th>Jarak</th>
										<th>Prioritas</th>
									</tr>
								</thead>
								<tbody id="neighbors-table"></tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>


		<!-- Action Buttons -->
		{{-- <div class="row mt-3">
			<div class="col-md-12 text-center">
				<button class="btn btn-secondary" onclick="inputKandidatLain()">Input Kandidat Lain</button>
				<button class="btn btn-primary" onclick="lihatDaftarKandidat()">Lihat Daftar Kandidat</button>
			</div>
		</div> --}}
	</div>

	@php
		$prioritasCounts = collect($neighbors)->pluck('original_data')->groupBy('prioritas')->map->count();
	@endphp
@endsection

@push('after_styles')
	<style>
		.chart-legend {
			padding: 10px 0;
		}

		.legend-item {
			display: flex;
			align-items: center;
			margin-bottom: 8px;
			font-size: 12px;
			cursor: pointer;
			padding: 4px;
			border-radius: 4px;
			transition: background-color 0.2s;
		}

		.legend-item:hover {
			background-color: #f8f9fa;
		}

		.legend-color {
			width: 16px;
			height: 16px;
			border-radius: 3px;
			margin-right: 8px;
			flex-shrink: 0;
		}

		.legend-text {
			flex: 1;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.legend-value {
			font-weight: bold;
			margin-left: 5px;
			color: #6c757d;
		}

		.legend-item.hidden {
			opacity: 0.5;
			text-decoration: line-through;
		}
	</style>
@endpush

@push('after_scripts')
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<script>
		let pieChart;
		let scatterChart;

		const data = {
			neighbors: @json($neighbors),
			k: {{ $k }},
			inputBarang: @json($inputBarang),
			predictedClass: "{{ $predictedClass }}"
		};

		// Data pie chart
		const pieChartData = {
			labels: ['Prioritas Tinggi', 'Prioritas Sedang', 'Prioritas Rendah'],
			counts: [
				{{ $prioritasCounts['tinggi'] ?? 0 }},
				{{ $prioritasCounts['sedang'] ?? 0 }},
				{{ $prioritasCounts['rendah'] ?? 0 }}
			],
			colors: ['#e74a3b', '#f6c23e', '#1cc88a']
		};

		// Hitung total di JavaScript
		const total = pieChartData.counts.reduce((sum, count) => sum + count, 0);

		// Hitung persentase di JavaScript
		pieChartData.data = pieChartData.counts.map(count =>
			total > 0 ? parseFloat((count / total * 100).toFixed(1)) : 0
		);

		// Function to destroy all existing charts
		function destroyExistingCharts() {
			if (pieChart) {
				pieChart.destroy();
				pieChart = null;
			}
			if (scatterChart) {
				scatterChart.destroy();
				scatterChart = null;
			}
		}

		function initializePieChart() {
			// Destroy existing chart if it exists
			if (pieChart) {
				pieChart.destroy();
			}

			var ctx = document.getElementById('pieJarakNeighbors').getContext('2d');
			pieChart = new Chart(ctx, {
				type: 'pie',
				data: {
					labels: pieChartData.labels,
					datasets: [{
						data: pieChartData.data,
						backgroundColor: pieChartData.colors,
						borderWidth: 2,
						borderColor: '#ffffff'
					}]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							display: false // Matikan legend bawaan
						},
						tooltip: {
							callbacks: {
								label: function(context) {
									const label = context.label || '';
									const value = context.parsed;
									const total = context.dataset.data.reduce((a, b) => a + b, 0);
									const percentage = ((value / total) * 100).toFixed(1);
									return `${label}: ${value}% (${percentage}% dari total)`;
								}
							}
						}
					}
				}
			});
		}

		// Initialize scatter plot
		function initializeScatterChart() {
			// Destroy existing chart if it exists
			if (scatterChart) {
				scatterChart.destroy();
			}

			const ctx = document.getElementById('knnChart').getContext('2d');

			// Prepare data for scatter plot
			const scatterData = prepareScatterData();

			scatterChart = new Chart(ctx, {
				type: 'scatter',
				data: {
					datasets: scatterData.datasets
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						title: {
							display: true,
							text: 'Visualisasi KNN - Tetangga Terdekat',
							font: {
								size: 16
							}
						},
						legend: {
							display: true,
							position: 'top',
							labels: {
								usePointStyle: true,
								padding: 20
							}
						},
						tooltip: {
							callbacks: {
								title: function(context) {
									const point = context[0];
									const datasetLabel = point.dataset.label;
									return datasetLabel;
								},
								label: function(context) {
									const dataPoint = context.raw;
									return [
										`Jarak: ${dataPoint.x.toFixed(3)}`,
										`Kuantitas: ${dataPoint.y}`,
										`ID Barang: BRG-${dataPoint.id}`,
										`Gudang: ${dataPoint.gudang}`,
										`Prioritas: ${dataPoint.prioritas}`
									];
								}
							}
						}
					},
					scales: {
						x: {
							title: {
								display: true,
								text: 'Jarak Euclidean'
							},
							beginAtZero: true
						},
						y: {
							title: {
								display: true,
								text: 'Kuantitas Barang'
							},
							beginAtZero: true
						}
					},
					elements: {
						point: {
							radius: 8,
							hoverRadius: 12,
							borderWidth: 2
						}
					}
				}
			});
		}

		function prepareScatterData() {
			// Color mapping for priorities
			const priorityColors = {
				'tinggi': {
					background: 'rgba(231, 74, 59, 0.7)', // Red
					border: 'rgba(231, 74, 59, 1)'
				},
				'sedang': {
					background: 'rgba(246, 194, 62, 0.7)', // Yellow
					border: 'rgba(246, 194, 62, 1)'
				},
				'rendah': {
					background: 'rgba(28, 200, 138, 0.7)', // Green
					border: 'rgba(28, 200, 138, 1)'
				}
			};

			// Group neighbors by priority
			const groupedByPriority = {};
			data.neighbors.forEach(neighbor => {
				const priority = neighbor.original_data.prioritas;
				if (!groupedByPriority[priority]) {
					groupedByPriority[priority] = [];
				}
				groupedByPriority[priority].push({
					x: neighbor.distance,
					y: neighbor.original_data.jumlah,
					id: neighbor.original_data.id,
					gudang: neighbor.original_data.nama_gudang,
					prioritas: neighbor.original_data.prioritas,
					namaBarang: neighbor.original_data.nama_barang
				});
			});

			// Create datasets for each priority level
			const datasets = [];
			Object.keys(groupedByPriority).forEach(priority => {
				const color = priorityColors[priority] || {
					background: 'rgba(108, 117, 125, 0.7)',
					border: 'rgba(108, 117, 125, 1)'
				};

				datasets.push({
					label: `Prioritas ${priority.charAt(0).toUpperCase() + priority.slice(1)}`,
					data: groupedByPriority[priority],
					backgroundColor: color.background,
					borderColor: color.border,
					pointBorderWidth: 2,
					pointRadius: 8,
					pointHoverRadius: 12
				});
			});

			// Add input barang as a special point
			if (data.inputBarang) {
				datasets.push({
					label: 'Barang Input (Baru)',
					data: [{
						x: 0, // Input barang has distance 0 from itself
						y: data.inputBarang.jumlah,
						id: data.inputBarang.barang_id || 'New',
						gudang: data.inputBarang.nama_gudang,
						prioritas: data.predictedClass,
						namaBarang: data.inputBarang.nama_barang
					}],
					backgroundColor: 'rgba(0, 123, 255, 0.8)',
					borderColor: 'rgba(0, 123, 255, 1)',
					pointBorderWidth: 3,
					pointRadius: 12,
					pointHoverRadius: 15,
					pointStyle: 'star'
				});
			}

			return {
				datasets
			};
		}

		function populateNeighborsTable(data) {
			const tableBody = document.getElementById('neighbors-table');
			tableBody.innerHTML = '';

			if (!data.neighbors || data.neighbors.length === 0) {
				tableBody.innerHTML = '<tr><td colspan="6">Tidak ada data tetangga.</td></tr>';
				return;
			}

			data.neighbors.forEach((neighbor, index) => {
				const original = neighbor.original_data;
				const row = document.createElement('tr');
				row.style.cursor = 'pointer';
				row.innerHTML = `
				<td>${index + 1}</td>
				<td>BRG-${original.id}</td>
				<td>${original.nama_gudang}</td>
				<td>${original.jumlah}</td>
				<td>${neighbor.distance.toFixed(3)}</td>
				<td><span class="badge bg-${getPriorityBadgeColor(original.prioritas)}">${original.prioritas}</span></td>
			`;

				// Add click handler for highlighting
				row.addEventListener('click', function() {
					// Remove previous highlights
					document.querySelectorAll('#neighbors-table tr').forEach(r => r.classList.remove(
						'table-active'));
					// Highlight clicked row
					this.classList.add('table-active');
					// Highlight corresponding point in chart
					highlightPoint(index);
				});

				tableBody.appendChild(row);
			});
		}

		function getPriorityBadgeColor(priority) {
			switch (priority.toLowerCase()) {
				case 'tinggi':
					return 'red';
				case 'sedang':
					return 'yellow';
				case 'rendah':
					return 'green';
				default:
					return 'secondary';
			}
		}

		function createCustomLegend() {
			const legendContainer = document.getElementById('chart-legend');
			legendContainer.innerHTML = ''; // Clear existing legend
			const total = pieChartData.data.reduce((a, b) => a + b, 0);

			pieChartData.labels.forEach((label, index) => {
				const value = pieChartData.data[index];
				const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;

				const legendItem = document.createElement('div');
				legendItem.className = 'legend-item';
				legendItem.setAttribute('data-index', index);

				legendItem.innerHTML = `
				<div class="legend-color" style="background-color: ${pieChartData.colors[index]}"></div>
				<div class="legend-text">${label}</div>
				<div class="legend-value">${value}%</div>
			`;

				// Tambahkan event listener untuk toggle visibility
				legendItem.addEventListener('click', function() {
					toggleDatasetVisibility(index);
				});

				legendContainer.appendChild(legendItem);
			});
		}

		function toggleDatasetVisibility(index) {
			const legendItem = document.querySelector(`[data-index="${index}"]`);

			if (pieChart && pieChart.isDatasetVisible(0)) {
				const meta = pieChart.getDatasetMeta(0);
				const isHidden = meta.data[index].hidden;

				meta.data[index].hidden = !isHidden;
				legendItem.classList.toggle('hidden', !isHidden);

				pieChart.update();
			}
		}

		// Optional: Function to highlight specific points
		function highlightPoint(neighborIndex) {
			if (scatterChart && neighborIndex >= 0 && neighborIndex < data.neighbors.length) {
				// Reset all points
				scatterChart.data.datasets.forEach(dataset => {
					dataset.data.forEach(point => {
						point.radius = 8;
					});
				});

				// Highlight specific point
				const neighbor = data.neighbors[neighborIndex];
				const priority = neighbor.original_data.prioritas;

				scatterChart.data.datasets.forEach(dataset => {
					if (dataset.label.toLowerCase().includes(priority)) {
						dataset.data.forEach(point => {
							if (point.id === neighbor.original_data.id) {
								point.radius = 15;
							}
						});
					}
				});

				scatterChart.update();
			}
		}

		// Fungsi untuk update data chart (jika diperlukan)
		function updatePieChartData(newLabels, newData, newColors) {
			if (pieChart) {
				pieChart.data.labels = newLabels;
				pieChart.data.datasets[0].data = newData;
				pieChart.data.datasets[0].backgroundColor = newColors;
				pieChart.update();

				// Update custom legend
				pieChartData.labels = newLabels;
				pieChartData.data = newData;
				pieChartData.colors = newColors;
				createCustomLegend();
			}
		}

		// Optional: Function to update scatter chart data (if needed)
		function updateScatterChart(newData) {
			if (scatterChart) {
				const scatterData = prepareScatterData();
				scatterChart.data.datasets = scatterData.datasets;
				scatterChart.update();
			}
		}

		// Fungsi untuk tombol navigasi
		function inputKandidatLain() {
			// Implementasi untuk input kandidat lain
			console.log('Input kandidat lain');
		}

		function lihatDaftarKandidat() {
			// Implementasi untuk lihat daftar kandidat
			console.log('Lihat daftar kandidat');
		}

		// Main initialization
		document.addEventListener('DOMContentLoaded', function() {
			if (window.Chart) {
				// Destroy any existing charts first
				destroyExistingCharts();

				// Initialize all components
				initializePieChart();
				createCustomLegend();
				initializeScatterChart();
				populateNeighborsTable(data);

				// Update UI elements
				document.getElementById('jumlah-k').textContent = data.k;
				if (document.getElementById('neighbors-count')) {
					document.getElementById('neighbors-count').textContent = data.k;
				}
			}
		});
	</script>
@endpush
