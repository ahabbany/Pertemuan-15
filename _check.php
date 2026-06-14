<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "now: " . now() . "\n";
echo "today: " . today() . "\n";
echo "tz: " . config('app.timezone') . "\n\n";

$transaksis = DB::table('transaksis')
    ->where('status', 'Dipinjam')
    ->get();

echo "Dipinjam count: " . $transaksis->count() . "\n\n";

foreach ($transaksis as $t) {
    $isLate = $t->tanggal_kembali < now() ? 'YA' : 'TIDAK';
    echo "$t->id | $t->kode_transaksi | tgk: $t->tanggal_kembali | late? $isLate\n";
}
