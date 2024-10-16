<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->prefix('v1')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('authors', AuthorController::class);

    Route::apiResource('books', BookController::class);

    // get books with authors
    Route::get('/books-with-authors', [BookController::class, 'GetAllBooksWithAuthors']);

    // get authors with books
    Route::get('/authors-with-books', [AuthorController::class, 'GetAllAuthorsWithBooks']);

    Route::apiResource('roles', RoleController::class);

    Route::get('/roles-with-permissions', [RoleController::class, 'getRolesWithPermissions']);

    Route::get('/test', function (Request $request) {
        return 'Authenticated';
    });
});
