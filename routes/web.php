<?php

use App\Events\MyWebsocketEvent;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Conversation;
use App\Http\Controllers\ConversationController;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::get('/dashboard', function () {
    $users = User::all();
    // dd(auth()->user()->id);





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


    return Inertia::render('Dashboard',compact('users','conversation'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/conversation',[ConversationController::class,'store'])->name('conversation.store');
    Route::post('/message',[ConversationController::class,'storeMessage']);
    Route::get('/messages/{id}',[ConversationController::class,'getMessages'])->name('get.messages');
});

require __DIR__.'/auth.php';
