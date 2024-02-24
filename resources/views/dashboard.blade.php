@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="row">
        {{-- card-no-1 --}}
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalKategori }}</h3>

                    <p>Kategori</p>
                </div>
                <div class="icon">
                    <i class="fas fa-cube"></i>
                </div>
                <a href="{{ route('category.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        {{-- card-no-1-end --}}

        {{-- card-no-2 --}}
        <div class="col-lg-4 col-md-4 col-sm-6">
            <!-- small card -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $totalProduk }}</h3>

                    <p>Produk</p>
                </div>
                <div class="icon">
                    <i class="fas fa-utensils"></i>
                </div>
                <a href="{{ route('product.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        {{-- card-no-2-end --}}

        {{-- card-no-3 --}}
        <div class="col-lg-4 col-md-4 col-sm-6">
            <!-- small card -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>12</h3>

                    <p>Transaksi</p>
                </div>
                <div class="icon">
                    <i class="fas fa-tags"></i>
                </div>
                <a href="#" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        {{-- card-no-3-end --}}
    </div>
</div>

@stop

@section('footer')
    <strong>Copyright &copy; 2024 <a href="https://github.com/Davaxtra">Mohammad Daffa Hafidzan</a>.</strong>
        All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 2.0
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop