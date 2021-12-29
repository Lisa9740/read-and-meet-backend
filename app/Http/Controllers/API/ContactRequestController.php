<?php

namespace App\Http\Controllers\API;

use App\Models\Contact;
use App\Models\ContactRequest;
use App\Models\User;
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
    public function createRequest(Request $request): JsonResponse
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

        return $this->sendResponse($contactRequest);
    }

    /**
     * Display a listing of contact request to user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReceivedContactRequest(): \Illuminate\Http\JsonResponse
    {
        $contactRequests = DB::table('contact_requests')->where('to_user_id', 'LIKE', Auth::id())->get();

//        $list = [];
//
//        foreach ($contactRequests as $request) {
//            $list[] = [
//                'from'          => $this->getUserInfo($request->from_user_id),
//                'description' => $request->description,
//                'status'      => $request->accepted,
//            ];
//        }
        return $this->sendResponse($contactRequests);
    }

    /**
     * Display a listing of contact request from user.
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSentContactRequest(): \Illuminate\Http\JsonResponse
    {
        $contactRequests = DB::table('contact_requests')->where('from_user_id', 'LIKE', Auth::id())->get();
        $list = [];

        foreach ($contactRequests as $request) {
            $list[] = [
                'to'          => $this->getUserInfo($request->to_user_id),
                'description' => $request->description,
                'status'      => $request->accepted,
            ];
        }
        return $this->sendResponse($list);
    }

    /**
     * Set contact request as accepted and then remove the contact request.
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function acceptContactRequest(int $id): \Illuminate\Http\JsonResponse
    {

        $contactRequest = ContactRequest::find($id);

        if (Auth::id() === $contactRequest->to_user_id){
            $contactRequest->accepted = true;
            $contact = Contact::create([]);
            $this->createUsersContact($contact->id, $contactRequest->from_user_id);
            $contactRequest->delete();
            $contactRequest->save();

            return $this->sendResponse('Requête accepté');
        }
        return $this->sendResponse('Forbidden');
    }




    /**
     * remove the contact request
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function removeContactRequest(int $id): \Illuminate\Http\JsonResponse
    {
        $contactRequest = ContactRequest::find($id);

        if (Auth::id() === $contactRequest->to_user_id){
            ContactRequest::destroy($id);
            $contactRequest->save();

            return $this->sendResponse('Requête supprimé');
        }
        return $this->sendResponse('Forbidden');
    }

    function createUsersContact($id, $fromUserId){
        UserHasContact::create([
            'contact_id' => $id,
            'user_id'    => Auth::id(),
        ]);

       UserHasContact::create([
            'contact_id' => $id,
            'user_id'    => $fromUserId,
        ]);
    }

    function getUserInfo($id): array
    {
        $user = User::find($id);
        return ['id' => $user->id, 'name' => $user->name, 'avatar' => $user->user_picture];
    }
}
