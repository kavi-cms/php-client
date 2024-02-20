<?php

use Illuminate\Support\Facades\Route;
use Kavicms\KavicmsLaravel\Http\Controllers\KaviCmsController;

Route::get('/{slug}', [KaviCmsController::class, 'index'])->where('slug', '.*');
