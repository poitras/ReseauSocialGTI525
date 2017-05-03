<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Friendship;
use App\User;

class FriendshipController extends Controller
{
    /**
     * @SWG\Post(
     *   path="/user/friendship",
     *   summary="Send a friend request to target user",
     *   tags={"friendships"},
     *   operationId="add_friend",
     *
     *   @SWG\Parameter(
     *     name="friendship_request",
     *     in="body",
     *     description="A friendship request form.",
     *     required=true,
     *     @SWG\Schema(
     *       ref="#/definitions/Friendship_form",
     *     ),
     *   ),
     *
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *         @SWG\Schema(ref="#/definitions/Friendships")
     *   ),
     *
     *   @SWG\Response(
     *     response=403,
     *     description="Requester mismatch the current authenticated user.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     *
     *   @SWG\Response(
     *     response=406,
     *     description="Ticket does not belongs to you. Cannot transfer ticket.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     *
     *   @SWG\Response(
     *         response=400,
     *         description="Multiple error definitions.",
     *         @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function addFriend(Request $request)
    {
        $auth_user_id = Auth::id();

        $friendship = null;

        $requester = (int)$request->input('requester');

        $user_requested_id = (int)$request->input('user_requested');

        if(User::where('id',$user_requested_id)->exists())
        {
            if($requester == $auth_user_id)
            {
                if($user_requested_id != $auth_user_id)
                {
                    if(!(Friendship::where('requester', $requester)->where('user_requested', $user_requested_id)->exists()))
                    {
                        $friendship = Friendship::firstOrCreate([
                            'requester' => $auth_user_id,
                            'user_requested' => $user_requested_id
                        ]);

                        $url = "/user/friendship/" . (string)($friendship->id);

                        $friendship['url'] = $url;

                        return response()
                            ->json($friendship, 201)
                            ->header('Content-Type', 'application/json');
                    }

                    else
                    {
                        return response()
                            ->json(["code" => "400", "description" => "Bad request.", 'message' => 'Friendship is already created.'], 400)
                            ->header('Content-Type', 'application/json');
                    }
                }

                else
                {
                    return response()
                    ->json(["code" => "400", "description" => "Bad request.", 'message' => 'You cannot send a friend request to yourself.'], 400)
                    ->header('Content-Type', 'application/json');
                }
            }

            else
            {
                return response()
                    ->json(["code" => "403", "description" => "Forbidden.", 'message' => 'Requester mismatch the current authenticated user.'], 403)
                    ->header('Content-Type', 'application/json');
            }
        }

        else
        {
            return response()
                ->json(["code" => "404", "description" => "Not found.", 'message' => 'The specified user does not exist.'], 404)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Patch(
     *   path="/user/friendship/{friendship_id}",
     *   summary="Accept friend request from target user",
     *   tags={"friendships"},
     *   operationId="accept_friend",
     *   @SWG\Parameter(
     *     name="friendship_id",
     *     in="path",
     *     description="Target friend that requested the friendship",
     *     required=true,
     *     type="integer",
     *     format="int32"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Successful operation.",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Friendship already accepted.",
     *     @SWG\Schema(ref="#/definitions/Error")
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="Friendship request not found.",
     *     @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function acceptFriend($friendshipID)
    {
        $auth_user_id = Auth::id();
        $friendship = null;

        $friendship = Friendship::where('id', $friendshipID)->where('user_requested', $auth_user_id)->first();

        if ($friendship)
        {
            if($friendship->status == 0)
            {
                $friendship->update([
                    'status' => 1
                ]);

                return response()
                    ->json(["code" => "200", "description" => "OK", "message" => 'Friendship accepted.'], 200)
                    ->header('Content-Type', 'application/json');
            }

            else
            {
                return response()
                    ->json(["code" => "400", "description" => "Bad request.", "message" => 'Friendship already accepted.'], 400)
                    ->header('Content-Type', 'application/json');
            }

        }

        return response()
            ->json(["code" => "404", "description" => "Bad request.", "message" => 'Friendship request not found.'], 404)
            ->header('Content-Type', 'application/json');
    }

    /**
     * @SWG\Get(
     *   path="/user/friendship/{friendship_id}",
     *   summary="Display the specified friendship object.",
     *   tags={"friendships"},
     *   operationId="getFriendship",
     *   @SWG\Parameter(
     *     name="friendship_id",
     *     in="path",
     *     description="Target friendship ID.",
     *     required=true,
     *     type="integer",
     *     format="int32"
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Successful operation.",
     *   ),
     *   @SWG\Response(
     *     response=403,
     *     description="You are not the requester of this friendship. You are not authorized to see it.",
     *     @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function getFriendship($friendship_id)
    {
       $auth_user_id = Auth::id();

       $friendship = Friendship::where('id', $friendship_id)->first();

       if($friendship->requester == $auth_user_id)
       {
           return response()
               ->json($friendship, 200)
               ->header('Content-Type', 'application/json');
       }

       else
       {
           return response()
               ->json(["code" => "403", "description" => " Forbidden.", "message" => 'You are not the requester of this friendship. You are not authorized to see it.'], 400)
               ->header('Content-Type', 'application/json');
       }
    }

    /**
     * @SWG\Delete(
     *   path="/user/friendship/{friendship_id}",
     *   summary="Delete a friend request from target user",
     *   tags={"friendships"},
     *   operationId="accept_friend",
     *   @SWG\Parameter(
     *     name="friendship_id",
     *     in="path",
     *     description="Target friend that requested the friendship",
     *     required=true,
     *     type="integer",
     *     format="int32"
     *   ),
     *   @SWG\Response(
     *       response=200,
     *       description="Successful operation.",
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Friendship is not in a pending state.",
     *     @SWG\Schema(ref="#/definitions/Error")
     *   ),
     *   @SWG\Response(
     *     response=404,
     *     description="Friendship request not found.",
     *     @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function deleteFriendRequest($friendshipID)
    {
        $auth_user_id = Auth::id();
        $friendship = null;

        $friendship = Friendship::where('id', $friendshipID)->where('user_requested', $auth_user_id)->first();

        if ($friendship)
        {
            if($friendship->status == 0)
            {
                $friendship->delete();

                return response()
                    ->json(["code" => "200", "description" => "OK", "message" => 'Friendship request deleted.'], 200)
                    ->header('Content-Type', 'application/json');
            }

            else
            {
                return response()
                    ->json(["code" => "400", "description" => "Bad request.", "message" => 'Friendship is not in a pending state.'], 400)
                    ->header('Content-Type', 'application/json');
            }
        }

        return response()
            ->json(["code" => "404", "description" => "Not found.", "message" => 'Friendship request not found.'], 404)
            ->header('Content-Type', 'application/json');
    }

    /**
     * @SWG\Get(
     *   path="/user/friendships/pending/received",
     *   summary="Return the users from which the authenticated user has pending friend requests.",
     *   tags={"friendships"},
     *   operationId="getPendingFriendRequestsReceived",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *
     *      @SWG\Schema(
     *         type="array",
     *          @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="first_name", type="string"),
     *                 @SWG\Property(property="last_name", type="string"),
     *                 @SWG\Property(property="email", type="string"),
     *                 @SWG\Property(property="address", type="string"),
     *                 @SWG\Property(property="city", type="string"),
     *                 @SWG\Property(property="province", type="string"),
     *                 @SWG\Property(property="postal_code", type="string"),
     *                 @SWG\Property(property="avatar", type="string"),
     *                 @SWG\Property(property="created_at", type="date"),
     *                 @SWG\Property(property="updated_at", type="date"),
     *         ),
     *      ),
     *   ),
     *   @SWG\Response(
     *     response=204,
     *     description="You have no incoming pending friendship request.",
     *     @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function getPendingFriendRequestsReceived()
    {
        $auth_user_id = Auth::id();
        $f1 = null;
        $users = array();

        $f1 = Friendship::where('status', 0)->where('user_requested', $auth_user_id)->get();

        if ($f1->count() != 0)
        {
            foreach ($f1 as $friendship)
            {
                array_push($users, User::find($friendship->requester));
            }

            return response()->json($users, 200)
                ->header('Content-Type', 'application/json');
        }

        else
        {
            return response()
                ->json(["code" => "204", "description" => "No content.", "message" => 'You have no incoming pending friendship request.'], 204)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/friendships/pending/sent",
     *   summary="Return the users for which the authenticated user has sent friend requests.",
     *   tags={"friendships"},
     *   operationId="getPendingFriendRequestSent",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *
     *      @SWG\Schema(
     *         type="array",
     *          @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="first_name", type="string"),
     *                 @SWG\Property(property="last_name", type="string"),
     *                 @SWG\Property(property="email", type="string"),
     *                 @SWG\Property(property="address", type="string"),
     *                 @SWG\Property(property="city", type="string"),
     *                 @SWG\Property(property="province", type="string"),
     *                 @SWG\Property(property="postal_code", type="string"),
     *                 @SWG\Property(property="avatar", type="string"),
     *                 @SWG\Property(property="created_at", type="date"),
     *                 @SWG\Property(property="updated_at", type="date"),
     *         ),
     *      ),
     *   ),
     *
     *   @SWG\Response(
     *     response=204,
     *     description="You have not sent any friendship request.",
     *     @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function getPendingFriendRequestSent()
    {

        $auth_user_id = Auth::id();

        $users = array();

        $friendships = Friendship::where('status', 0)->where('requester', $auth_user_id)->get();

        if(!($friendships->isEmpty()))
        {

            foreach ($friendships as $friendship)
            {
                array_push($users, User::find($friendship->user_requested));
            }

            return response()->json($users);
        }

        else
        {
            return response()
                ->json(["code" => "204", "description" => "No content.", "message" => 'You have not sent any friendship request.'], 204)
                ->header('Content-Type', 'application/json');
        }

    }

    /**
     * @SWG\Get(
     *   path="/user/friends",
     *   summary="Return the users that you are friend with.",
     *   tags={"friends"},
     *   operationId="getFriends",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *
     *        @SWG\Schema(
     *         type="array",
     *          @SWG\Items(
     *                 type="object",
     *                 @SWG\Property(property="id", type="integer"),
     *                 @SWG\Property(property="first_name", type="string"),
     *                 @SWG\Property(property="last_name", type="string"),
     *                 @SWG\Property(property="email", type="string"),
     *                 @SWG\Property(property="address", type="string"),
     *                 @SWG\Property(property="city", type="string"),
     *                 @SWG\Property(property="province", type="string"),
     *                 @SWG\Property(property="postal_code", type="string"),
     *                 @SWG\Property(property="avatar", type="string"),
     *                 @SWG\Property(property="created_at", type="date"),
     *                 @SWG\Property(property="updated_at", type="date"),
     *         ),
     *      ),
     *  ),
     *   @SWG\Response(
     *     response=204,
     *     description="There are no accepted friends.",
     *   ),
     * )
     */
    public function getFriends()
    {
        $auth_user_id = Auth::id();
        $friends = array();
        $friends2 = array();
        $f1 = null;
        $f1 = null;

        $f1 = Friendship::where('status', 1)->where('requester', $auth_user_id)->get();
        $f2 = Friendship::where('status', 1)->where('user_requested', $auth_user_id)->get();

        if($f1->count() != 0  || $f2->count() != 0)
        {
            foreach ($f1 as $friendship)
            {
                array_push($friends, User::find($friendship->user_requested));
            }

            foreach ($f2 as $friendship)
            {
                array_push($friends2, User::find($friendship->requester));
            }

            return response()->json(array_merge($friends, $friends2))
                ->header('Content-Type', 'application/json');
        }

        else
        {
            return response("There are no accepted friends.", 204);
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/friend/{friend_id}",
     *   summary="Return the specified user if this user is a friend.",
     *   tags={"friends"},
     *   operationId="getFriend",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *
     *      @SWG\Schema(
     *         @SWG\Items(
     *           type="object",
     *           @SWG\Property(property="id", type="integer"),
     *         ),
     *      ),
     *   ),
     *   @SWG\Response(
     *     response=403,
     *     description="You are not friend with this user.",
     *     @SWG\Schema(ref="#/definitions/Error")
     *   ),
     *   @SWG\Response(
     *      response=404,
     *      description="The specified user does not exist.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function getFriend($user_id)
    {
        $auth_user_id = Auth::id();

        if(User::where('id',$user_id)->exists())
        {
            if(Friendship::where('status', 1)->where('user_requested', $user_id)->where('requester', $auth_user_id)->exists() || Friendship::where('status', 1)->where('user_requested', $auth_user_id)->where('requester', $user_id)->exists())
            {
                return response()
                ->json(User::where('id', $user_id)->get());

            }

            else
            {
                return response()
                    ->json(["code" => "403", "description" => "Forbidden.", 'message' => 'You are not friend with this user.'], 403)
                    ->header('Content-Type', 'application/json');
            }
        }

        else
        {
            return response()
                ->json(["code" => "404", "description" => "Not found.", 'message' => 'The specified user does not exist.'], 404)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/friends/ids",
     *   summary="Return a list of a friend's ID.",
     *   tags={"friends"},
     *   operationId="friends_ids",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *
     *      @SWG\Schema(
     *      type="array",
     *         @SWG\Items(
     *           @SWG\Property(property="user_id", type="integer"),
     *         ),
     *      ),
     *   ),
     *    @SWG\Response(
     *         response=204,
     *         description="Successful operation.",
     *         @SWG\Schema(ref="#/definitions/Ticket")
     *     ),
     *  ),
     * )
     */
    public function getFriendIDs()
    {
        $auth_user_id = Auth::id();

        $f1 = Friendship::where('status', 1)->where('requester', $auth_user_id)->get();
        $f2 = Friendship::where('status', 1)->where('user_requested', $auth_user_id)->get();
        $friends_ids = array();


        if($f1->count() != 0  || $f2->count() != 0)
        {
            foreach ($f1 as $friendship)
            {
                $friends_ids[] = array('user_id' => $friendship->user_requested);
            }

            foreach ($f2 as $friendship)
            {
                $friends_ids[] = array('user_id' => $friendship->requester);
            }

            return response()->json($friends_ids, 200);
        }

        else
        {
            return response('You have no friends.', 204);
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/isFriend/{user_id}",
     *   summary="Return if a user is a friend or not.",
     *   tags={"friends"},
     *   operationId="is_friends_with",
     *
     *   @SWG\Response(
     *     response=200,
     *     description="Successful operation.",
     *      @SWG\Items(
     *        type="object",
     *        @SWG\Property(property="is_friend", type="boolean"),
     *      ),
     *   ),
     *
     *   @SWG\Response(
     *     response=404,
     *     description="Specified user not found.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function isFriend($user_id)
    {
        if(User::where('id',$user_id)->exists())
        {
            $friendArray = array();

            $array = $this->getFriendIDs();

            if($array->getStatusCode() != 204)
            {
                $array = $array->getData();
                foreach ($array as $item)
                {
                    foreach ($item as $value)
                    {
                        array_push($friendArray, $value);
                    }
                }
            }


            if (in_array($user_id, $friendArray))
            {
                return response()->json(['is_friend' => true], 200)
                                 ->header('Content-Type', 'application/json');
            }

            else
            {
                return response()->json(['is_friend' => false], 200)
                                 ->header('Content-Type', 'application/json');
            }
        }

        else
        {
            return response()->json(["code" => "404", "description" => "Not Found.", 'message' => 'Specified user not found.'], 404)
                             ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/friendships/pending/userIDs",
     *   summary="Return a list of id of the pending friends of a user.",
     *   tags={"friendships"},
     *   operationId="pending_friend_request_ids",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *
     *      @SWG\Schema(
     *         type="array",
     *          @SWG\Items(
     *            @SWG\Property(property="id", type="integer"),
     *          ),
     *      ),
     *   ),
     * )
     */
    public function getPendingFriendRequestIDs()
    {
        $pendingFriendRequest = $this->getPendingFriendRequestsReceived();
        $ids = array();

        if($pendingFriendRequest->getStatusCode() != 204)
        {
            $users = $pendingFriendRequest->getData();

            if(!(empty($users)))
            {
                foreach ($users as $user)
                {
                    $ids[] = array('user_id' => $user->id);
                }

                return response()->json($ids, 200);
            }
        }

        else
        {
            return response('You have no pending friendship request.', 204);
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/friendships/pending/from/{target_user_id}",
     *   summary="Return if the authenticated user has a pending request from a target user.",
     *   tags={"friendships"},
     *   operationId="has_pending_friend_request_from",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *     @SWG\Items(
     *       type="object",
     *       @SWG\Property(property="has_pending_friend_request", type="boolean"),
     *     ),
     *   ),
     *
     *   @SWG\Response(
     *     response=404,
     *     description="Specified user not found.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function hasPendingFriendRequestFrom($user_id)
    {
        if(User::where('id',$user_id)->exists())
        {
            $array = $this->getPendingFriendRequestsReceived();

            $friendRequestsReceived = array();

            if($array->getStatusCode() != 204)
            {
                $array = $array->getData();

                foreach ($array as $item)
                {
                    foreach ($item as $value)
                    {
                        array_push($friendRequestsReceived, $value);
                    }
                }
            }



            if (in_array($user_id, $friendRequestsReceived))
            {
                return response()->json(["has_pending_friend_request" => true], 200)
                    ->header('Content-Type', 'application/json');
            }

            else
            {
                return response()->json(["has_pending_friend_request" => false], 200)
                    ->header('Content-Type', 'application/json');
            }
        }

        else
        {
            return response()->json(["code" => "404", "description" => "Not found.", 'message' => 'Specified user not found.'], 404)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/friendships/pending/sent/{target_user_id}",
     *   summary="Return if authenticated user has a pending request sent to target user.",
     *   tags={"friendships"},
     *   operationId="has_pending_friend_request_sent_to",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *     @SWG\Items(
     *       type="object",
     *       @SWG\Property(property="has_pending_friend_request", type="boolean"),
     *     ),
     *   ),
     *
     *   @SWG\Response(
     *     response=404,
     *     description="Specified user not found.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function hasPendingFriendRequestSent($user_id)
    {
        if(User::where('id',$user_id)->exists())
        {
            $array = $this->getPendingFriendRequestSent();

            $friendRequestsSent = array();
            if($array->getStatusCode() != 204)
            {
                $array = $array->getData();
                foreach ($array as $item)
                {
                    foreach ($item as $value)
                    {
                        array_push($friendRequestsSent, $value);
                    }
                }
            }

            if(in_array($user_id, $friendRequestsSent))
            {
                return response()->json(["has_pending_friend_request" => true], 200)
                    ->header('Content-Type', 'application/json');
            }

            else
            {
                return response()->json(["has_pending_friend_request" => false], 200)
                    ->header('Content-Type', 'application/json');
            }
        }

        else
        {
            return response()->json(["code" => "404", "description" => "Not found.", 'message' => 'Specified user not found.'], 404)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Delete(
     *   path="/user/friend/{friend_id}",
     *   summary="Remove an existing friendship between authenticated user and target user.",
     *   tags={"friends"},
     *   operationId="remove_friendship",
     *   @SWG\Response(
     *         response=200,
     *         description="Request successful. User has been removed from friend list.",
     *     ),
     *   @SWG\Response(
     *     response=404,
     *     description="Friend or friendship not found.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function deleteFriendship($user_to_unfriend)
    {
        $auth_user_id = Auth::id();

        $friendship = null;

        if(User::where('id',$user_to_unfriend)->exists())
        {
            $friendship = Friendship::
                where(function ($query) use ($auth_user_id, $user_to_unfriend)
                    {
                        $query->where('requester', $auth_user_id)
                            ->where('user_requested', $user_to_unfriend);
                    })
                ->orWhere(function ($query) use ($auth_user_id, $user_to_unfriend)
                    {
                        $query->where('requester', $user_to_unfriend)
                            ->where('user_requested', $auth_user_id);
                    });

            if ($friendship != null)
            {
                $friendship->delete();

                return response(["code" => "200", "description" => "OK", 'message' => 'Request successful. User has been removed from friend list.'], 200)
                    ->header('Content-Type', 'application/json');
            }

            else if ($friendship == null)
            {
                return response(["code" => "404", "description" => "Not found.", 'message' => 'Friendship not found.'], 404)
                    ->header('Content-Type', 'application/json');
            }
        }

        else
        {
            return response(["code" => "404", "description" => "Not found.", 'message' => 'Friend to delete not found.'], 404)
                ->header('Content-Type', 'application/json');
        }
    }


    /**
     * @SWG\Get(
     *   path="/user/friendships/pending/ids/sent",
     *   summary="Return a list of id of the pending friendship requests sent by the authenticated user.",
     *   tags={"friendships"},
     *   operationId="pending_friend_request_ids",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *
     *      @SWG\Schema(
     *         type="array",
     *          @SWG\Items(
     *            @SWG\Property(property="id", type="integer"),
     *          ),
     *      ),
     *   ),
     * )
     */
    public function getPendingFriendRequestsSentIDs()
    {
        $users = $this->getPendingFriendRequestSent();
        $ids = array();

        if($users->getStatusCode() != 204)
        {
            $users = $users->getData();

            if(!(empty($users)))
            {
                foreach ($users as $user)
                {
                    $ids[] = array('user_id' => $user->id);
                }

                return response()->json($ids, 200);
            }
        }

        else
        {
            return response('You have no pending friendship request.', 204);
        }
    }

    public static function getNumberOfPendingRequests()
    {
        $auth_user_id = Auth::id();

        $pendingFriendsRequests = Friendship::where('status', 0)->where('user_requested', $auth_user_id)->get();

        return $pendingFriendsRequests->count();
    }
}