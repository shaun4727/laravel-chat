<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;

class ConversationController extends Controller
{
    public function store(Request $request){
        $validated=$request->validate([
            'user_id'=>'required|max:255',
            'participantOne' => 'required|max:255',
            'participantTwo' => 'required|max:255',
            'last_message' => ''
        ]);
        // Customer::create($validated)
        Conversation::create($validated);
    }
}
