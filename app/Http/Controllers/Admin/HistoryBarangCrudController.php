<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HistoryBarangRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class HistoryBarangCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class HistoryBarangCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\HistoryBarang::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/history-barang');
        CRUD::setEntityNameStrings('history barang', 'history barangs');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
        CRUD::column('gudang_id')->label('Gudang')->type('select')
            ->entity('gudang') // nama fungsi relasi di model
            ->model(\App\Models\Gudang::class)
            ->attribute('nama'); // field yang ingin ditampilkan (misal: 'nama')

        CRUD::column('jenisbarang_id')->label('Jenis Barang')->type('select')
            ->entity('jenisbarang') // nama fungsi relasi di model
            ->model(\App\Models\JenisBarang::class)
            ->attribute('nama'); // field yang ingin ditampilkan (misal: 'nama')

        CRUD::column('prioritas')->label('Prioritas')->type('select_from_array')
            ->options(['tinggi' => 'Tinggi', 'sedang' => 'Sedang', 'rendah' => 'Rendah']);

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
        if (backpack_user()->role === 'pimpinan') {
            $this->crud->denyAccess('create');
            $this->crud->denyAccess('delete');
            $this->crud->denyAccess('update');
        }
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(HistoryBarangRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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
            // 'wrapperAttributes' => [
            //     'id' => 'prioritas-wrapper',
            //     'style' => 'display:none',
            // ],
        ]);

        CRUD::addField([
            'name' => 'jumlah',
            'type' => 'number',
            'label' => 'Jumlah',
        ]);

        // CRUD::addField([
        //     'name' => 'keterangan',
        //     'type' => 'textarea',
        //     'label' => 'Keterangan',
        // ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
