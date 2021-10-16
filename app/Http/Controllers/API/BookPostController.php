<?php

namespace App\Http\Controllers\API;

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
        $posts = auth()->user()->bookPosts;

        return $this->sendResponse(BookPostResource::collection($posts), 'Book Post retrieved successfully.');
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
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post = BookPost::create(['title' => $request->get('title'),'description' => $request->get('description'), "user_id" => Auth::id() ]);

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
    public function update(Request $request, BookPost $post)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post->title = $input['title'];
        $post->description = $input['descrption'];
        $post->save();

        return $this->sendResponse(new BookPostResource($post), 'Post updated successfully.');
    }
}
