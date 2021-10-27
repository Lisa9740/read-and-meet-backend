<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ContactRequestResource;
use App\Http\Resources\ContactResource;
use App\Http\Resources\UserResource;
use App\Models\Contact;
use App\Models\User;
use App\Models\UserHasContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): \Illuminate\Http\JsonResponse
    {
        $usrContacts = DB::table('user_has_contact')->where('user_id', 'LIKE', Auth::id())->get();
        $users = [];

        foreach ($usrContacts as $contact){
            $users[] = $this->getUserContactById($contact->contact_id);
        }

        return $this->sendResponse($users);
    }

    function getUserContactById($id): ?array
    {
        $result = null;
        $contacts = DB::table('user_has_contact')->where('contact_id', 'LIKE', $id)->get();
        $user = null;

        foreach ($contacts as $contact){
            if ($contact->user_id !== Auth::id()){
                $user = User::find($contact->user_id);
            }
        }

        if ($user){
            $result = ["id" => $user->id,  "name" => $user->name, "avatar" => $user->user_picture  ];
        }
        return $result;
    }
}
