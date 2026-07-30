<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$p = \App\Models\Permission::where('user_id', 3)->where('date', '2026-07-30')->first();
$a = \App\Models\Attendance::where('user_id', 3)->where('date', '2026-07-30')->first();
if ($a && $p) {
    $a->time_in = $p->created_at->timezone('Asia/Makassar')->toTimeString();
    $a->save();
    echo "Updated time_in to WITA: " . $a->time_in;
} else {
    echo "No update needed.";
}
