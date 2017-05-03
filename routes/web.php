<?php

use App\Ticket;
use App\Friendship;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();


Route::get('/home', function () {
    if(Auth::check())
    {
        $userID = Auth::id();
        $arrayTickets = Ticket::where('user_id', $userID)->get();


        if ($arrayTickets != null)
        {
            return view('/home', compact('arrayTickets'));
        }

        else
        {
            return view('/home');
        }
    }
    else
    {
        return response()
            ->json(["code" => "401", "description" => "Unauthorized", "message" => 'Unauthorized. Please connect with an authorized account.'], 401)
            ->header('Content-Type', 'application/json');
    }
});

Route::get('/friendships', function() {

    if(Auth::check())
    {
        $friendshipController = new \App\Http\Controllers\FriendshipController();

        $friendshipRequest = $friendshipController->getPendingFriendRequestsReceived()->getData();

        if($friendshipRequest != null)
        {
            return view('/friendship', compact('friendshipRequest'));
        }
    }
});

/*
 * Routes pour la page de profile settings
 */
Route::get('/profileSettings', 'ProfileSettingsController@index');
Route::post('/profileSettingsAvatar', 'ProfileSettingsController@update_avatar');
Route::put('/profileSettingsUpdate', 'ProfileSettingsController@update');
Route::patch('/profileSettingsUpdatePw', 'ProfileSettingsController@updatePassword');
/*
 * Routes pour la page friends
 */
Route::get('/friends', 'FriendsController@index');

/*
 * Routes pour la recherche d'ami
 */
Route::get('/searchFriends', 'SearchController@getUserList');

/*
 * Routes pour la page profile d'un ami
 */
Route::get('/profile/{user_id}', 'ProfileController@index');
Route::post('/profile/{user_id}', 'ProfileController@add');
Route::patch('/profile/{user_id}', 'ProfileController@accept');
Route::patch('/ticketTransfer/{user_id}', 'ProfileController@transfer');
Route::delete('/profile/{user_id}', 'ProfileController@remove');

/*
 * Routes pour l'affichage des spectacles de ses amis
 */
Route::get('/friendsspectables', 'FriendsSpectaclesController@index');

/*
 * Route through Logging system.
 */
Route::get('/logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

/*
 * Protected route to API management interface.
 */
Route::get('api/management', [
    'middleware' => 'auth',
    'uses' => 'ApiOAuthController@index'
]);

/*
 * Route to get a PDF version of a ticket.
 */
Route::get('pdfTicket', [
    'as' => 'pdfTicket',
    'uses' => 'TicketsController@pdfTicket'
]);


