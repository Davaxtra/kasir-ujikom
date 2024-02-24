@extends('adminlte::page')
@section('title', 'Kategori')

@section('content_header')
    <h1>Kategori</h1>
@stop

@section('content')

<div class="with-border">
    <button class="btn btn-primary mb-2" data-bs-toggle="modal" id="createNewCat"><i
        class="fas fa-plus mr-1"></i>Tambah Kategori</button>
</div>
<table class="table table-striped data-table">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th>Name</th>
            <th width="100px">Action</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>

{{-- add new cat modal start --}}
<div class="modal fade" id="ajaxModelexa" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="catForm" name="catForm" class="form-horizontal">
                   <input type="hidden" name="cat_id" id="cat_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Nama</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Masukan Nama" value="" required>
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
{{-- add new cat modal end --}}
@endsection

@section('footer')
    <strong>Copyright &copy; 2024 <a href="https://github.com/Davaxtra">Mohammad Daffa Hafidzan</a>.</strong>
        All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
        <b>Version</b> 2.0
    </div>
@stop

@section('js')
    <script>
        
    $(function () {
        
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
      var table = $('.data-table').DataTable({
          processing: true,
          serverSide: true,
          autoWidth: false,
          responsive: true,
          ajax: "{{ route('category.index') }}",
          columns: [
              {data: 'DT_RowIndex'},
              {data: 'name', name: 'name'},
              {data: 'action', name: 'action', orderable: false, searchable: false},
          ]
      });
    

    $('#createNewCat').click(function () {
        $('#savedata').val("create-category");
        $('#cat_id').val('');
        $('#catForm').trigger("reset");
        $('#modelHeading').html("Tambah Kategori");
        $('#ajaxModelexa').modal('show');
    });

    $('body').on('click', '.editKategori', function () {
      var cat_id = $(this).data('id');
      $.get("category" +'/' + cat_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Category");
          $('#savedata').val("edit-category");
          $('#cat_id').val(data.id);
          $('#name').val(data.name);
          $('#ajaxModelexa').modal('show');
      })
   });

    $('#savedata').click(function (e) {
        e.preventDefault();
        $(this).html('Simpan');
    
        $.ajax({
          data: $('#catForm').serialize(),
          url: "{{ route('category.store') }}",
          type: "POST",
          dataType: 'json',
          success: function (data) {
            Swal.fire(
                'Cihuyy!',
                'Data sukses disimpan!',
                'success'
            )
              $('#catForm').trigger("reset");
              $('#ajaxModelexa').modal('hide');
              table.ajax.reload();
          },
          error: function (data) {
            toastr.options.progressBar = true;
            toastr.warning('Data tidak valid!');
          }
        });
    });

    $('body').on('click', '.deleteKategori', function () {
     
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
            if (result.isConfirmed){
                 $.ajax({
         type: "DELETE",
         url: "{{ route('category.store') }}"+'/'+id,
         success: function (data) {
            // Swal.fire(
            //       'Terhapus!',
            //       'Data berhasil dihapus!',
            //       'success'
            //     )
            toastr.options.progressBar = true;
            toastr.success('Data berhasil dihapus!');
             table.ajax.reload();
         },
         error: function (data) {
             console.log('Error:', data);
         }
     });
            }
        })
    
 });
});
  </script>
@stop