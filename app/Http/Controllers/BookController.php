<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Http\Resources\BooksResource;
use Faker\Factory;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::all();
        return new BooksResource($books);
    }

    public function GetAllBooksWithAuthors()
    {
        $books = Book::all()->load('Authors');
        return new BooksResource($books);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request, Book $book)
    {
        $book = Book::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return new BooksResource($book);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        // book with author
        $bookWithAuthor = Book::with('Authors')->find($book->id);
        return new BooksResource($bookWithAuthor);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book = Book::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);
        return new BooksResource($book);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return response('Deleted successfully !', 204);
    }
}
