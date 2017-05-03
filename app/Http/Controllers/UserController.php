<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Image;
use File;

class UserController extends Controller
{
    /**
     * Default index method.
     */
    public function index()
    {

    }

    /**
     * @SWG\Get(
     *   path="/users",
     *   summary="Show all users present in the social network database.",
     *   tags={"users"},
     *   operationId="getUsers",
     *   produces={"application/json"},
     *
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/User")
     *         ),
     *     ),
     *   @SWG\Response(response=500, description="Internal server error.")
     * )
     */
    public function getUsers()
    {

        return response(User::all()->toJson(), 200)
            ->header('Content-Type', 'application/json');

    }

    /**
     * @SWG\Get(
     *   path="/user/{user_ID}",
     *   summary="Get the information about a specific user.",
     *   tags={"users"},
     *   operationId="getUser",
     *   @SWG\Parameter(
     *     name="user_ID",
     *     in="path",
     *     description="Target user.",
     *     required=true,
     *     type="integer",
     *     format="int32"
     *   ),
     *
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *         @SWG\Schema(ref="#/definitions/User")
     *
     *     ),
     *   @SWG\Response(response=406, description="Not acceptable."),
     *   @SWG\Response(response=500, description="Internal server error.")
     * )
     */
    public function getUser($id)
    {
        return response(User::where('id', $id)->get()->toJson(), 200)
            ->header('Content-Type', 'application/json');

    }


    public function getToken($username, $password)
    {
        $user = User::where('email', $username)->where('password', $password)->get();

        if($user != null)
        {
            $token = $user->createToken('Personal Token')->accessToken;

            return $token;
        }

        else
        {
            return response()
                ->json(["code" => "404", "description" => "Bad request.", "message" => 'User not found.'], 404)
                ->header('Content-Type', 'application/json');
        }
    }
}