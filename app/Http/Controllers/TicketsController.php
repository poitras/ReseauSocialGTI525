<?php

namespace App\Http\Controllers;

use App\Friendship;
use App\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;

class TicketsController extends Controller
{
    /**
     * Default index method.
     */
    public function index()
    {

    }

    /**
     * @SWG\Get(
     *   path="/user/tickets",
     *   summary="List all tickets of an authenticated user.",
     *   tags={"tickets"},
     *   operationId="getTickets",
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *         @SWG\Schema(ref="#/definitions/Ticket")
     *     ),
     * )
     */
    public function getTickets()
    {
        $auth_user_id = Auth::id();

        $tickets = Ticket::where('user_id', $auth_user_id)->get();

        if(!($tickets->isEmpty()))
        {
            return response()->json($tickets, 200)
                ->header('Content-Type', 'application/json');
        }
        else
        {
            return response()
                ->json(["code" => "204", "description" => "No content", "message" => 'This user has no ticket'], 204)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/ticket/{ticket_ID}",
     *   summary="Show a specific ticket belonging to a specific authenticated user.",
     *   tags={"tickets"},
     *   operationId="getTicket",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="ticket_ID",
     *     in="path",
     *     description="Target ticket.",
     *     required=true,
     *     type="integer",
     *     format="int32"
     *   ),
     *
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *         @SWG\Schema(ref="#/definitions/Ticket")
     *     ),
     *   @SWG\Response(
     *     response=404,
     *     description="Ticket not found.",
     *   @SWG\Schema(ref="#/definitions/Error")
     * )
     * )
     */
    public function getTicket($ticket_id)
    {
        $auth_user_id = Auth::id();

        if (Ticket::where('user_id', $auth_user_id)->where('ticket_id', $ticket_id)->exists())
        {
            return response(Ticket::where('user_id', $auth_user_id)->where('ticket_id', $ticket_id)->get()->toJson(), 200)
                ->header('Content-Type', 'application/json');
        }

        else
        {
            return response()
                ->json(['code'=>'404', "description" => "Bad request.", "message" => 'Ticket not found.'], 404)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Post(
     *   path="/user/tickets",
     *   summary="Add one or multiple tickets to authenticated user.",
     *   tags={"tickets"},
     *   operationId="addTicket",
     *
     *      @SWG\Parameter(
     *         name="ticket",
     *         in="body",
     *         description="A ticket to add to a user.",
     *         required=true,
     *         @SWG\Schema(ref="#/definitions/TicketPost"),
     *     ),
     *   @SWG\Response(
     *         response=201,
     *         description="Resource created.",
     *         @SWG\Items(ref="#/definitions/Ticket")
     *     ),
     *   @SWG\Response(
     *     response=404,
     *     description="User not found.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   )
     *  )
     *  security={{"social_network_auth":{"write:tickets", "read:tickets"}}}
     * )
     */
    public function addTicket(Request $request)
    {
        $auth_user_id = Auth::id();

        \Log::info('received request', ['request' => $request]);
        
        error_log($request->input('date_event'));

        if(User::where('id',$auth_user_id)->exists())
        {
            $user = User::where('id',$auth_user_id)->first();

            $date = Carbon::createFromFormat('Y-m-d H:i:s', $request->input("date_event"));

            if($request->input('image') == null)
            {
               $request->merge(array('image' => 'defaultTicket.png'));
            }

            $ticket = Ticket::create([
                'user_id'          => $auth_user_id,
                'owner_first_name' => $user->first_name,
                'owner_last_name' => $user->last_name,
                'unique_id'     => $request->input("unique_id"),
                'event'         => $request->input("event"),
                'artist'        => $request->input("artist"),
                'price'         => $request->input("price"),
                'venue'         => $request->input("venue"),
                'city'          => $request->input("city"),
                'date_event'    => $date->toDateTimeString(),
                'description'   => $request->input("description"),
                'image'         => $request->input('image'),
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);

            $url = "/user/ticket/" . (string)($ticket->id);

            $ticket['url'] = $url;

                return response()
                    ->json($ticket, 201)
                    ->header('Content-Type', 'application/json');
            }

        else
        {
            return response()
                ->json(["code" => "404", "description" => "Not found.", "message" => 'User not found.'], 404)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Patch(
     *   path="/user/ticket/{unique_ticket_id}/owner/{new_user_id}",
     *   summary="Change owner of a ticket_id to the selected user_id",
     *   tags={"tickets"},
     *   operationId="transferTicket",
     *   produces={"application/json"},
     *
     *   @SWG\Parameter(
     *     name="unique_ticket_id",
     *     in="path",
     *     description="The unique ID of a ticket.",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Parameter(
     *     name="new_user_id",
     *     in="path",
     *     description="The user ID to which we want to transfer ticket.",
     *     required=true,
     *     type="string",
     *   ),
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Ticket")
     *         ),
     *     ),
     *   @SWG\Response(
     *     response=403,
     *     description="Ticket does not belongs to you. Cannot transfer ticket.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="Ticket or user not found. Cannot transfer ticket.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function transferTicket($ticketUniqueID, $newUserID)
    {
        $auth_user_id = Auth::id();

        $isValidNewUser = null;
        $isValidTicketUniqueID = null;

        $isValidNewUser = User::where('id', $newUserID)->exists();
        $isValidTicketUniqueID = Ticket::where('unique_id', $ticketUniqueID)->exists();
        $isAuthorized = Ticket::where('user_id', $auth_user_id)->where('unique_id', $ticketUniqueID)->exists();

        if($isAuthorized)
        {
            if ($isValidTicketUniqueID == true && $isValidNewUser == true)
            {
                if (!(Ticket::where('user_id', $newUserID)->where('unique_id', $ticketUniqueID)->exists()))
                {
                    $firstName = User::where('id', $newUserID)->value('first_name');
                    $lastName = User::where('id', $newUserID)->value('last_name');
                    $updateTime = Carbon::now();

                    DB::table('tickets')->where('unique_id', $ticketUniqueID)->update(['user_id' => $newUserID]);
                    DB::table('tickets')->where('unique_id', $ticketUniqueID)->update(['owner_first_name' => $firstName]);
                    DB::table('tickets')->where('unique_id', $ticketUniqueID)->update(['owner_last_name' => $lastName]);
                    DB::table('tickets')->where('unique_id', $ticketUniqueID)->update(['updated_at' => $updateTime]);

                    return response(["code" => "200", "description" => "OK", "message" => 'Ticket has been successfully transferred.'], 200)
                        ->header('Content-Type', 'text/plain');
                }

                else
                {
                    return response()
                        ->json(["code" => "400", "description" => "Bad request", "message" => 'Ticket already belongs to user. Cannot transfer ticket.'], 400)
                        ->header('Content-Type', 'application/json');
                }
            }
        }

        else if($isAuthorized == false)
        {
            return response()
                ->json(["code" => "403", "description" => "Forbidden", "message" => "Ticket does not belongs to you. Cannot transfer ticket."], 403)
                ->header('Content-Type', 'application/json');
        }

        else if ($isValidNewUser == true && $isValidTicketUniqueID == false)
        {
            return response()
                ->json(["code" => "400", "description" => "Bad request.", "message" => 'Ticket not found. Cannot transfer ticket.'], 400)
                ->header('Content-Type', 'application/json');
        }

        else if ($isValidNewUser == false && $isValidTicketUniqueID == true)
        {
            return response()
                ->json(["code" => "400", "description" => "Bad request.", "message" => 'User not found. Cannot transfer ticket.'], 400)
                ->header('Content-Type', 'application/json');
        }
    }

    /**
     * @SWG\Get(
     *   path="/user/{friend_ID}/tickets",
     *   summary="Show the list of friend's tickets.",
     *   tags={"tickets"},
     *   operationId="getFriendTicket",
     *   produces={"application/json"},
     *   @SWG\Parameter(
     *     name="friend_ID",
     *     in="path",
     *     description="Target friend.",
     *     required=true,
     *     type="integer",
     *     format="int32"
     *   ),
     *
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *
     *     @SWG\Schema(
     *         type="array",
     *         @SWG\Items(ref="#/definitions/Ticket")
     *       ),
     *     ),
     *
     *   @SWG\Response(
     *     response=403,
     *     description="You are not friend with this person. You cannot retrieve its tickets.",
     *      @SWG\Schema(ref="#/definitions/Error")
     *   ),
     * )
     */
    public function getFriendTickets($friend_id)
    {
        $requester = Auth::id();

        if(Friendship::where('requester', $requester)->where('user_requested', $friend_id)->where('status', 1)->exists() || Friendship::where('requester', $friend_id)->where('user_requested', $requester)->where('status', 1)->exists())
        {
            $tickets = Ticket::where('user_id', $friend_id)->get();
            return response()->json($tickets, 200)
                                ->header('Content-Type', 'application/json');
        }

        else
        {
            return response()
                ->json(["code" => "403", "description" => "Forbidden", "message" => 'You are not friend with this person. You cannot retrieve its tickets.'], 403)
                ->header('Content-Type', 'application/json');
        }
    }

    public function getIncomingFriendTickets($friend_id)
    {
        $requester = Auth::id();

        if(Friendship::where('requester', $requester)->where('user_requested', $friend_id)->where('status', 1)->exists() || Friendship::where('requester', $friend_id)->where('user_requested', $requester)->where('status', 1)->exists())
        {
            $tickets = Ticket::where('user_id', $friend_id)->where('date_event', '>', Carbon::now())->get();
            return response()->json($tickets, 200)
                ->header('Content-Type', 'application/json');
        }

        else
        {
            return response()
                ->json(["code" => "403", "description" => "Forbidden", "message" => 'You are not friend with this person. You cannot retrieve its tickets.'], 403)
                ->header('Content-Type', 'application/json');
        }
    }

    /*
     * NOT AN API ROUTE.
     * This route is to create a PDF ticket and thus enable the user to print it.
     */
    public function pdfTicket(Request $request)
    {
        if(Auth::check())
        {
            $ticketOwnerID = Ticket::where('user_id', Auth::id())->value('user_id');
            if(Auth::id() == $ticketOwnerID)
            {
                $uniqueID = $request->uniqueID;
                $ticket = Ticket::where('unique_id', $uniqueID)->get();

                if($request->has('download'))
                {
                    $pdf = PDF::loadView('/layouts/pdfTicket', compact('ticket'));
                    return $pdf->download('TicketFromSocialNetwork.pdf');
                }

                return view('/layouts/pdfTicket', compact('ticket'));
            }
        }
        else
        {
            return response()
                ->json(["code" => "401", "description" => "Unauthorized", "message" => 'You are not authorized to print this ticket. Please login.'], 401)
                ->header('Content-Type', 'application/json');
        }
    }
}