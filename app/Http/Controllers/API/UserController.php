<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserResource;
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
     * @return Response
     */
    public function index()
    {
        $users = User::all();
        return $this->sendResponse(UserResource::collection($users), "Users retrieve successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $user = User::find($id);

        if (is_null($user)) {
            return $this->sendError('User not found.');
        }

        return $this->sendResponse(new UserResource($user), 'User retrieved successfully.');
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
                'confirmed' => 'le :attriute doit être confirmer'
            ]
        )->validate();

        $user = auth()->user();
        $user->password = Hash::make($validator['password']);
        $user->save();

        return response('Mot de passe changer avec succès', 200);;
    }

    /**
     * @throws \Illuminate\Validation\ValidationException
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

        $user = User::where('id', Auth::id())->first();
        if ($request->hasFile('image')) {
            if ($request->hasFile('image')) {
                $oldImage = $user->avatar;

                if ($oldImage != null && $oldImage != "avatars/default.png") {
                    $oldFilePath = public_path('images') . '/' . $oldImage;
                    unlink($oldFilePath);
                    $imageUploaded  = $validator['image'];
                    $extension      = $imageUploaded->getClientOriginalExtension();
                    $image          = time() . rand() . '.' . $extension;
                    $imageUploaded->move(public_path('images/avatars'), $image);
                    $user->avatar = $image;
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
        return response('Avatar changer avec succès', 200);;
    }
}

