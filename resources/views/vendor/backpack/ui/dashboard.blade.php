@extends(backpack_view('blank'))

@php
	if (backpack_theme_config('show_getting_started')) {
	    $widgets['before_content'][] = [
	        'type' => 'view',
	        'view' => backpack_view('inc.getting_started'),
	    ];
	} else {
	    $widgets['before_content'][] = [
	        'type' => 'jumbotron',
	        'heading' => '<h1 class="fw-bold mb-4">SISTEM KLASIFIKASI BARANG INVENTARIS </h1>',
	        'heading_class' =>
	            'display-4 ' . (backpack_theme_config('layout') === 'horizontal_overlap' ? ' text-white' : ''),
	        'content' =>
	            '
                <div class="row align-items-center">
                    <div class="col-md-12 text-center mb-4 mb-md-0">
                        <img src="' .
	            asset('img/gita.png') .
	            '" width="100%" alt="PT Haleyora Powerindo Logo" class="img-fluid" />
                    </div>
                </div>',
	        'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
	    ];
	}
@endphp
