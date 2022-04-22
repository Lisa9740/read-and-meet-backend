<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::all();
        return $this->sendResponse(ProductResource::collection($products));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $Product =  Product::create([
            'title' => $request->get('ProductTitle'),
            'short_description' => $request->get('ProductDescription'),
            'image_thumbail_url' =>$request->get('image_thumbail_url'),
            'isbn_number' => $request->get('isbnNumber'),
            'author' => $request->get('ProductAuthor'),
        ]);
        return $Product;
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $Product = Product::find($id);
        if (is_null($Product)) {
            return $this->sendError('Product Post not found.');
        }
        return $this->sendResponse(new ProductResource($Product));
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

        $Product  = Product::find($id);
        $Product->title = $input['title'];
        $Product->short_description = $input['short_description'];
        $Product->isbn_number = $input['isbn_number'];
        $Product->author = $input['author'];
        $Product->save();

        return $this->sendResponse(new ProductResource($Product));
    }
}
