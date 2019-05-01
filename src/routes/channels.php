<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Entities.User', function () {
    return Auth::user();
});
Broadcast::channel('App.Entities.User.{id}', function ($user, $id) {
    return Auth::user();
});


Broadcast::channel('message',function($user){
  return Auth::user();
});
Broadcast::channel('messages_all',function($user){
  return Auth::user();
});
Broadcast::channel('posts',function($user){
  return Auth::user();
});
Broadcast::channel('points',function($user){
  return Auth::user();
});
Broadcast::channel('lock',function($user){
  return Auth::user();
});
Broadcast::channel('answers',function($user){
  return Auth::user();
});
Broadcast::channel('comments',function($user){
  return Auth::user();
});
Broadcast::channel('Online',function(){
  return Auth::user();
});
Broadcast::channel('video_chat',function(){
return Auth::user();
});
Broadcast::channel('quizes',function(){
return Auth::user();
});