<?php

use App\Helpers\StellarHelper;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// Artisan::command('stellar', function () {
    // $stellar = new StellarHelper;
    // var_dump(StellarHelper::getOperations('desc', 2));
    // var_dump(StellarHelper::checkPayment("GAUEEYOUENSQLAFWSRPHABROV263WV5DKSH23ITC4BVY4GEWLBSRZUEG", 253.00, 800));
// });
