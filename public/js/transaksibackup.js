$.ajaxSetup({
    headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    },
});

function addToCart(data) {
    var data = JSON.parse(data);
    var cart = $("#cart");
    var existingCard = cart.find('.info-box[data-id="' + data.id + '"]');
    var produk_id = $(
        '<input type="hidden" name="produk_id[]" value="' + data.id + '">'
    );
    var jumlah = $('<input type="hidden" name="jumlah[]" value="1">');
    var subtotal = $(
        '<input type="hidden" name="subtotal[]" value="' + data.price + '">'
    );
    if (existingCard.length > 0) {
        // Jika kartu dengan ID yang sama sudah ada di keranjang, tambahkan quantity
        var quantityElement = existingCard.find(".info-box-quantity");
        var currentQuantity = parseInt(quantityElement.text());
        if (currentQuantity < data.stock) {
            quantityElement.text(currentQuantity + 1);
            console.log(
                "Quantity untuk " +
                    data.name +
                    " diperbarui: " +
                    (currentQuantity + 1)
            );
            updateSubtotal();
        }
    } else {
        // Jika kartu dengan ID yang sama belum ada di keranjang, tambahkan ke keranjang
        var cardWrap = $(
            '<div class="info-box" style="display: inline-block; margin-bottom: 10px;" data-id="' +
                data.id +
                '"></div>'
        ); // wadah kartu
        var cardTop = $(
            '<div style="display: flex; align-items: center;"></div>'
        ); //wadah buat gambar, nama, harga produk
        var cardImg = $(
            '<img class="info-box-img" src="storage/product/' +
                data.image +
                '" alt="' +
                data.name +
                '" width="100px" height="100px">'
        );
        var cardContent = $(
            '<div class="info-box-content" style="margin-left: 10px;"></div>'
        );
        var cardName = $(
            '<span class="info-box-text">' + data.name + "</span>"
        );
        var formattedPrice = parseFloat(data.price).toLocaleString("id-ID", {
            style: "decimal",
        });
        var cardPrice = $(
            '<span class="info-box-number">Rp. ' + formattedPrice + "</span>"
        );

        // Tombol untuk menambah dan mengurangi quantity
        var qtyBox = $('<div class="row"></div>');
        var qty = $('<span class="col info-box-quantity text-center">1</span>'); // Tambahkan elemen untuk quantity
        var decreaseButton = $(
            '<button class="col btn btn-sm btn-danger btn-decrease">-</button>'
        );
        var increaseButton = $(
            '<button class="col btn btn-sm btn-success btn-increase">+</button>'
        );

        // Event listener untuk tombol decrease
        decreaseButton.click(function () {
            var currentQuantity = parseInt(qty.text());
            if (currentQuantity > 1) {
                qty.text(currentQuantity - 1);
                updateSubtotal(); // Memanggil kembali fungsi untuk memperbarui subtotal
            } else {
                cardWrap.remove();
                console.log("Card " + data.name + " dihapus dari keranjang");
                if (cart.find(".info-box").length === 0) {
                    cart.append(
                        '<p id="empty-cart-msg">Keranjang belanja kosong.</p>'
                    );
                }
                updateTotalPrice(); // Memanggil kembali fungsi untuk memperbarui total harga setelah menghapus item
                updateSubtotal();
            }
        });

        // Event listener untuk tombol increase
        increaseButton.click(function () {
            var currentQuantity = parseInt(qty.text());
            if (currentQuantity < data.stock) {
                qty.text(currentQuantity + 1);
                console.log(
                    "Quantity untuk " +
                        data.name +
                        " diperbarui: " +
                        (currentQuantity + 1)
                );
                updateSubtotal(); // Memanggil kembali fungsi untuk memperbarui subtotal
                updateTotalPrice();
            }
        });

        console.log("Card " + data.name + " ditambahkan ke keranjang");

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
        cardWrap.append(cardTop);
        cardWrap.append(jumlah);
        cardWrap.append(subtotal);
        cardWrap.append(produk_id);
        cart.append(cardWrap);
        $("#formPembayaran").append(jumlah);
        $("#formPembayaran").append(produk_id);
        $("#formPembayaran").append(subtotal);

        // update subtotal
        function updateSubtotal() {
            var qtyValue = parseInt(qty.text()); // mendapatkan nilai qty
            var price = parseFloat(data["price"]); // mendapatkan nilai harga dari data

            var newSubtotalValue = qtyValue * price; // subtotal diubah sesuai perhitungan qty * harga

            // Update nilai pada elemen subtotal
            jumlah.val(qtyValue);
            subtotal.val(newSubtotalValue);
        }
    }

    // Menghapus pesan "Keranjang belanja kosong" jika ada kartu yang ditambahkan
    cart.find("#empty-cart-msg").remove();

    updateTotalPrice(data);
}

function updateTotalPrice() {
    var totalPrice = 0;
    // Memeriksa apakah ada barang di dalam $('.info-box')
    if ($(".info-box").length > 0) {
        $(".info-box").each(function () {
            var price = parseFloat(
                $(this)
                    .find(".info-box-number")
                    .text()
                    .replace("Rp. ", "")
                    .replace(".", "")
                    .replace(",", ".")
            );
            var qty = parseInt($(this).find(".info-box-quantity").text());
            totalPrice += price * qty;
        });
    }
    // Mengatur nilai total harga di dalam input #total_price
    $("#total_price").val(totalPrice);
    hitungKembalian();
}

$("#bayar").on("input", function () {
    var uang = parseFloat($(this).val());
    var total = parseFloat($("#total_price").val());
    var kembalian = uang - total;

    $("#kembalian").val(kembalian);
    console.log("kanjut");
});

function bayar(uang) {
    // var uang = parseFloat($(this).val());
    var total = parseFloat($("#total_price").val());
    var kembalian = uang - total;

    $("#kembalian").val(kembalian);
    console.log("kembalian muncul");
}
function hitungKembalian() {
    var uang = parseFloat($("#bayar").val());
    var total = parseFloat($("#total_price").val());
    var kembalian = uang - total;

    $("#kembalian").val(kembalian);
    console.log("kanjut");
}
