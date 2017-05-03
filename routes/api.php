<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('v1/user', function (Request $request) {
    return $request->user();
});


/*
 * Friends routes
 */

Route::middleware('auth:api')->get('v1/user/friends', 'FriendshipController@getFriends');

Route::middleware('auth:api')->get('v1/user/friend/{friend_id}', 'FriendshipController@getFriend');

Route::middleware('auth:api')->get('v1/user/friends/ids', 'FriendshipController@getFriendIDs');

Route::middleware('auth:api')->get('v1/user/isFriend/{user_id}', 'FriendshipController@isFriend');

Route::middleware('auth:api')->delete('v1/user/friend/{friend_id}', 'FriendshipController@deleteFriendship');

/*
 * Friendship routes
 */

Route::middleware('auth:api')->post('v1/user/friendship/', 'FriendshipController@addFriend');

Route::middleware('auth:api')->patch('v1/user/friendship/{friendship_id}', 'FriendshipController@acceptFriend');

Route::middleware('auth:api')->delete('v1/user/friendship/{friendship_id}', 'FriendshipController@deleteFriendRequest');

Route::middleware('auth:api')->get('v1/user/friendship/{friendship_id}', 'FriendshipController@getFriendship');

Route::middleware('auth:api')->get('v1/user/friendships/pending/received', 'FriendshipController@getPendingFriendRequestsReceived');

Route::middleware('auth:api')->get('v1/user/friendships/pending/sent', 'FriendshipController@getPendingFriendRequestSent');

Route::middleware('auth:api')->get('v1/user/friendships/pending/ids/', 'FriendshipController@getPendingFriendRequestIDs');

Route::middleware('auth:api')->get('v1/user/friendships/pending/ids/sent/', 'FriendshipController@getPendingFriendRequestsSentIDs');

Route::middleware('auth:api')->get('v1/user/friendships/pending/from/{target_user_id}', 'FriendshipController@hasPendingFriendRequestFrom');

Route::middleware('auth:api')->get('v1/user/friendships/pending/sent/{target_user_id}', 'FriendshipController@hasPendingFriendRequestSent');


/*
 * Ticket's routes
 */

Route::middleware('auth:api')->get('v1/user/tickets', 'TicketsController@getTickets');

Route::middleware('auth:api')->post('v1/user/tickets', 'TicketsController@addTicket');

Route::middleware('auth:api')->get('v1/user/ticket/{ticket_id}', 'TicketsController@getTicket');

Route::middleware('auth:api')->get('v1/user/{friend_id}/tickets', 'TicketsController@getFriendTickets');

Route::middleware('auth:api')->patch('v1/user/ticket/{ticket_id}/owner/{new_user_id}', 'TicketsController@transferTicket');


/*
 * User's routes
 */

Route::middleware('auth:api')->get('v1/users', 'UserController@getUsers');

Route::middleware('auth:api')->get('v1/user/{user_id}', 'UserController@getUser');



