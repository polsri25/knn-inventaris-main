<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\NewItemRequest;
use App\Models\Gudang;
use App\Models\HistoryBarang;
use App\Models\JenisBarang;
use App\Models\KnnClassification;
use App\Services\KnnBarangService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\DB;

/**
 * Class NewItemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class NewItemCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    protected $knnService;
    public function setup()
    {
        CRUD::setModel(HistoryBarang::class); // tetap pakai model ini
        CRUD::setRoute(config('backpack.base.route_prefix') . '/new-item');
        CRUD::setEntityNameStrings('Barang', 'Input Barang Baru');
        // $this->knnService = new \App\Services\KnnBarangService();
        CRUD::addClause('whereHas', 'knnClassification');
        Widget::add()->type('script')->content('assets/js/admin/forms/newItem.js');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(NewItemRequest::class);

        CRUD::addField([
            'name' => 'gudang_id',
            'label' => 'Gudang',
            'type' => 'select',
            'entity' => 'gudang',
            'model' => \App\Models\Gudang::class,
            'attribute' => 'nama',
        ]);

        CRUD::addField([
            'name' => 'jenisbarang_id',
            'label' => 'Jenis Barang',
            'type' => 'select',
            'entity' => 'jenisbarang',
            'model' => \App\Models\JenisBarang::class,
            'attribute' => 'nama',
            'options' => (function ($query) {
                return $query->orderByRaw('LEFT(nama, 1)')->orderBy('nama')->get();
            }),
        ]);

        // CRUD::addField([
        //     'name' => 'tipe',
        //     'label' => 'Tipe Transaksi',
        //     'type' => 'select_from_array',
        //     'options' => ['keluar' => 'Keluar', 'masuk' => 'Masuk'],
        // ]);

        CRUD::addField([
            'name' => 'prioritas',
            'label' => 'Prioritas',
            'type' => 'select_from_array',
            'options' => ['tinggi' => 'Tinggi', 'sedang' => 'Sedang', 'rendah' => 'Rendah'],
            'wrapperAttributes' => [
                'id' => 'prioritas-wrapper',
                'style' => 'display:none',
            ],
        ]);

        CRUD::addField([
            'name' => 'jumlah',
            'type' => 'number',
            'label' => 'Jumlah',
        ]);

        CRUD::addField([
            'name' => 'k_value',
            'label' => 'Nilai K',
            'type' => 'number',
            'default' => 3,
            'attributes' => [
                'min' => 1,
                'step' => 1,
            ],
        ]);

        $this->crud->removeSaveActions(['save_and_back', 'save_and_edit', 'save_and_new']);

        $this->crud->addSaveAction([
            'name' => 'predict_and_show',
            'button_text' => 'Simpan & Tampilkan Hasil',
            'visible' => function ($crud) {
                return true;
            },
            'after_save' => function ($crud, $request, $entry) {},
            'redirect' => function ($crud, $request, $itemId) {
                return backpack_url('new-item/knn-preview');
            }
        ]);
    }

    protected function setupUpdateOperation()
    {
        // disable update, optional
        // abort(403, 'Update tidak diizinkan untuk New Item');
    }

    protected function setupListOperation()
    {
        CRUD::setFromDb();

        CRUD::column('gudang_id')->label('Gudang')->type('select')
            ->entity('gudang')
            ->model(\App\Models\Gudang::class)
            ->attribute('nama');

        CRUD::column('jenisbarang_id')->label('Jenis Barang')->type('select')
            ->entity('jenisbarang')
            ->model(\App\Models\JenisBarang::class)
            ->attribute('nama');

        CRUD::column('prioritas')->label('Prioritas')->type('select_from_array')
            ->options(['tinggi' => 'Tinggi', 'sedang' => 'Sedang', 'rendah' => 'Rendah']);

        if (backpack_user()->role === 'pimpinan') {
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('delete');
            $this->crud->denyAccess('update');
        }
        if (backpack_user()->role == 'pimpinan') {
            $this->crud->addButtonFromView('top', 'print_report', 'print_report_button', 'beginning');
        }

        $this->crud->addButtonFromView('line', 'showStatistics', 'setuplist_button', 'beginning');
        $this->crud->removeButton('show');
        $this->crud->removeButton('update');
    }

    public function showStatistics($id = null)
    {
        // Gunakan session jika ID tidak disediakan
        if (!$id) {
            $id = session('latest_knn_log_id');
            $result = KnnClassification::with('barang.gudang')->findOrFail($id);
        } else {
            $result = KnnClassification::where('barang_id', $id)->with('barang.gudang')->firstOrFail();
        }

        // Decode neighbors jika masih dalam format string JSON
        $neighbors = is_string($result->neighbors)
            ? json_decode($result->neighbors, true)
            : $result->neighbors;

        $k = count($neighbors);

        // Tambahkan nama gudang & nama barang ke masing-masing neighbor
        $neighborsWithDetail = collect($neighbors)->map(function ($neighbor) {
            $gudangId = $neighbor['original_data']['gudang_id'] ?? null;
            $jenisBarangId = $neighbor['original_data']['jenisbarang_id'] ?? null;

            // Ambil nama Gudang dan Nama Barang
            $gudangName = $gudangId ? Gudang::find($gudangId)?->nama : 'Tidak diketahui';
            $namaBarang = $jenisBarangId ? JenisBarang::find($jenisBarangId)?->nama : 'Tidak diketahui';

            // Tambahkan informasi tambahan ke neighbor
            $neighbor['original_data']['nama_gudang'] = $gudangName;
            $neighbor['original_data']['nama_barang'] = $namaBarang;

            return $neighbor;
        })->toArray();

        // Data barang input (hasil klasifikasi)
        $inputBarang = [
            'barang_id'   => $result->barang_id,
            'gudang_id'   => optional($result->barang)->gudang_id,
            'jumlah'      => optional($result->barang)->jumlah,
            'nama_gudang' => optional($result->barang->gudang)->nama,
            'nama_barang' => optional($result->barang->jenisbarang)->nama,
        ];

        // Ambil deskripsi pengaturan untuk barang yang bersangkutan
        $deskripsiPengaturan = $this->getDeskripsiPengaturan($result->barang->jenisbarang_id, $inputBarang['gudang_id'], $result->barang->prioritas);

        // dd([
        //     'neighbors'      => $neighborsWithDetail,
        //     'k'              => $k,
        //     'inputBarang'    => $inputBarang,
        //     'predictedClass' => $result->barang->prioritas,
        //     'deskripsiPengaturan' => $deskripsiPengaturan,
        // ]);

        return view('knn_statistics', [
            'neighbors'          => $neighborsWithDetail,
            'k'                  => $k,
            'inputBarang'        => $inputBarang,
            'predictedClass'     => $result->barang->prioritas,
            'deskripsiPengaturan' => $deskripsiPengaturan, // Menambahkan deskripsi ke view
        ]);
    }

    private function getDeskripsiPengaturan($jenisBarangId, $gudangId, $prioritas)
    {
        // dd($jenisBarangId, $gudangId, $prioritas);
        // Mencari deskripsi pengaturan barang berdasarkan jenis barang, gudang dan prioritas
        $deskripsi = DB::table('deskripsihistoris')
            ->where('jenisbarang_id', $jenisBarangId)
            ->where('gudang_id', $gudangId)
            ->whereRaw('LOWER(prioritas) = ?', [strtolower($prioritas)])
            ->first();
        return $deskripsi ? $deskripsi->deskripsi_pengaturan : 'Deskripsi tidak ditemukan';
    }

    public function store()
    {
        $request = $this->crud->getRequest();

        $jenisbarangId = $request->input('jenisbarang_id');
        $gudangId = $request->input('gudang_id');
        $jumlah = $request->input('jumlah');

        // Ambil nilai K dari input atau default ke 3
        $kValue = (int) $request->input('k_value', 3);

        // Inisialisasi service saat ini saja
        $knnService = new \App\Services\KnnBarangService($kValue);

        // Prediksi
        $knnResult = $knnService->predictWithNeighbors($gudangId, $jumlah);
        $prediction = $knnResult['prediction'];

        // Tambahkan ke request
        $request->merge(['prioritas' => $prediction]);

        $this->crud->setRequest($request);
        $this->crud->validateRequest();

        $barang = $this->crud->create($this->crud->getStrippedSaveRequest($request));

        // Simpan log klasifikasi
        $knnService->logPrediction(
            $request->all(),
            $prediction,
            $knnResult,
            $barang
        );

        \Alert::success("Data berhasil ditambahkan dengan prioritas: <strong>$prediction</strong>")->flash();


        // return redirect()->to($this->crud->route);
        return redirect()->to(backpack_url('new-item/knn-preview'));
    }


    public function getJenisBarang($gudangId)
    {
        // Mengambil jenis barang berdasarkan history_barang di gudang tertentu
        $jenisBarang = HistoryBarang::where('gudang_id', $gudangId)
            ->with('jenisbarang') // Men-load relasi jenis_barang
            ->get()
            ->pluck('jenisbarang') // Mengambil collection jenis_barang
            ->unique('id'); // Menghindari duplikasi

        return response()->json($jenisBarang);
    }

    public function printReport()
    {
        $items = HistoryBarang::with(['gudang', 'jenisbarang', 'knnClassification'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalTinggi = $items->where('prioritas', 'tinggi')->sum('jumlah');
        $totalSedang = $items->where('prioritas', 'sedang')->sum('jumlah');
        $totalRendah = $items->where('prioritas', 'rendah')->sum('jumlah');
        $totalSemua = $items->sum('jumlah');

        return view('vendor.backpack.crud.print_report_history', [
            'items' => $items,
            'totalTinggi' => $totalTinggi,
            'totalSedang' => $totalSedang,
            'totalRendah' => $totalRendah,
            'totalSemua' => $totalSemua,
            'tanggalCetak' => now()->format('d F Y H:i:s'),
        ]);
    }
}
