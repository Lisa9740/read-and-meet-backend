<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfileController extends BaseController
{
    /**
     * Display a listing of user profile where is_visible.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $profiles = DB::table('profiles')->where('is_visible', 'LIKE', 1)->get();
        return $this->sendResponse(ProfileResource::collection($profiles));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $profile = Profile::find($id);
        if (is_null($profile)) {
            return $this->sendError('Profil not found.');
        }
        return $this->sendResponse(new ProfileResource($profile), 'Profile retrieved successfully.');
    }


    /**
     * Changed the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit($id, Request $request)
    {
        $input = $request->all();


      $validator = Validator::make($input, [
          'description'   => 'required',
          'book_liked'    => 'required'
      ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $profile = Profile::find($id);
        $profile->description = $input['description'];
        $profile->book_liked = $input['book_liked'];
        $profile->photo = $input['photo'];
        $profile->save();

        return $this->sendResponse(new ProfileResource($profile), 'Profile retrieved successfully.');
    }

    /**
     * Changed the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeVisibility($id, Request $request)
    {
        $input = $request->all();


        $validator = Validator::make($input, [
            'is_visible'   => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $profile = Profile::find($id);
        $profile->is_visible = $input['is_visible'];
        $profile->save();

        return $this->sendResponse(new ProfileResource($profile), 'Profile retrieved successfully.');
    }


    /**
     * Changed the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function changePhoto($id, Request $request)
    {
        $input = $request->all();


        $validator = Validator::make($input, [
            'photo'   => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $profile = Profile::find($id);
        $profile->photo = $input['photo'];
        $profile->save();

        return $this->sendResponse(new ProfileResource($profile), 'Profile retrieved successfully.');
    }


}
