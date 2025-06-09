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
                    <div class="col-12 text-center mb-4">
                        <img src="' .
	            asset('img/gita.png') .
	            '" width="100%" alt="PT Haleyora Powerindo Logo" class="img-fluid" />
                    </div>
                    <div class="col-12">
                        <div class="row justify-content-center">
                            <div class="col-md-6 mb-4 mb-md-0 text-center">
                                <h2 class="fw-bold">VISI</h2>
                                <p class="mb-0">"Menjadi Perusahaan Teknologi Informasi & Penyedia Jasa Internet Terkemuka yang concern & terdepan dalam menghasilkan Produk & Jasa IT sebagai total solusi terkini, terintegrasi, sinergis & profesional."</p>
                            </div>
                            <div class="col-md-6 text-center">
                                <h2 class="fw-bold">MISI</h2>
                                <p class="mb-0">"Memfokuskan pelayanan dalam bidang pengembang Jaringan Infrastruktur Internet baik perusahaan maupun individual & telah dipercaya memberikan layanan yang berkualitas diseluruh nusantara."</p>
                            </div>
                        </div>
                    </div>
                </div>',
	        'content_class' => backpack_theme_config('layout') === 'horizontal_overlap' ? 'text-white' : '',
	    ];
	}
@endphp
