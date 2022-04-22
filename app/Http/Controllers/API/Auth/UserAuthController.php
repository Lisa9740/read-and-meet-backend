<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Resources\User\UserResource;
use App\Http\Resources\User\UserWithoutPostResource;
use App\Models\Profile;
use Error;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserAuthController extends Controller
{
    /**
     * Registration
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {

            $profile = Profile::create([
                'description' => null,
                'book_liked' => null,
                'is_visible' =>  false,
                'photo' => null]);


            $path =  $request->get("avatar");

            $user = User::create([
                'firstname'     => $request->firstname,
                'lastname'     => $request->lastname,
                'name'          => $request->name,
                'email'         => $request->email,
                'password'      => bcrypt($request->password),
                'profile_id'    => $profile->id,
                'user_picture'  => $path

            ]);

            $token = $user->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['token' => $token], 200);
    }

    public function login(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (auth()->attempt($data)) {
            $token = auth()->user()->createToken('LaravelAuthApp')->accessToken;
            return response()->json(['user'=> new UserResource(auth()->user()) , 'token' => $token,  'valid' => auth()->check()], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }


    /**
     * Handle disconnect request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): \Illuminate\Http\JsonResponse
    {

       Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json([
            'success' => true,
            "message" => "Vous êtes déconnecté !",
        ]);
    }


    /**
     * Check token validity
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyToken(): \Illuminate\Http\JsonResponse
    {
        $loggedUser   = Auth::user();
        if($loggedUser) {
            return response()->json("Authenticated", 401);
        }
        return response()->json("Unauthenticated", 401);
    }


}
