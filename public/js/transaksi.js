$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function addToCart(data) {
    var data = JSON.parse(data);
    var cart = $('#cart');
    var existingCard = cart.find('.info-box[data-id="' + data.id + '"]');
    // var jumlah = $(`<input type="hidden" name="jumlah[]" class="form-control col-sm-8 col-form-label" value="1">`);
    var subtotal = $(`<input type="hidden" name="subtotal[]" class="form-control col-sm-8 col-form-label" value="${data['price']}" >`);
    var produk_id = $(`<input type="hidden" name="produk_id[]" class="form-control col-sm-8 col-form-label" value="${data['id']}" >`);

    if (existingCard.length > 0) {
        // Jika kartu dengan ID yang sama sudah ada di keranjang, tambahkan quantity
        var quantityElement = existingCard.find('form[name=jumlah[]]');
        var currentQuantity = parseInt(quantityElement.val());
        var subtotalElement = existingCard.find('form[name=subtotal[]]');
        if (currentQuantity < data.stock){
        quantityElement.val(currentQuantity + 1);
        console.log('Quantity untuk ' + data.name + ' diperbarui: ' + (currentQuantity + 1));
        
        }
    } else {
        // Jika kartu dengan ID yang sama belum ada di keranjang, tambahkan ke keranjang
        var cardWrap = $('<div class="info-box" style="display: inline-block; margin-bottom: 10px;" data-id="' + data.id + '"></div>'); // wadah kartu
        var cardTop = $('<div style="display: flex; align-items: center;"></div>'); //wadah buat gambar, nama, harga produk
        var cardImg = $('<img class="info-box-img" src="storage/product/' + data.image + '" alt="' + data.name + '" width="100px" height="100px">');
        var cardContent = $('<div class="info-box-content" style="margin-left: 10px;"></div>');
        var cardName = $('<span class="info-box-text">' + data.name + '</span>');
        var formattedPrice = parseFloat(data.price).toLocaleString('id-ID', { style: 'decimal' });
        var cardPrice = $('<span class="info-box-number">Rp. ' + formattedPrice + '</span>');
        
        // Tombol untuk menambah dan mengurangi quantity
        var qtyBox = $('<div class="row"></div>')
        var qty = $(`<input type="number" name="jumlah[]" id="jumlah" class="form-control info-box-quantity col-sm-8 col-form-label" value="1" >`); // Tambahkan elemen untuk quantity
        var decreaseButton = $('<button class="col btn btn-sm btn-danger btn-decrease">-</button>');
        var increaseButton = $('<button class="col btn btn-sm btn-success btn-increase">+</button>');
    
        // Event listener untuk tombol decrease
        decreaseButton.click(function() {
            var currentQuantity = parseInt(qty.text());
            if (currentQuantity > 1) {
                qty.text(currentQuantity - 1);
                updateTotalPrice(); // Memanggil kembali fungsi untuk memperbarui total harga
            } else {
                cardWrap.remove();
                console.log('Card ' + data.name + ' dihapus dari keranjang');
                if (cart.find('.info-box').length === 0) {
                    cart.append('<p id="empty-cart-msg">Keranjang belanja kosong.</p>');
                }
                updateTotalPrice(); // Memanggil kembali fungsi untuk memperbarui total harga setelah menghapus item
            }
        });
    
        // Event listener untuk tombol increase
        increaseButton.click(function() {
            var currentQuantity = parseInt(qty.text());
            if (currentQuantity < data.stock){
                qty.text(currentQuantity + 1);
                console.log('Quantity untuk ' + data.name + ' diperbarui: ' + (currentQuantity + 1));
                updateTotalPrice(); // Memanggil kembali fungsi untuk memperbarui total harga
            }
        });
    
        console.log('Card ' + data.name + ' ditambahkan ke keranjang');
    
        // Menggabungkan elemen-elemen ke dalam qtyBox
        qtyBox.append(decreaseButton);
        qtyBox.append(qty);
        qtyBox.append(increaseButton);
    
        // element card
        cardTop.append(cardImg);
        cardContent.prepend(cardName);
        cardContent.append(cardPrice);
        cardContent.append(qtyBox); // Menambahkan qtyBox ke cardContent
        cardTop.append(cardContent);
        cardWrap.append(subtotal); // Menambahkan input hidden subtotal ke dalam keranjang
        cardWrap.append(cardTop);
        cart.append(cardWrap);
        qty.on('input', function(){ // on change
            console.log('kanjut');
            var jumlahqty = $(this).val();
            var jumlahsubtotal = jumlahqty * data.price;
            subtotal.val(jumlahsubtotal);
            console.log(jumlahsubtotal);

        });
        
        // Menghitung subtotal dan menambahkannya ke input hidden subtotal[]
        var total = parseFloat(data.price) * parseInt(qty.text());
        subtotal.val(total);
    
        // // Mengisi input hidden jumlah[] dengan kuantitas produk
        // jumlah.val(qty.text());
    
        // Menambahkan subtotal ke dalam keranjang
        cart.append(subtotal);
        cart.append(produk_id);
    }
    

    // Menghapus pesan "Keranjang belanja kosong" jika ada kartu yang ditambahkan
    cart.find('#empty-cart-msg').remove();

    updateTotalPrice(data);
}

function updateTotalPrice() {
    var totalPrice = 0;
    // Memeriksa apakah ada barang di dalam $('.info-box')
    if ($('.info-box').length > 0) {
        $('.info-box').each(function () {
            var price = parseFloat($(this).find('.info-box-number').text().replace('Rp. ', '').replace('.', '').replace(',', '.'));
            var qty = parseInt($(this).find('.info-box-quantity').text());
            totalPrice += price * qty;
        });
    }
    // Mengatur teks total harga
    // $('#total-price').text('Total Harga: Rp. ' + totalPrice.toLocaleString('id-ID', { style: 'decimal' }));
    $('#total_price').val(totalPrice);
    hitungKembalian();
}



$('#pembayaran').on('input', function(){
    var uang = parseFloat($(this).val());
    var total = parseFloat($('#total_price').val());
    var kembalian = uang - total;

    $('#kembalian').val(kembalian);
    console.log('lalala');
});

function bayar(uang){
    // var uang = parseFloat($(this).val());
    var total = parseFloat($('#total_price').val());
    var kembalian = uang - total;

    $('#kembalian').val(kembalian);
    console.log('kembalian muncul');
}
function hitungKembalian() {
    var uang = parseFloat($('#pembayaran').val());
    var total = parseFloat($('#total-price').val());
    var kembalian = uang - total;

    $('#kembalian').val(kembalian);
    console.log('kembalian muncul');
}
