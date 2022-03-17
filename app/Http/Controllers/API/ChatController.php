<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\MessageResource;
use App\Http\Resources\UserResource;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
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
    public function store(Request $request): JsonResponse
    {
        $isExistingChatWithUser = DB::table('chats')->where('participant_one', 'LIKE', $request->get('user_id'))->orWhere('participant_two', 'LIKE', $request->get('user_id'));

        $participant1 = DB::table('users')->where('id', "LIKE", Auth::id())->get();
        $participant2 = DB::table('users')->where('id', "LIKE", $request->get('user_id'))->get();

        $chat = new Chat();
        $chat->participant_one = Auth::id();
        $chat->participant_two = $request->get('user_id');
        $chat->save();

        $chatInfo = ['id'  => $chat->id,
            'participant1' => $participant1,
            'participant2' => $participant2];

        return $this->sendResponse($chatInfo);

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
        $message->message_txt = $request->get('message_txt');
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
        $chats = DB::table('chats')->where('participant_one', 'LIKE', Auth::id())->orWhere('participant_two','LIKE', Auth::id())->get();
        $participant1 = DB::table('users')->where('id', "LIKE", Auth::id())->get();


        $chatsInfo = [];
        foreach ($chats as $chat){
            if ($chat->participant_one != Auth::id()){
                $searchedId = $chat->participant_one;
            }else{
                $searchedId = $chat->participant_two;
            }

            $participant2 = DB::table('users')->where('id', "LIKE", $searchedId)->get();
            $messages = DB::table('messages')->where("chat_id", "LIKE", $chat->id)->get();
            $chatsInfo[] = [
                'id'           => $chat->id,
                'participant1' => $participant1,
                'participant2' => $participant2,
                'messages' => $messages
            ];
        }

        if (is_null($chats)) {
            return $this->sendError('Chats not found.');
        }
        return $this->sendResponse($chatsInfo);
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
