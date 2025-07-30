@include('layouts.head')

@include('layouts.header')


<!-- ======= Hero Section ======= -->
<section id="hero">
    <div id="heroCarousel" data-bs-interval="5000" class="carousel slide carousel-fade" data-bs-ride="carousel">

        <ol class="carousel-indicators" id="hero-carousel-indicators"></ol>

        <div class="carousel-inner" role="listbox">

            <!-- Slide 1 -->
            <div class="carousel-item active" style="background-image: url(img/slider_gnet-1.jpg)">
                <div class="carousel-container">
                    <div class="container">
                        <h2 class="animate__animated animate__fadeInDown">PT Global Internet Data</h2>
                        <p class="animate__animated animate__fadeInUp">Gita Fusion merupakan brand dari PT Global Internet Data yang memiliki lisensi ISP dari Kementerian Informasi dan Komunikasi RI dan menjadi anggota tetap Asosiasi  Penyelenggara Asosiasi Internet Indonesia (APJII) dengan nomor 734.</p>

                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item" style="background-image: url(img/slider_gnet-2.jpg)">
                <div class="carousel-container">
                    <div class="container">
                        <h2 class="animate__animated animate__fadeInDown">Gita Fusion</h2>
                        <p class="animate__animated animate__fadeInUp">
                            Produk dan layanan Internet Service Platinum, Gold, dan Silver
                        </p>
                    </div>
                </div>
            </div>

            <!-- Slide 3 -->
            <div class="carousel-item" style="background-image: url(img/slider_gnet-3.jpg)">
                <div class="carousel-container">
                    <div class="container">
                        <h2 class="animate__animated animate__fadeInDown">Perangkat yang Tersedia</h2>
                        <p class="animate__animated animate__fadeInUp">Router, Switch, Kabel LAN, Access Point, Modem</p>
                    </div>
                </div>
            </div>

        </div>

        <a class="carousel-control-prev" href="#heroCarousel" role="button" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bi bi-chevron-left" aria-hidden="true"></span>
        </a>

        <a class="carousel-control-next" href="#heroCarousel" role="button" data-bs-slide="next">
            <span class="carousel-control-next-icon bi bi-chevron-right" aria-hidden="true"></span>
        </a>

    </div>
</section><!-- End Hero -->
    <!-- ======= Contact Section ======= -->
    <section id="contact" class="contact">
        <div class="container">

            <div class="section-title">
                <h2>HUBUNGI KAMI</h2>
                <p>PT Global Internet Data</p>
                <P>Hubungi PT Global Internet Data.</P>
            </div>

            <div class="row">

                <div class="col-lg-12 d-flex align-items-stretch">
                    <div class="info">
                        <div class="address">
                            <i class="bi bi-geo-alt"></i>
                            <h4>Lokasi</h4>
                            <p>ruko Villa citra dago, Lorong Satria II No.1, Sukajaya, Kec. Sukarami, Kota Palembang, Sumatera Selatan 30151
                            </p>
                        </div>

                        <div class="email">
                            <i class="bi bi-envelope"></i>
                            <h4>Email:</h4>
                            <p>info@gitafusion.id</p>
                        </div>


                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.5833554327332!2d104.74000001142836!3d-2.9353875970285594!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e3b758fa29bb125%3A0x11e043efd53c1aa7!2sPT%20Global%20Internet%20Data%20(Gita%20Fusion)!5e0!3m2!1sid!2sid!4v1753834384034!5m2!1sid!2sid" frameborder="0" style="border:0; width: 100%; height: 290px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>

                </div>

            </div>

        </div>
    </section><!-- End Contact Section -->

</main><!-- End #main -->

@include('layouts.footer')

