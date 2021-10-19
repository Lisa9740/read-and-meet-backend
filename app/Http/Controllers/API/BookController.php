<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $books = Book::all();
        return $this->sendResponse(BookResource::collection($books));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $book =  Book::create([
            'title' => $request->get('bookTitle'),
            'short_description' => $request->get('bookDescription'),
            'isbn_number' => $request->get('isbnNumber'),
            'author' => $request->get('bookAuthor'),
        ]);
        return $book;
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book Post not found.');
        }
        return $this->sendResponse(new BookResource($book));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title'             => 'required',
            'short_description' => 'required',
            'isbn_number'       => 'required',
            'author'            => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $book  = Book::find($id);
        $book->title = $input['title'];
        $book->short_description = $input['short_description'];
        $book->isbn_number = $input['isbn_number'];
        $book->author = $input['author'];
        $book->save();

        return $this->sendResponse(new BookResource($book));
    }
}
