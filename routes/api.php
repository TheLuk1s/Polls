<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

use App\Http\Controllers\Users;
use App\Http\Controllers\Poll;
use App\Http\Controllers\Option;
use App\Http\Controllers\Answer;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);    

    // Apklausos
    Route::delete('poll/{id}', [Poll::class, 'deletePoll']);

    Route::get('poll', [Poll::class, 'getPollList']);
    Route::get('poll/{id}', [Poll::class, 'getPoll']);

    Route::post('poll', [Poll::class, 'createPoll']);
    Route::put('poll/{id}', [Poll::class, 'updatePoll']);

    // Atsakymai
    Route::get('poll/{pollID}/answer', [Answer::class, 'getAnswerList']);
    Route::get('poll/{pollID}/answer/{answerID}', [Answer::class, 'getAnswer']);

    Route::post('poll/{pollID}/answer', [Answer::class, 'createAnswer']);
    Route::put('poll/{pollID}/answer/{answerID}', [Answer::class, 'updateAnswer']);

    Route::delete('poll/{pollID}/answer/{answerID}', [Answer::class, 'deleteAnswer']);

    // Pasirinkimai
    Route::get('poll/{pollID}/option', [Option::class, 'getOptionList']);
    Route::get('poll/{pollID}/option/{optionID}', [Option::class, 'getOption']);

    Route::post('poll/{pollID}/option', [Option::class, 'createOption']);
    Route::put('poll/{pollID}/option/{optionID}', [Option::class, 'updateOption']);

    Route::delete('poll/{pollID}/option/{optionID}', [Option::class, 'deleteOption']);
});