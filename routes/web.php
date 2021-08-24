<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'is_admin']], function(){
    Route::get('/',                         [App\Http\Controllers\AdminController::class, 'index'])->name('admin');
    Route::get('/search/{table}',           [App\Http\Controllers\AdminController::class, 'search'])->name("admin-search");
    Route::get('/browse/{table}',           [App\Http\Controllers\AdminController::class, 'browse'])->name("admin-browse");
    Route::get('/create/{table}',           [App\Http\Controllers\AdminController::class, 'create'])->name("admin-create");
    Route::get('/edit/{table}/{id}',        [App\Http\Controllers\AdminController::class, 'edit'])->name("admin-edit");
    Route::get('/manage-account',           [App\Http\Controllers\AdminController::class, 'manageAccount'])->name("admin-manage-account");
    Route::get('/documentation/{subject?}', [App\Http\Controllers\AdminController::class, 'documentation'])->name("admin-documentation");
    Route::get('/commands',                 [App\Http\Controllers\AdminController::class, 'commands'])->name("admin-commands");
    
    Route::post('/create/{table}/action',   [App\Http\Controllers\AdminController::class, 'createAction'])->name("admin-create-action");
    Route::post('/edit/{table}/{id}/action',[App\Http\Controllers\AdminController::class, 'editAction'])->name("admin-edit-action");
    Route::post('/delete',                  [App\Http\Controllers\AdminController::class, 'deleteAction'])->name("admin-delete-action");
    Route::post('/wysiwyg-upload',          [App\Http\Controllers\AdminController::class, 'wysiwygUpload'])->name("wysiwyg-upload");
    Route::post('/manage-account-action',   [App\Http\Controllers\AdminController::class, 'manageAccountAction'])->name("admin-manage-account-action");
    Route::post('/command-run',             [App\Http\Controllers\AdminController::class, 'commandRun'])->name("admin-command-run");
});

require __DIR__.'/auth.php';
