<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\GudangRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GudangCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GudangCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Gudang::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/gudang');
        CRUD::setEntityNameStrings('gudang', 'gudang');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('nama');
        CRUD::column('kode');
        CRUD::column('alamat');

        // Tambahkan kolom custom untuk menampilkan jumlah history
        CRUD::addColumn([
            'name' => 'history_count',
            'label' => 'Jumlah History Barang',
            'type' => 'closure',
            'function' => function ($entry) {
                return $entry->historyBarangs->sum('jumlah');
            }
        ]);

        // Jika perlu membatasi akses untuk pimpinan
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
        CRUD::setValidation(GudangRequest::class);
        CRUD::setFromDb(); // set fields from db columns.

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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
