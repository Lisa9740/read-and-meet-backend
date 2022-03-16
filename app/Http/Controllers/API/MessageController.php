<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\MessageResource;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends BaseController
{

    /**
     * Display the specified resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $messages = Message::all();
        return $this->sendResponse(MessageResource::collection($messages));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $message = new Message();
        $message->chat_id = $request->get('chat_id');
        $message->user_id = Auth::id();
        $message->receiver_id = $request->get('user_id');
        $message->message_txt = $request->get('message_txt');
        $message->image_url = "test";

        $message->save();
        return $this->sendResponse($message);
    }


    /**
     * Display the specified resource.
     * @return JsonResponse
     */
    public function showMessagesByChat($chatId): JsonResponse
    {
        $messages = DB::table('messages')->where('chat_id', 'LIKE', $chatId)->get();

        if (is_null($messages)) {
            return $this->sendError('Message chats not found.');
        }
        return $this->sendResponse($messages);
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $chat = Message::findOrFail($id);
        $chat->delete();

        return $this->sendResponse(null);
    }

}
