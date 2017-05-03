<?php

namespace App\Http\Controllers;

use App\Friendship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use App\Ticket;
use Auth;
use Image;
use File;
use App\User;

class ProfileController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');

    }

    /**
     * Funtion to show a user profile page
     *
     * @return profilePage
     */
    public function index($user_id)
    {
        $friendshipController = new FriendshipController();
        $ticketController = new TicketsController();

        // Get user info
        $user = User::find($user_id);

        //Get if the user is a friend
        $isFriend = collect($friendshipController->isFriend($user_id)->getData());

        //Get if the user is a friend
        $isPending = collect($friendshipController->hasPendingFriendRequestFrom($user_id)->getData());

        //Get a request has already been sent
        $requestSent = collect($friendshipController->hasPendingFriendRequestSent($user_id)->getData());

        //Get auth user tickets for transfer
        $authUserTicket = $ticketController->getTickets();

        if ($authUserTicket->getStatusCode() != 204)
        {
            $authUserTicket = collect($authUserTicket->getData());
        }
        else
        {
            $authUserTicket = null;
        }

        //Get profile user tickets
        $profileUserTickets = $ticketController->getIncomingFriendTickets($user_id);

        if ($profileUserTickets->getStatusCode() != 403)
        {
            $profileUserTickets = collect($profileUserTickets->getData());
        }
        else
        {
            $profileUserTickets = null;
        }


        return view('profile.userProfile', [
                                                'user' => $user,
                                                'isFriend' => $isFriend,
                                                'isPending' => $isPending,
                                                'hasSendRequest' => $requestSent,
                                                'arrayTickets' => $profileUserTickets,
                                                'authUserTicket' => $authUserTicket
                                            ]);
    }

    /**
     *
     */
    public function accept($user_id)
    {
        $friendshipController = new FriendshipController();
        $friendship = Friendship::Where('requester' , $user_id)->where('user_requested', Auth::id())->first();
        $friendshipController->acceptFriend($friendship->id);

        return redirect()->action('ProfileController@index' , $user_id);
    }

    /**
     *
     */
    public function remove($user_id)
    {

        $friendshipController = new FriendshipController();
        $friendshipController->deleteFriendship($user_id);

        return redirect()->action('ProfileController@index' , $user_id);

    }

    public function add(Request $request, $user_id)
    {
        $friendshipController = new FriendshipController();
        $friendshipController->addFriend($request);

        return redirect()->action('ProfileController@index' , $user_id);
    }

    public function transfer(Request $request, $user_id)
    {
        $ticketController = new TicketsController();

        $ticketController->transferTicket($request->uniqueTicketId, $user_id);

        return redirect()->action('ProfileController@index' , $user_id);
    }
}