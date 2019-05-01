<?php
use Illuminate\Http\Request;
//use App\Entities\User;
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
Route::group(["prefix" => "students"], function ()
{
    Route::post('grades/user', 'GradesController@user');
    Route::post("image", "AuthController@image");
    Route::post("video", "AuthController@video");
    Route::post("file", "AuthController@file");
    Route::get('subscriptions/{subscription}/invoice', 'SubscriptionsController@invoice');
    Route::group(['middleware' => ['api', 'jwt.auth']], function ()
    {
        Route::resources(['days' => 'DaysController', 'lessons' => 'LessonsController', 'users' => 'UsersController', 'messagesAlls' => 'MessagesAllsController', 'badges' => 'BadgesController', 'points' => 'PointsController', 'give_badges' => 'GiveBadgesController', 'events' => 'EventsController', 'rooms' => 'RoomsController', 'teachers' => 'TeachersController', 'readings' => 'ReadingsController', 'subscriptions' => 'SubscriptionsController']);
        Route::resource('notes', 'NotesController');
        Route::post('quizzes/{quiz}/joint', "QuizzesController@joint");
        Route::post('quizzes/{quiz}/give_answer', "QuizzesController@giveAnswer");
        Route::get('users/generate/{user}', "UsersController@generate");
        Route::get('grades/generate/{grade}', "GradesController@generate");
        Route::resource('posts', 'PostsController', ['only' => ['store', 'update', 'show', 'destroy']]);
        Route::resource('comments', 'CommentsController', ['only' => ['store', 'update', 'show', 'destroy']]);
        Route::resource('answers', 'AnswersController', ['only' => ['store', 'update', 'show', 'destroy']]);
        Route::group(['middleware' => ['admin_perrmission']], function ()
        {
            Route::post('notifications/{user}', 'NotificationsController@store');
            Route::resource('locks', 'LocksController', ['only' => ['store', 'destroy']]);
        });
        Route::resource('messages', 'MessagesController', ['only' => ['destroy', 'store']]);
        Route::group(['middleware' => ['grade_perrmission']], function ()
        {
            Route::get('grades/{grade}/exists', 'GradesController@exists');
            Route::get('grades/{grade}/posts', 'GradesController@posts');
            Route::get('grades/{grade}/homeworks', 'GradesController@homeworks');
            Route::get('grades/{grade}/notes_from_lesson', 'GradesController@notes_from_lesson');
            Route::get('grades/{grade}/schedule', 'GradesController@schedule');
            Route::get('grades/{grade}/readings', 'GradesController@readings');
            Route::get('grades/{grade}/messagesAlls', 'GradesController@messagesAlls');
            Route::get('grades/{grade}/posts?q={query}', 'GradesController@postsSearch');
            Route::get('grades/{grade}/users', 'GradesController@users');
            Route::get('grades/{grade}/quizes', 'GradesController@quizes');
            Route::get('grades/{grade}/bearing', 'GradesController@bearing');
            Route::resource('grades', 'GradesController');
        });
        Route::post('posts/{post}/pick', 'PostsController@pick');
        Route::post('posts/{post}/unpick', 'PostsController@unpick');
        Route::get('messages/user/{user_id}', 'MessagesController@user');

        Route::resource('trusts', 'TrustsController', ['only' => 'store']);
    });
    Route::resource('schools', 'SchoolsController');
    Route::put('schools/{school}/card', 'SchoolsController@updateCard');
    Route::post('schools/{school}/buy/{subscription}', 'SchoolsController@Buy');
    Route::post('schools/{school}/subscription', 'SchoolsController@Subscription');
    Route::get('schools/{school}/users', 'SchoolsController@users');
    Route::get('schools/{school}/posts', 'SchoolsController@Posts');
    Route::post('schools/{school}/subscriptions', 'SchoolsController@Subscription');
    Route::resource('users', 'UsersController');
    Route::group([

    'middleware' => 'api', 'prefix' => 'auth'

    ], function ($router)
    {

        Route::post('login', 'AuthController@login');
        Route::post('qr', 'AuthController@qr');
        Route::post('id', 'AuthController@id');
        Route::get('notifications', 'AuthController@notifications');
        Route::post('logout', 'AuthController@logout');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('me', 'AuthController@me');

    });
    Route::post('grades/register', 'GradesController@register');

    Broadcast::routes(["middleware" => ["jwt.auth"]]);

});

//Route::get('get_token',function(){
//$user = \App\Entities\User::find(225);
//return Auth::login($user);
//});
//User::find(43)->update(['password'=>bcrypt("123456")]);