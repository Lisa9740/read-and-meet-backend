<?php

namespace App\Http\Controllers\API;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\ChatResource as ChatResource;

class ChatController extends BaseController
{
    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $chats = Chat::all();
        return $this->sendResponse(ChatResource::collection($chats));
    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {

        $isExistingChatWithUser = DB::table('chats')
            ->where('user_id', 'LIKE', $request->get('user_id'))
            ->orWhere('author_id', 'LIKE', $request->get('user_id'));

        if ($isExistingChatWithUser->count() < 1){
            $chat = new Chat();
            $chat->author_id = Auth::id();
            $chat->user_id = $request->get('user_id');
            $chat->save();
            return $this->sendResponse($chat);
        }
        return $this->sendResponse($isExistingChatWithUser->first());

    }


    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function createMessage(Request $request): JsonResponse
    {

        $message = new Message();
        $message->chat_id = $request->get('chat_id');
        $message->user_id = Auth::id();
        $message->receiver_id = $request->get('user_id');
        $message->message_txt = $request->get('content');
        $message->image_url = "test";

        $message->save();
        return $this->sendResponse($message);
    }

    /**
     * Display the specified resource.
     * @return JsonResponse
     */
    public function showByUser(): JsonResponse
    {
        $chat = DB::table('chats')->where('user_id', 'LIKE', Auth::id())->orWhere('author_id','LIKE', Auth::id())->get();

        if (is_null($chat)) {
            return $this->sendError('Chats not found.');
        }
        return $this->sendResponse($chat);
    }


    /**
     * Display the specified resource.
     * @return JsonResponse
     */
    public function showMessagesByUser(): JsonResponse
    {
        $messages = DB::table('messages')->where('user_id', 'LIKE', Auth::id())->orWhere('receiver_id','LIKE', Auth::id())->get();

        if (is_null($messages)) {
            return $this->sendError('Chats not found.');
        }
        return $this->sendResponse($messages);
    }


    /**
     * Display the specified resource.
     * @return JsonResponse
     */
    public function get($id): JsonResponse
    {
        $chat = DB::table('chats')->where('id', 'LIKE', $id)->get();

        if (is_null($chat)) {
            return $this->sendError('Chats not found.');
        }
        return $this->sendResponse($chat);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $chat = Chat::findOrFail($id);
        $chat->delete();

        return $this->sendResponse(null);
    }

}