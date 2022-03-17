<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NotificationController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $notifications = Notification::all();
        return $this->sendResponse(NotificationResource::collection($notifications));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $notifications =  Notification::create([
            'title' => $request->get('title'),
            'content' => $request->get('content'),
            'type' =>$request->get('type'),
            'user_id' => Auth::id(),
        ]);
        return $notifications;
    }

    /**
     * Display the specified resource.
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $notification = Notification::find($id);
        if (is_null($notification)) {
            return $this->sendError('notification not found.');
        }
        return $this->sendResponse(new NotificationResource($notification));
    }


}
