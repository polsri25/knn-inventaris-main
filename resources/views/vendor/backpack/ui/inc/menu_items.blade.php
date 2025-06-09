{{-- This file is used for menu items by any Backpack v6 theme --}}
{{-- <x-backpack::menu-item title="Dashboard Barang" icon="la la-dashboard" :link="backpack_url('dashboard-barang')" /> --}}
@if (backpack_user()->role === 'staff')
	<x-backpack::menu-item title="Barang Baru" icon="la la-plus-square" :link="backpack_url('new-item')" />
@elseif(backpack_user()->role === 'admin')
	<x-backpack::menu-item title="Gudang" icon="la la-warehouse" :link="backpack_url('gudang')" />
	<x-backpack::menu-item title="Jenis Barang" icon="la la-list" :link="backpack_url('jenis-barang')" />
	<x-backpack::menu-item title="Users" icon="la la-users" :link="backpack_url('user')" />
	<x-backpack::menu-item title="History Barang" icon="la la-history" :link="backpack_url('history-barang')" />
@elseif(backpack_user()->role === 'pimpinan')
	<x-backpack::menu-item title="History Barang" icon="la la-history" :link="backpack_url('history-barang')" />
	<x-backpack::menu-item title="Gudang" icon="la la-warehouse" :link="backpack_url('gudang')" />
	<x-backpack::menu-item title="Barang Baru" icon="la la-plus-square" :link="backpack_url('new-item')" />
	<x-backpack::menu-item title="Jenis Barang" icon="la la-list" :link="backpack_url('jenis-barang')" />
	<x-backpack::menu-item title="Users" icon="la la-users" :link="backpack_url('user')" />
@endif
