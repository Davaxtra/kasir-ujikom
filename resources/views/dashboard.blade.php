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
                <a href="{{ route('kategori.index') }}" class="small-box-footer">
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
                <a href="{{ route('produk.index') }}" class="small-box-footer">
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
                    <h3>{{ $totalTransaksi }}</h3>

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
    <div class="row">
        {{-- chart Pendapatan --}}
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Grafik Pendapatan</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        <canvas id="barChart"
                            style="min-height: 300px; height: 350px; max-height: 400px; max-width: 100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    
        {{-- chart produk terlaris --}}
        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Produk Terlaris</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="donutChart"
                        style="min-height: 300px; height: 350px; max-height: 500px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
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
<script>
    var ctx = document.getElementById('barChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($dataChart['labels']),
                datasets: [{
                    label: 'Pendapatan',
                    data: @json($dataChart['data']),
                    backgroundColor: 'rgba(66, 135, 245, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        var ctx = document.getElementById('donutChart').getContext('2d');
        var myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($dataDonut['labels']),
                datasets: [{
                    data: @json($dataDonut['data']),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    borderWidth: 1
                }]
            },
        });
</script>
@stop