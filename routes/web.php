<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';


Route::get('my-contents', [\App\Http\Controllers\MyContent::class, 'index'])
    ->middleware(['auth', 'check.user.subscribed'])
    ->name('my-content');

Route::get('watch/{content:slug}', [\App\Http\Controllers\MyContent::class, 'single'])
    ->name('watch.video')->middleware(['auth']);

Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'checkout'])
    ->middleware('auth')
    ->name('checkout');
Route::post('/checkout/charge', [\App\Http\Controllers\CheckoutController::class, 'charge'])
    ->middleware('auth')
    ->name('checkout.charge');

Route::get('resource/{code}/{video}', function ($code, $video) {
    $video = $code . '/' . $video;

    return \Illuminate\Support\Facades\Storage::disk('videos_processed')
        ->response(
            $video,
            null,
            [
                'Content-Type' => 'application/x-mpegURL',
                'isHls' => true
            ]
        );
})->name('stream.player')->middleware(['auth']);

Route::prefix('media')->name('media.')->group(function () {

    Route::resource(
        'contents',
        \App\Http\Controllers\Media\ContentController::class
    )
        ->middleware('auth');

    Route::get(
        'contents/{content}/videos',
        [\App\Http\Controllers\Media\VideoController::class, 'index']
    )->name('content.videos.upload');

    Route::post(
        'contents/{content}/videos/store',
        [\App\Http\Controllers\Media\VideoController::class, 'store']
    )->name('contents.videos.store');


    Route::post(
        'contents/{content}/videos/{video}/process/chunck',
        [\App\Http\Controllers\Media\VideoController::class, 'processChunk']
    )->name('contents.videos.upload.chuncks');

    Route::match(
        ['PUT', 'PATCH'],
        'contents/{content}/videos/{video}/update',
        [\App\Http\Controllers\Media\VideoController::class, 'update']
    )->name('contents.videos.update');

    Route::delete(
        'contents/{content}/videos/{video}/destroy',
        [\App\Http\Controllers\Media\VideoController::class, 'destroy']
    )->name('contents.videos.destroy');
});
