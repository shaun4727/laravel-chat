<?php

namespace App\Http\Controllers;

use App\Events\SendMessageEvent;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Messages;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    public function store(Request $request){
        $validated=$request->validate([
            'user_id'=>'required|max:255',
            'participantOne' => 'required|max:255',
            'participantTwo' => 'required|max:255',
            'last_message' => ''
        ]);

        $conv = Conversation::where('user_id',$validated['user_id'])
                ->where('participantOne',$validated['participantOne'])->get();

        if(count($conv) === 0){
            Conversation::create($validated);

        }

    }

    public function storeMessage(Request $request){
        $validated=$request->validate([
            'conversation_id'=>'required|max:255',
            'sender' => 'required|max:255',
            'receiver' => 'required|max:255',
            'message' => ''
        ]);


        $con = Conversation::find($validated['conversation_id']);
        $con->last_message = $validated['message'];
        $con->save();

        Messages::create($validated);
        broadcast(new SendMessageEvent($validated));
    }
    public function findConversation($array,$id){
        foreach($array as $item){
            if($item->id == $id){
                return $item;
            }
        }
    }

    public function getMessages($id){
        $messages = Messages::where('conversation_id',$id)->get();
        // dd($messages);
        $users = User::all();

        $conversation = DB::table('conversations')
        ->leftJoin('users as participantOneInfo', 'conversations.participantOne', '=', 'participantOneInfo.id')
        ->leftJoin('users as participantTwoInfo', 'conversations.participantTwo', '=', 'participantTwoInfo.id')
        ->select(
            'conversations.*',
            'participantOneInfo.name as participantOne',
            'participantTwoInfo.name as participantTwo',
            'participantOneInfo.id as participantOneId',
            'participantTwoInfo.id as participantTwoId',
        )
        ->where('participantOne','=',auth()->user()->id)
        ->orWhere('participantTwo','=',auth()->user()->id)
        ->get();

        $singleConversation = $this->findConversation($conversation,$id);
        return Inertia::render('Dashboard',compact('messages','users','conversation','singleConversation'));
    }
}
