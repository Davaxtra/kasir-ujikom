@extends('adminlte::page')
@section('title', 'Transaksi')

{{-- @section('content_header')
<h1>Transaksi</h1>
@endsection --}}
@section('css')
@parent
<style>
    body {
        overflow: hidden;
    }
</style>
@endsection
@section('content')
{{-- <div class="container-fluid">
    <div class="row">
        <div class="col-md-9">
            @foreach ($product as $item)
            <div class="card" style="width: 10rem;">
                <img src="{{ $item->image }}">
                <div class="card-body">
                    <h5 class="card-title"><strong>{{ $item->name }}</strong></h5><br>
                    <span class="card-text badge badge-info">{{ $item->price }}</span>
                </div>
            </div>
            @endforeach

        </div>
        <div class="col-md-3" style="height: 80vh; display:grid">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <div class="card-title"><strong>Keranjang</strong></div>
                </div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
</div> --}}

<div class="container-fluid">
    <div class="row">
        <div class="col-9" style="max-height: 95vh; overflow-y: auto;">
            <h3>Transaksi</h3>
            <div class="row">
                @foreach ($produk as $item)
                <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-4">
                    <div class="card product-card" data-product-id="{{ $item->id }}"
                        style="width: 13rem; cursor:pointer;" onclick="addToCart('{{ json_encode($item) }}')">
                        <img src="{{ asset('storage/product/' . $item->image) }}" class="card-img-top"
                            alt="{{ $item->name }}" width="200px" height="200px">
                        <div class="card-body">
                            <h5 class="card-title"><b>{{ $item->name }}</b></h5>
                            <br>
                            {{-- <h5 class="card-title">{{ $item->name }}</h5> --}}
                            <p class="card-text badge badge-success">Rp. {{ number_format($item->price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                {{-- @dd($item) --}}
                @endforeach
            </div>
        </div>
        {{-- <div class="col-md-3" style="height: 80vh; overflow-y: auto;">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <div class="card-title"><strong>Keranjang</strong></div>
                </div>
                <div class="card-body" id="cart">
                    {{-- @foreach($cart as $item)
                    <div class="info-box" style="display: inline-block; margin-bottom: 10px;">
                        <div style="display: flex; align-items: center;">
                            <img class="info-box-img" src="" alt="" width="100px" height="100px">
                            <div class="info-box-content" style="margin-left: 10px;">
                                <span class="info-box-text"></span>
                                <span class="info-box-number">Rp. {</span>
                            </div>
                        </div>
                        <div class="info-box-quantity" style="margin-top: 5px;">
                            <span class="info-box-text">Quantity: 10</span>
                        </div>
                    </div>
                    @endforeach
                    <div class="card-footer">
                        <div class="totalprice">
                            Total Harga :
                        </div>
                    </div>
                </div>

            </div>
        </div> --}}
        <div class="col-3" style="height: 80vh;">
            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <div class="card-title"><strong>Keranjang</strong></div>
                </div>
                
                    <div class="card-body" style="overflow-y: auto; min-height:55vh; max-height: 55vh;" id="cart">
                        <p id="empty-cart-msg">Keranjang belanja kosong.</p>
                    </div>
                    <div class="card-footer">
                        <div>
                            <form action="{{ route('transaksi.store') }}" id="formPembayaran" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="total">Total</label>
                                <input type="number" name="total" id="total_price"
                                    class="form-control col-sm-8 col-form-label" readonly>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="bayar">Bayar</label>
                                <input type="number" id="bayar" name="bayar"
                                    class="form-control col-sm-8 col-form-label" oninput="hitungKembalian()">
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-4 col-form-label" for="kembalian">Kembalian</label>
                                <input type="number" id="kembalian" name="kembalian"
                                    class="form-control col-sm-8 col-form-label" readonly>
                            </div>
                            <!-- Input tersembunyi untuk jumlah, subtotal, dan produk_id -->
                            {{-- <input type="hidden" name="jumlah[]" id="jumlah" value="">
                            <input type="hidden" name="subtotal[]" id="subtotal" value="">
                            <input type="hidden" name="produk_id[]" id="produk_id" value=""> --}}
                            <button type="submit" class="btn btn-primary">Cetak</button>
                </form>

            </div>
        </div>
    </div>
</div>

</div>
</div>

<script src="{{ asset('/js/transaksibackup.js')}}"></script>
@endsection