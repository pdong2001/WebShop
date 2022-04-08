<?php

use App\Http\Controllers\AuthenticationApiController;
use App\Http\Controllers\CartApiController;
use App\Http\Controllers\CategoryApiController;
use App\Http\Controllers\FileApiController;
use App\Http\Controllers\ImageAssignApiController;
use App\Http\Controllers\InvoiceApiController;
use App\Http\Controllers\InvoiceDetailApiController;
use App\Http\Controllers\ProductApiController;
use App\Http\Controllers\ProductDetailApiController;
use App\Http\Controllers\RegisterApiController;
use App\Http\Middleware\CORS;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/user', function (Request $request) {
        return response()->json([
            'code' => Response::HTTP_OK,
            'status' => true,
            'data' => new UserResource(Auth::user()),
            'meta' => []
            
        ]);
    });
    Route::resource('products', ProductApiController::class)->except(['edit', 'create']);
    Route::resource('product-details', ProductDetailApiController::class)->except(['edit', 'create']);
    Route::resource('invoice', InvoiceApiController::class)->except(['edit', 'create']);
    Route::resource('invoice-details', InvoiceDetailApiController::class)->except(['edit', 'create']);
    Route::resource('carts', CartApiController::class)->except(['edit', 'create']);
    Route::post('carts/{id}', [CartApiController::class, 'checkOut'])->name('carts.checkout');
    Route::resource('categories', CategoryApiController::class)->except(['edit', 'create']);
    Route::post('logout', [AuthenticationApiController::class, 'logout'])->name('auth.logout');
    Route::post('image_assigns', [ImageAssignApiController::class, 'store'])->name('image_assign.store');
    Route::delete('image_assigns/{id}', [ImageAssignApiController::class, 'destroy'])->name('image_assign.delete');
    Route::get('blobs', [FileApiController::class, 'getListBlob'])->name('file.index');
    Route::post('upload', [FileApiController::class, 'uploadRange'])->name('file.uploadRange')->middleware(['auth:api']);
});
Route::put('blobs/{id}', [FileApiController::class, 'updateBlob'])->name('blob.update')->middleware(['auth:api']);
Route::post('upload', [FileApiController::class, 'upload'])->name('file.upload')->middleware(['auth:api']);
Route::delete('blobs/{id}', [FileApiController::class, 'delete'])->name('blob.delete')->middleware(['auth:api']);
Route::get('files/{name}', [FileApiController::class, 'get'])->name('file.get');
Route::get('blobs/{id}', [FileApiController::class, 'getByBlob'])->name('file.blob');
Route::get('blobs/download/{id}', [FileApiController::class, 'downloadById'])->name('file.blob');
Route::get('file/download/{name}', [FileApiController::class, 'download'])->name('file.download');

Route::prefix('admin')->group(function() {
    // Route::post('register', [RegisterApiController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthenticationApiController::class, 'login'])->name('auth.login');
});
    
// Route::get('/products', [ProductApiController::class, 'Index']);
    