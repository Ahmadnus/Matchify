<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlockController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PeopleController;
use App\Http\Controllers\UserAnswerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::post('signup', 'signup')->name('signup');
    Route::post('signupAdmin', 'siginUpAd')->name('signup.admin');
    Route::post('login', 'login')->name('login');
    Route::post('loginAdmin', 'loginAd')->name('login.admin');
    Route::post('logout/{id}', 'logout')->name('logout');
});

Route::prefix('user-answers')->controller(UserAnswerController::class)->group(function () {
    Route::get('/', 'index')->name('user-answers.index');
    Route::post('/', 'store')->name('user-answers.store');


    Route::get('{userAnswer}', 'showUserAnswers')->name('user-answers.show-by-user');
    Route::put('{userAnswer}', 'update')->name('user-answers.update');
    Route::delete('{userAnswer}', 'destroy')->name('user-answers.destroy');


});
Route::prefix('likes')->middleware('auth:sanctum')->group(function () {
    Route::post('/', [LikeController::class, 'sendLike']);
    Route::put('/{likeId}/respond', [LikeController::class, 'respond']);
    Route::get('/pending', [LikeController::class, 'pending']);
    Route::get('/accepted', [LikeController::class, 'accepted']);
});
// routes/api.php أو web.php حسب حاجتك
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/friends/accepted', [FriendController::class, 'getAcceptedFriends']);
    Route::get('/friends/accepted/ids', [FriendController::class, 'getAcceptedFriendIds']);
});
Route::post('/people', [PeopleController::class, 'index']);
Route::post('/update-location', [PeopleController::class, 'updateLocation'])->middleware('auth:sanctum');


Route::middleware('auth:sanctum')->group(function () {

    // إنشاء أو جلب محادثة بين مستخدمين
    Route::post('/chats', [MessageController::class, 'createOrGet']);

    // جلب كل المحادثات الخاصة بالمستخدم
    Route::get('/chats', [MessageController::class, 'indexx']);

    // جلب الرسائل لمحادثة معينة
    Route::get('/chats/{chat}/messages', [MessageController::class, 'index']);

    // إرسال رسالة
    Route::post('/messages/{chat}', [MessageController::class, 'send']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/block', [BlockController::class, 'block']);
    Route::post('/unblock', [BlockController::class, 'unblock']);
    Route::get('/blocked-users', [BlockController::class, 'blockedUsers']);
});