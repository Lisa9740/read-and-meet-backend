<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use App\Models\Localisation;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostResource as PostResource;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $posts = Post::all();
        return PostResource::collection($posts);
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

       /* if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }*/


        $book = $this->createBook($request);
        $book->save();

        $localisation = $this->createPostLocalisation($request);
        $localisation->save();

        $post = new Post();
        $post->title = $request->get('title');
        $post->description = "";
        $post->user_id = Auth::id();
        $post->book_id = $book->id;
        $post->localisation_id = $localisation->id;
        $post->is_visible = 1;

        $post->save();
        return $this->sendResponse($post);
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $post = Post::find($id);
        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }
        return $this->sendResponse(new PostResource($post));
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

        $post  = Post::find($id);
        $post->title = $input['title'];
        $post->description = $input['description'];
        $post->is_visible = 1;
        $post->user_id = Auth::id();
        $post->save();

        return $this->sendResponse(new PostResource($post));
    }

    /**
     * Remove the specified resource from storage.
    * @param int $id
    * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return $this->sendResponse(null);
    }

    public function createBook(Request $request)
    {
        $book = new Book();

        $book->title =  $request->get('bookTitle');
        $book->short_description =  $request->get('bookDescription');
        $book->isbn_number = $request->get('isbnNumber');
        $book->author = $request->get('bookAuthor');
        $book->image_thumbail_url = $request->get('image');

        return $book;
    }

    public function createPostLocalisation(Request $request){
        return Localisation::create([
           'lat' => $request->get('lat'),
           'lng' => $request->get('lng'),
            'city' => $request->get('city')
        ]);
    }
}
