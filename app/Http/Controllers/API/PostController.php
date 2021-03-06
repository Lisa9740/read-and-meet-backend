<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\Collection\Post\PostWithoutUserCollection;
use App\Http\Resources\PostWithoutUserResource;
//use App\Http\Resources\UserPostResource;
use App\Models\Book;
use App\Models\File;
use App\Models\Localisation;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Post\PostResource as PostResource;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $posts = Post::where('is_visible',  '=', 1)->get();

        //$posts = DB::table("posts")->where('is_visible',  '=', 1)->get();

        Log::info($posts);

        return $this->sendResponse(PostResource::collection($posts));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $localisation = $this->createPostLocalisation($request);
        $localisation->save();

        $post = new Post();
        $post->title = $request->get('title');
        $post->description = $request->get('description');
        $post->user_id = Auth::id();
        $post->localisation_id = $localisation->id;
        $post->is_visible = $request->get('is_visible');

        $post->save();
        $this->createBooks($request, $post->id);

        return $this->sendResponse(new PostResource($post));
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
     * Display the specified resource.
     *  @return JsonResponse
     */
    public function showPosts(): JsonResponse
    {
        $post = Post::where('user_id', '=', Auth::id())->get();
        if (is_null($post)) {
            return $this->sendError('Post not found.');
        }
        return $this->sendResponse(new PostWithoutUserCollection($post));
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
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post  = Post::find($id);
        $post->title = $input['title'];
        $post->description = $input['description'];
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


    public function createBooks(Request $request, $id)
    {
        $books = json_decode($request->get("books"));
        Log::info($books);

        foreach ($books as $book){
            $newBook = new Book();
            $newBook->title = $book->title;
            $newBook->short_description = $book->description;
            $newBook->isbn_number = "ffff";
            $newBook->author = $book->author;
            $newBook->image_thumbail_url = $book->image;
            $newBook->price = $book->price;
            $newBook->post_id = $id;
            $newBook->save();
        }


        //return $books;

    }

    public function createPostLocalisation(Request $request){
        return Localisation::create([
            'lat' => $request->get('lat'),
            'lng' => $request->get('lng'),
            'city' => $request->get('city')
        ]);
    }
}
