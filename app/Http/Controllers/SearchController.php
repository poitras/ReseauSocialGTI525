<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Friendship;
use App\User;
use Auth;

class SearchController extends Controller
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
     * Search for a user with input
     *
     * @return \Illuminate\Http\Response
     */
    public function getUserList(Request $request)
    {
        $collectionFriends=null;
        $collectionPendings=null;
        $collectionSent=null;

        $array = array();

        $array_pending = array();

        $friendsCollection = null;
        $pendingFriendsCollection = null;
        $friendsRequestSentCollection = null;

        $input = studly_case($request->userInput);

        $users = User::where('id','!=', Auth::id())
                        ->Where(function ($query) use ($input) {
                            $query->Where('email', 'LIKE', '%' . $input . '%')
                                ->orWhere('first_name', 'LIKE', '%' . $input . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $input . '%');
                        })
                        ->get();

        $FriendshipController = new FriendshipController();
        $friendIDRequest = $FriendshipController->getFriendIDs();
        $pendingFriendsIDSRequest = $FriendshipController->getPendingFriendRequestIDs();
        $friendsRequestSentCollection = $FriendshipController->getPendingFriendRequestsSentIDs();

        if($friendIDRequest->getStatusCode() != 204)
        {
            $friendIDs =  $friendIDRequest->getData();

            foreach ($friendIDs as $friend)
            {
                $collectionFriends = collect($friend);

                $id = $collectionFriends->get('user_id');

                array_push($array, $id);
            }

            $friendsCollection = collect($array);

        }

        if($pendingFriendsIDSRequest->getStatusCode() != 204)
        {
            $friendIDs =  $pendingFriendsIDSRequest->getData();

            foreach ($friendIDs as $friend)
            {
                $collectionFriends = collect($friend);

                $id = $collectionFriends->get('user_id');

                array_push($array_pending, $id);
            }

            $pendingFriendsCollection = collect($array_pending);
        }

        if($friendsRequestSentCollection->getStatusCode() != 204)
        {
            $friendIDs =  $friendsRequestSentCollection->getData();

            foreach ($friendIDs as $friend)
            {
                $collectionSent = collect($friend);

                $id = $collectionSent->get('user_id');

                array_push($array_pending, $id);
            }

            $friendsRequestSentCollection = collect($array_pending);
        }
        else
        {
            $friendsRequestSentCollection = null;
        }

        return view('friends.friends', ['users' => $users, 'friendIds' => $friendsCollection, 'pendingIds' => $pendingFriendsCollection, 'sentIds' => $friendsRequestSentCollection]);

    }
}