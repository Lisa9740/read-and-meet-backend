<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserWithoutPostResource;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = User::all();
        return $this->sendResponse(UserWithoutPostResource::collection($users));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = User::find($id);

        return $this->sendResponse(new UserResource($user));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return UserResource
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateUser(Request $request): UserResource
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => '',
            ],
        )->validate();


        $user =  auth()->user();

        $validator['name'] != null ? $user->name = $validator['name'] : null;
        $validator['user_picture'] != null ? $user->name = $validator['user_picture'] : null;
        $user->save();
        return new UserResource($user);
    }

    /**
     * Fonction post qui permet de modifier le mot de passe de l'utilisateur
     * @return success
     */

    public function updatePassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|min:6|confirmed',
                'password_confirmation' => 'required|min:6'
            ],
            [
                'required' => 'Le champs :attribute est requis', // :attribute renvoie le champs / l'id de l'element en erreure
                'confirmed' => 'le :attriute doit ??tre confirmer'
            ]
        )->validate();

        $user = auth()->user();
        $user->password = Hash::make($validator['password']);
        $user->save();

        return response('Mot de passe changer avec succ??s', 200);;
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
     *
     */
    public function updateAvatar(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'image' => 'required|image',
            ],
            [
                'required' => 'Le champs :attribute est requis', // :attribute renvoie le champs / l'id de l'element en erreure
            ]
        )->validate();


        $requestedBaseUrl = $request['url'];

        $user = User::where('id', Auth::id())->first();
        if ($request->hasFile('image')) {
            if ($request->hasFile('image')) {
                $oldImage = $user->user_picture;

                if ($oldImage != null && $oldImage != "avatars/default.png") {
                   // $oldFilePath = public_path('images') . '/' . $oldImage;
                   // unlink($oldFilePath);

                    $imageUploaded  = $validator['image'];

                    $path = $imageUploaded->store('public/avatars');
                    $file = File::create([
                        'path' => $path,
                        'filename' => strval($request['image']),
                        'authorId' => Auth::id()
                    ]);

                    $file->save();

                    $extension      = $imageUploaded->getClientOriginalExtension();
                    $image          = time() . rand() . '.' . $extension;
                    $imageUploaded->move(public_path('images/avatars'), $image);
                    $storageImageFilename = explode("/", $path);

                    $user->user_picture = $requestedBaseUrl . '/storage/avatars/' . end($storageImageFilename);

                } else {
                    $imageUploaded  = $validator['image'];
                    $extension      = $imageUploaded->getClientOriginalExtension();
                    $image          = time() . rand() . '.' . $extension;
                    $imageUploaded->move(public_path('images/avatars'), $image);
                    $user->avatar = $image;
                }
            }
        }

        $user->save();
        return response($user, 200);;
    }
}

