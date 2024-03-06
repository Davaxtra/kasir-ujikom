function pilihProduk(data)
{
    var keranjang = $('#keranjang');
    var existingRow = keranjang.find(`tr[data-id="${data['id']}"]`);
    var jumlah = $(`<input type="text"  name="jumlah[]" class="form-control" value="1">`);
    var subtotal = $(`<input type="hidden"  name="sub_total[]" value="${data['hj']}">`);
    var idProduk = $(`<input type="hidden"  name="id_produk[]" value="${data['id']}">`);

    if (existingRow.length > 0) {
        var existingQty = parseInt(existingRow.find('input[name="jumlah[]"]').val());
        var existingSubTotal = parseInt(existingRow.find('input[name="sub_total[]"]').val());
        if(data['stok'] > existingQty )
        {
            var newQty = existingQty + 1;
            existingRow.find('input[name="jumlah[]"]').val(newQty);
            existingRow.find('input[name="sub_total[]"]').val(data['hj'] * newQty);
            // existingSubTotal.val(data['hj'] * newQty)
            totalHarga();

        }

    } else {
        var produk = `
        <tr data-id="${data['id']}">
            <td>${data['nama']}</td>
            <td>${data['hj']}</td>
            <td>${data['stok']}</td>
            <td></td> 
        </tr>
        `;

        keranjang.append(produk);
        keranjang.find('tr:last-child td:last-child').append(jumlah); // Menambahkan elemen jumlah ke kolom terakhir pada baris terakhir
        keranjang.find('tr:last-child td:last-child').append(idProduk); // Menambahkan elemen jumlah ke kolom terakhir pada baris terakhir
        keranjang.find('tr:last-child td:last-child').append(subtotal); // Menambahkan elemen jumlah ke kolom terakhir pada baris terakhir
        // keranjang.append(subtotal);
        subtotal.val(data['hj']);
        totalHarga();

        jumlah.on('input', function() {
            if(data['stok'] > existingQty )
            {
                var hargaPerItem = parseInt($(this).closest('tr').find('td:eq(1)').text()); // Ambil harga dari kolom kedua (indeks 1) dalam baris yang sama
                var qty = parseInt($(this).val());
                subtotal.val(hargaPerItem * qty);
                totalHarga();
            }else{
                jumlah.val(data['stok']);
            }
        });

    }

    console.log('susu');

    // cetakProduk(data);
}

function totalHarga()
{
    var total = 0;
    $('input[name="sub_total[]"]').each(function() {
        total += parseInt($(this).val());
    });
    $('input[name="total"]').val(total);
    console.log('Total harga: ' + total);
    // Lakukan sesuatu dengan total harga, misalnya menampilkannya di layar
}



function pembayaran()
{
    var total = $('input[name="total"]').val();
    var bayar = $('input[name="bayar"]').val();
    var hasil = bayar - total
    
    // kembalian.val(hasil);
    $('input[name="kembalian"]').val(hasil);

}