<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE buku MODIFY COLUMN kategori ENUM('Pemrograman', 'Basis Data', 'Desain Web', 'Jaringan', 'Ilmu Data') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE buku MODIFY COLUMN kategori ENUM('Programming', 'Database', 'Web Design', 'Networking', 'Data Science') NOT NULL");
    }
};
