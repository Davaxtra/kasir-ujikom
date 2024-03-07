@extends('adminlte::page')

@section('title', 'Laporan')

@section('content_header')
<h1>Laporan</h1>
@stop

@section('content')
@section('plugins.Datatables', true)
    <div class="container-fluid">
        <table id="transaction-table" class="table datatable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Kasir</th>
                    <th>Total Harga</th>
                    <th>Bayar</th>
                    <th>Kembalian</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $no = 1;
                @endphp
                @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $transaction->user->name }}</td>
                    <td>Rp. {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($transaction->bayar, 0, ',', '.') }}</td>
                    <td>Rp. {{ number_format($transaction->kembalian, 0, ',', '.') }}</td>
                </tr>
            @endforeach
            
            </tbody>
        </table>
    </div>
@endsection

@section('js')
<script>
    $(document).ready(function() {
        $('#transaction-table').DataTable({
            dom: 'Bfrtip',
            buttons: [
                'pdfHtml5'
            ]
        });
    });
</script>
@endsection