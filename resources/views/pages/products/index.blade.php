@extends('adminlte::page')
@section('title', 'Produk')

@section('content_header')
<h1>Produk</h1>
@stop

@section('content')
<div class="container-fluid">
<div class="with-border">
    <button class="btn btn-primary mb-2" data-bs-toggle="modal" id="createNewProduct"><i class="fas fa-plus mr-1"></i>Tambah Produk</button>
</div>
<table class="table table-striped data-table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th>Name</th>
            <th>Category</th>
            <th>Image</th>
            <th>Stock</th>
            <th>Price</th>
            <th>Action</th>
        </tr>
    </thead>
</table>

{{-- add product modal start --}}
<div class="modal fade" id="productModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="productForm" name="ProductForm" class="form-horizontal">
                    <input type="hidden" name="id" id="id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukan Nama Produk" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="category" class="col-sm-2 control-label">Kategori</label>
                        <div class="col-sm-12">
                            <select class="custom-select catItem" name="category" id="category">
                                <option selected disabled value="">Pilih Kategori</option>
                                @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12">
                            <x-adminlte-input-file name="image" id="image" label="Foto" placeholder="Choose a file..." />
                            {{-- <div class="input-group">
                                     <input type="file" class="form-control" name="image" id="image">
                                    </div> --}}
                            <img id="imagePreview" src="" alt="Preview Image" style="max-width: 100%; max-height: 150px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="stock" class="col-sm-2 control-label">Stok</label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" id="stock" name="stock" placeholder="Masukan Stok Produk" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price" class="col-sm-2 control-label">Harga</label>
                        <div class="col-sm-12">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input type="text" class="form-control" id="price" name="price" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-success" id="savedata" value="create">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
{{-- add product modal end --}}

@endsection
@section('js')
<script>
    // var SITEURL = '{{URL::to('')}}',
    $(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            responsive: true,
            ajax: {
                url: "{{ route('produk.index') }}",
                type: 'GET',
            },
            columns: [{
                    data: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'category_name',
                    name: 'category_name'
                },
                {
                    data: 'image',
                    name: 'image'
                },
                {
                    data: 'stock',
                    name: 'stock'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
        });

        $('#createNewProduct').click(function() {
            $('#savedata').val('#create-product')
            $('#modelHeading').html("Tambah Produk");
            $('#id').val('');
            $('#imagePreview').attr('src', 'https://via.placeholder.com/150');
            $('#productForm').trigger("reset");
            $('#productModal').modal('show');
        })
    })

    $('body').on('click', '.editProduct', function() {
        var product_id = $(this).data('id');
        $.get('produk' + '/' + product_id + '/edit', function(data) {
            $('#savedata').val('#edit-product');
            $('#modelHeading').html("Edit Produk");
            $('#id').val(data.id);
            $('#name').val(data.name);
            $('#category').val(data.category_id);

            var imageUrl = "{{ asset("storage/product") }}" + "/" + data.image;
            $('#image').val(data.imageUrl);
            $('#imagePreview').attr('src', imageUrl);
            if(data.image){
                $('#imagePreview').attr('src',imageUrl);
            }
            $('#stock').val(data.stock);
            $('#price').val(data.price);
            $('#productModal').modal('show');
        })
    })

    $('body').on('submit', '#productForm', function(e) {
        e.preventDefault();
        var actionType = $('#savedata').val();
        $('#savedata').html('Simpan');

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: "{{ route('produk.store') }}",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                Swal.fire(
                    'Cihuyy!',
                    'Data sukses disimpan!',
                    'success'
                )
                $('#productForm').trigger("reset");
                $('#productModal').modal('hide');
                $('.data-table').DataTable().ajax.reload();
            },
            error: function(data) {
                console.log('Error:', data);
                toastr.options.progressBar = true;
                toastr.warning('Data tidak valid!');
            }
        });
    });

    $('body').on('click', '.deleteProduct', function() {
        var id = $(this).data("id");
        Swal.fire({
            title: 'Kamu yakin?',
            text: "Anda tidak akan bisa mengembalikannya!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('produk.store') }}" + '/' + id,
                    success: function(data) {
                        toastr.options.progressBar = true;
                        toastr.success('Data berhasil dihapus!');
                        $('.data-table').DataTable().ajax.reload();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });

    // $('#image').on('change', function(event) {
    // var selectedFile = event.target.files[0];
    // var imageUrl = URL.createObjectURL(selectedFile);
    // $('#imagePreview').attr('src', imageUrl);
    // });

    $('#image').change((e) => {
        const uploaded = e.target.files[0];
        if (uploaded) {
            const reader = new FileReader();
            reader.onload = (e) => {
                $('#imagePreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(uploaded);
        }
    })
</script>
@stop