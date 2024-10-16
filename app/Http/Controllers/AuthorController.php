<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Http\Resources\AuthorsResource;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all authors
        $authors = Author::all();

        // Return the authors as a JSON response
        return new AuthorsResource($authors);
    }

    /**
     * Get all authors with books
     *
     * @return \Illuminate\Http\Response
     */
    public function GetAllAuthorsWithBooks()
    {
        // Get all authors with their books
        $authorsWithBooks = Author::all()->load('Books');

        // Return the authors with books as a JSON response
        return new AuthorsResource($authorsWithBooks);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreAuthorRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAuthorRequest $request)
    {
        // Create a new author
        $author = Author::create([
            'name' => $request->name,
        ]);

        // Return the created author as a JSON response
        return new AuthorsResource($author);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Author $author
     * @return \Illuminate\Http\Response
     */
    public function show(Author $author)
    {
        // Get the author with their books
        $authorWithBooks = Author::with('books')->find($author->id);

        // Return the author as a JSON response
        return new AuthorsResource($authorWithBooks);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateAuthorRequest $request
     * @param \App\Models\Author $author
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAuthorRequest $request, Author $author)
    {
        // Update the author
        $author->update([
            'name' => $request->name,
        ]);

        // Return the updated author as a JSON response
        return new AuthorsResource($author);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Author $author
     * @return \Illuminate\Http\Response
     */
    public function destroy(Author $author)
    {
        // Delete the author
        $author->delete();

        // Return a JSON response with a success message
        return response('', 204);
    }
}

