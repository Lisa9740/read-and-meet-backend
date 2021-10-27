<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ContactRequestResource;
use App\Http\Resources\PostResource as PostResource;
use App\Http\Resources\ProfileResource;
use App\Models\Contact;
use App\Models\ContactRequest;
use App\Models\Post;
use App\Models\UserHasContact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ContactRequestController extends BaseController
{
    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'to_user_id' => 'required',
            'description' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $contactRequest = ContactRequest::create([
            'description'       => $request->get('description'),
            'from_user_id'      => Auth::id(),
            'to_user_id'        => $request->get('to_user_id'),
            'accepted'          => false
        ]);

        return $this->sendResponse(new ContactRequestResource($contactRequest));
    }

    /**
     * Display a listing of contact request to user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReceivedContactRequest($id): \Illuminate\Http\JsonResponse
    {
        $contactRequests = DB::table('contact_requests')->where('to_user_id', 'LIKE', $id)->get();
        return $this->sendResponse(ContactRequestResource::collection($contactRequests));
    }

    /**
     * Display a listing of contact request from user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSentContactRequest($id): \Illuminate\Http\JsonResponse
    {
        $contactRequests = DB::table('contact_requests')->where('from_user_id', 'LIKE', $id)->get();
        return $this->sendResponse(ContactRequestResource::collection($contactRequests));
    }


    /**
     * Display a listing of contact request from user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptContactRequest($id, Request $request): \Illuminate\Http\JsonResponse
    {

        $input = $request->all();

        $validator = Validator::make($input, [
            'to_user_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $contactRequest = ContactRequest::find($id);
        $contactRequest->accepted = true;

        $contact = Contact::create();

        $userContact = UserHasContact::create([
            'contact_id' => $contact->id,
            'user_id'    => Auth::id(),
        ]);

        $otherUserContact = UserHasContact::create([
            'contact_id' => $contact->id,
            'user_id'    => $input['to_user_id'],
        ]);

        return $this->sendResponse(new ContactRequestResource($contactRequest));
    }


}
