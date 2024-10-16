<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorsResource;
use Faker\Factory;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::all();
        return new AuthorsResource($authors);
    }

    public function GetAllAuthorsWithBooks()
    {
        $authorsWithBooks = Author::all()->load('Books');
        return new AuthorsResource($authorsWithBooks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request, Author $author)
    {
        $author = Author::create([
            'name' => $request->name,
        ]);
        return new AuthorsResource($author);
    }

    /**
     * Display the specified resource.
     */
    public function show(Author $author)
    {
        $authorWithBooks = Author::with('books')->find($author->id);
        return new AuthorsResource($authorWithBooks);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAuthorRequest $request, Author $author)
    {
        $author->update([
            'name' => $request->name,
        ]);
        return new AuthorsResource($author);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Author $author)
    {
        $author->delete();
        return response('', 204);
    }
}
