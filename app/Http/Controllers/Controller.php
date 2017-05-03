<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Swagger\Annotations as SWG;


class Controller extends BaseController
{
    /**
     * @SWG\Swagger(
     *     @SWG\Info(
     *         version="1.0.0",
     *         title="Social Network API",
     *         description="The public API from the Social Network team.",
     *         @SWG\License(name="MIT"),
     *         @SWG\Contact(name="the GTI525 Social Network Team."),
     *     ),
     *     host="https://gti525-social-network.herokuapp.com/",
     *     basePath="/api/v1",
     *     schemes={"http"},
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     @SWG\Definition(
     *         definition="Error",
     *         required={"code", "message"},
     *         @SWG\Property(
     *             property="code",
     *             type="integer",
     *             format="int32"
     *         ),
     *          @SWG\Property(
     *             property="description",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="message",
     *             type="string"
     *         )
     *     ),
     *     @SWG\Definition(
     *         definition="Authorization_Successful",
     *         required={"access_token", "expires_in", "refresh_token", "token_type"},
     *         @SWG\Property(
     *             property="access_token",
     *             type="string",
     *         ),
     *          @SWG\Property(
     *             property="expires_in",
     *             type="integer",
     *             format="int32"
     *         ),
     *         @SWG\Property(
     *             property="refresh_token",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="token_type",
     *             type="string"
     *         )
     *     ),
     *     @SWG\Definition(
     *         definition="Friendship_form",
     *         required={"requester", "user_requested"},
     *
     *          @SWG\Property(
     *             property="requester",
     *             type="integer"
     *         ),
     *         @SWG\Property(
     *             property="user_requested",
     *             type="integer"
     *         )
     *     ),
     *
     *       @SWG\Definition(
     *         definition="Token_request_form",
     *         required={"grant_type", "client_id", "client_secret", "username", "password"},
     *
     *          @SWG\Property(
     *             property="grant_type",
     *             type="string",
     *             format="utf8",
     *             example="password"
     *         ),
     *         @SWG\Property(
     *             property="client_id",
     *             type="integer",
     *             format="int32",
     *             example="32"
     *         ),
     *          @SWG\Property(
     *             property="client_secret",
     *             type="string",
     *             format="utf8"
     *         ),
     *          @SWG\Property(
     *             property="username",
     *             type="string",
     *             format="utf8",
     *             example="john.appleseed@apple.com"
     *         ),
     *           @SWG\Property(
     *             property="password",
     *             type="string",
     *             format="utf8"
     *         ),
     *           @SWG\Property(
     *             property="scope",
     *             type="string",
     *             format="utf8"
     *         ),
     *     ),
     *
     *     @SWG\Definition(
     *         definition="Friendships",
     *         required=
     *         {
     *            "id",
     *            "requester",
     *            "user_requested",
     *            "status",
     *            "created_at",
     *            "updated_at"
     *          },
     *
     *         @SWG\Property(
     *             property="id",
     *             type="integer",
     *         ),
     *         @SWG\Property(
     *             property="requester",
     *             type="integer"
     *         ),
     *        @SWG\Property(
     *             property="user_requested",
     *             type="integer"
     *         ),
     *         @SWG\Property(
     *             property="status",
     *             type="string"
     *         ),
     *        @SWG\Property(
     *             property="url",
     *             type="string"
     *         ),
     *     ),
     *
     *     @SWG\Definition(
     *      definition="Ticket",
     *      required=
     *      {
     *        "id",
     *        "unique_id",
     *        "ownerFirstName",
     *        "ownerLastName",
     *        "user_id",
     *        "event",
     *        "artist",
     *        "price",
     *        "venue",
     *        "city",
     *        "image",
     *        "date",
     *        "description",
     *      },
     *         @SWG\Property(
     *             property="id",
     *             type="integer",
     *             format="int32",
     *         ),
     *          @SWG\Property(
     *             property="unique_id",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="ownerFirstName",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="ownerLastName",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="user_id",
     *             type="integer",
     *             format="int32",
     *         ),
     *         @SWG\Property(
     *             property="event",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="artist",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="price",
     *             type="number",
     *             format="float"
     *         ),
     *         @SWG\Property(
     *             property="venue",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="city",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="date_event",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="image",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="description",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="url",
     *             type="string",
     *         ),
     *
     *     ),
     *
     *     @SWG\Definition(
     *      definition="TicketPost",
     *      required=
     *      {
     *        "id",
     *        "unique_id",
     *        "ownerFirstName",
     *        "ownerLastName",
     *        "user_id",
     *        "event",
     *        "artist",
     *        "price",
     *        "venue",
     *        "city",
     *        "image",
     *        "date",
     *        "description",
     *      },
     *          @SWG\Property(
     *             property="unique_id",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="event",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="artist",
     *             type="string"
     *         ),
     *         @SWG\Property(
     *             property="price",
     *             type="number",
     *             format="float"
     *         ),
     *         @SWG\Property(
     *             property="venue",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="city",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="date_event",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="image",
     *             type="string",
     *         ),
     *         @SWG\Property(
     *             property="description",
     *             type="string",
     *         ),
     *
     *     ),
     * )
     */
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

