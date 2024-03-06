<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
        CREATE TRIGGER update_stok AFTER INSERT ON detail_transactions FOR EACH ROW
        BEGIN
            DECLARE produk_id INT;
            DECLARE qty INT;

            -- Ambil data id produk dan jumlah dari baris yang baru dimasukkan
            SELECT NEW.produk_id, NEW.qty INTO produk_id, qty;

            -- Kurangi stok di tabel produk
            UPDATE products 
            SET stock = stock - qty 
            WHERE id = produk_id;
        END;
    ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS update_stok');
    }
};
