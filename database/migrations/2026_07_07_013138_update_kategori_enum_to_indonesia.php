<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("UPDATE buku SET kategori = 'Pemrograman' WHERE kategori = 'Programming'");
        DB::statement("UPDATE buku SET kategori = 'Basis Data' WHERE kategori = 'Database'");
        DB::statement("UPDATE buku SET kategori = 'Desain Web' WHERE kategori = 'Web Design'");
        DB::statement("UPDATE buku SET kategori = 'Jaringan' WHERE kategori = 'Networking'");
        DB::statement("UPDATE buku SET kategori = 'Ilmu Data' WHERE kategori = 'Data Science'");

        DB::statement("ALTER TABLE buku MODIFY COLUMN kategori ENUM('Pemrograman', 'Basis Data', 'Desain Web', 'Jaringan', 'Ilmu Data') NOT NULL");
    }

    public function down()
    {
        DB::statement("UPDATE buku SET kategori = 'Programming' WHERE kategori = 'Pemrograman'");
        DB::statement("UPDATE buku SET kategori = 'Database' WHERE kategori = 'Basis Data'");
        DB::statement("UPDATE buku SET kategori = 'Web Design' WHERE kategori = 'Desain Web'");
        DB::statement("UPDATE buku SET kategori = 'Networking' WHERE kategori = 'Jaringan'");
        DB::statement("UPDATE buku SET kategori = 'Data Science' WHERE kategori = 'Ilmu Data'");

        DB::statement("ALTER TABLE buku MODIFY COLUMN kategori ENUM('Programming', 'Database', 'Web Design', 'Networking', 'Data Science') NOT NULL");
    }
};
