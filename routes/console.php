<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Artisan commands. Of course,
| each controller close to your application's console commands is located in
| the Console directory. You just need to tell Artisan the URIs
| to it.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose(Inspiring::quote()); 