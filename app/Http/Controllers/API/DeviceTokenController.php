<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\DeviceTokenResource;
use App\Http\Resources\NotificationResource;
use App\Models\DeviceToken;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeviceTokenController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $devices = DeviceToken::all();
        return $this->sendResponse(DeviceTokenResource::collection($devices));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {

        $existingDevice = DB::table("device_tokens")->where("registration_token", $request->get('registration_token'))->get();

        if ($existingDevice->count() > 0){
            return $this->sendResponse($existingDevice->first());
        }else {
            $device = DeviceToken::create([
                'registration_token' => $request->get('registration_token'),
                'user_id' => Auth::id(),
            ]);
            return $this->sendResponse($device);
        }


    }



}
