@extends('layouts-admin.app')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Kegiatan' )

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h1 class="page-title">
                        Kegiatan
                    </h2>
                </div>
                <!-- Page title actions -->
                <div class="col-auto ms-auto d-print-none">
                    <a href="./add-kegiatan" class="btn btn-main">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Buat Kegiatan Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <ul class="nav nav-bordered mb-4 nav-tabs" data-bs-toggle="tabs">
                <li class="nav-item">
                    <a href="#tabs-soon" class="nav-link active" data-bs-toggle="tab">Akan Datang</a>
                </li>
                <li class="nav-item">
                    <a href="#tabs-past" class="nav-link" data-bs-toggle="tab">Sudah Terlaksana</a>
                </li>
            </ul>
        <div class="row">
            <div class="tab-content">
                <div class="tab-pane active show" id="tabs-soon">
                    <div class="row row-cards">
                        <div class="col-sm-6 col-lg-4">
                            <div class="card">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(./tabler/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg)"></div>
                                <div class="card-body">
                                    <h3 class="card-title">Judul Kegiatan Baru</h3>
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima
                                    neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                                </div>
                                <div class="card-footer">
                                    <a href="./admin-kegiatan-detail" class="btn btn-main">Tampilkan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(./tabler/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg)"></div>
                                <div class="card-body">
                                    <h3 class="card-title">Judul Kegiatan</h3>
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima
                                    neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                                </div>
                                <div class="card-footer">
                                    <a href="./admin-kegiatan-detail" class="btn btn-main">Tampilkan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(./tabler/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg)"></div>
                                <div class="card-body">
                                    <h3 class="card-title">Judul Kegiatan</h3>
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima
                                    neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                                </div>
                                <div class="card-footer">
                                    <a href="./admin-kegiatan-detail" class="btn btn-main">Tampilkan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(./tabler/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg)"></div>
                                <div class="card-body">
                                    <h3 class="card-title">Judul Kegiatan</h3>
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima
                                    neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                                </div>
                                <div class="card-footer">
                                    <a href="./admin-kegiatan-detail" class="btn btn-main">Tampilkan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <ul class="pagination ms-auto">
                            <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                prev
                            </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item">
                            <a class="page-link" href="#">
                                next <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-pane" id="tabs-past">
                <div class="row row-cards">
                        <div class="col-sm-6 col-lg-4">
                            <div class="card">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(./tabler/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg)"></div>
                                <div class="card-body">
                                    <h3 class="card-title">Judul Kegiatan Lama</h3>
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima
                                    neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                                </div>
                                <div class="card-footer">
                                    <a href="./admin-kegiatan-detail-lampau" class="btn btn-main">Tampilkan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(./tabler/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg)"></div>
                                <div class="card-body">
                                    <h3 class="card-title">Judul Kegiatan</h3>
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima
                                    neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                                </div>
                                <div class="card-footer">
                                    <a href="./admin-kegiatan-detail-lampau" class="btn btn-main">Tampilkan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(./tabler/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg)"></div>
                                <div class="card-body">
                                    <h3 class="card-title">Judul Kegiatan</h3>
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima
                                    neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                                </div>
                                <div class="card-footer">
                                    <a href="./admin-kegiatan-detail-lampau" class="btn btn-main">Tampilkan</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-4">
                            <div class="card">
                                <!-- Photo -->
                                <div class="img-responsive img-responsive-21x9 card-img-top" style="background-image: url(./tabler/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg)"></div>
                                <div class="card-body">
                                    <h3 class="card-title">Judul Kegiatan</h3>
                                    <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit incidunt, iste, itaque minima
                                    neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                                </div>
                                <div class="card-footer">
                                    <a href="./admin-kegiatan-detail-lampau" class="btn btn-main">Tampilkan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <ul class="pagination ms-auto">
                            <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                                <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                                prev
                            </a>
                            </li>
                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                            <li class="page-item active"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">4</a></li>
                            <li class="page-item"><a class="page-link" href="#">5</a></li>
                            <li class="page-item">
                            <a class="page-link" href="#">
                                next <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                            </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection