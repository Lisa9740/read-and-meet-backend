<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\BookPost;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\BookPost as BookPostResource;

class BookPostController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = BookPost::all();
        return $this->sendResponse(BookPostResource::collection($posts));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'bookTitle' => 'required',
            'bookDescription' => 'required',
            'isbnNumber' => 'required',
            'bookAuthor' => 'required',
            'is_visible' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $book = $this->createBook($request);
        $post = BookPost::create([
            'title'          => $request->get('title'),
            'description'    => $request->get('description'),
            'is_visible'     => $request->get('is_visible'),
            'user_id'        => Auth::id(),
            'book_id'        => $book->id
        ]);

        return $this->sendResponse(new BookPostResource($post));
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $post = BookPost::find($id);
        if (is_null($post)) {
            return $this->sendError('Book Post not found.');
        }
        return $this->sendResponse(new BookPostResource($post));
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
            'title' => 'required',
            'description' => 'required',
            'is_visible'     => $request->get('is_visible'),
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post  = BookPost::find($id);
        $post->title = $input['title'];
        $post->description = $input['description'];
        $post->is_visible = $input['is_visible'];
        $post->user_id = Auth::id();
        $post->save();

        return $this->sendResponse(new BookPostResource($post));
    }

    /**
     * Remove the specified resource from storage.
    * @param int $id
    * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $post = BookPost::findOrFail($id);
        $post->delete();

        return $this->sendResponse(null);
    }

    public function createBook(Request  $request)
    {
        return Book::create([
            'title' => $request->get('bookTitle'),
            'short_description' => $request->get('bookDescription'),
            'isbn_number' => $request->get('isbnNumber'),
            'author' => $request->get('bookAuthor'),
        ]);
    }

}
