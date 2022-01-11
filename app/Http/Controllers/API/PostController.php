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
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::all();
        return $this->sendResponse(PostResource::collection($posts));
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
        $localisation = $this->createPostLocalisation($request);
        $post = Post::create([
            'title'          => $request->get('title'),
            'description'    => $request->get('description'),
            'is_visible'     => $request->get('is_visible'),
            'user_id'        => Auth::id(),
            'book_id'        => $book->id,
            'localisation_id'=> $localisation->id
        ]);

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
        $post->is_visible = $input['is_visible'];
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

    public function createBook(Request  $request)
    {
        return Book::create([
            'title' => $request->get('bookTitle'),
            'short_description' => $request->get('bookDescription'),
            'isbn_number' => $request->get('isbnNumber'),
            'author' => $request->get('bookAuthor'),
            'image_thumbail_url' => $request->get('image')
        ]);
    }

    public function createPostLocalisation(Request $request){
        return Localisation::create([
           'lat' => $request->get('lat'),
           'lng' => $request->get('lng'),
            'address' => $request->get('address'),
            'city' => $request->get('city')
        ]);
    }
}
