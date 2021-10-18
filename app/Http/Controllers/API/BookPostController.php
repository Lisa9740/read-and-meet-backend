<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\BookPost;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Http\Resources\BookPost as BookPostResource;

class BookPostController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = BookPost::all();
        return $this->sendResponse(BookPostResource::collection($posts), 'Book Posts retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'bookTitle' => 'required',
            'bookDescription' => 'required',
            'isbnNumber' => 'required',
            'bookAuthor' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $book = Book::create([
            'title' => $request->get('bookTitle'),
            'short_description' => $request->get('bookDescription'),
            'isbn_number' => $request->get('isbnNumber'),
            'author' => $request->get('bookAuthor'),
        ]);

        $post = BookPost::create([
            'title'          => $request->get('title'),
            'description'    => $request->get('description'),
            'user_id'        => Auth::id(),
            'book_id'       => $book->id
        ]);

        return $this->sendResponse(new BookPostResource($post), 'Book Post created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = BookPost::find($id);

        if (is_null($post)) {
            return $this->sendError('Book Post not found.');
        }

        return $this->sendResponse(new BookPostResource($post), 'Book Post retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post  = BookPost::find($id);
        $post->title = $input['title'];
        $post->description = $input['description'];
        $post->user_id = Auth::id();
        $post->save();

        return $this->sendResponse(new BookPostResource($post), 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        $post = BookPost::findOrFail($id);
        $post->delete();

        return $this->sendResponse(null, 'Post deleted successfully.');
    }

}
