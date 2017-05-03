<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiOAuthController extends Controller
{
    public function index()
    {
        return view('api');
    }

    /**
     * @SWG\Get(
     *   path="/oauth/authorize",
     *   summary="Get a federation authorization.",
     *   tags={"authentication"},
     *   operationId="none",
     *   @SWG\Parameter(
     *     name="client_id",
     *     in="path",
     *     description="User ID of the federation authentication.",
     *     required=true,
     *     type="string",
     *     format="utf8"
     *   ),
     *    @SWG\Parameter(
     *     name="redirect_uri",
     *     in="path",
     *     description="The redirect URI after authorization.",
     *     required=true,
     *     type="string",
     *     format="utf8"
     *   ),
     *   @SWG\Parameter(
     *     name="response_type",
     *     in="path",
     *     description="The response type of the authorization. Here: 'code'.",
     *     required=true,
     *     type="string",
     *     format="utf8"
     *   ),
     *   @SWG\Parameter(
     *     name="scope",
     *     in="path",
     *     description="The scope of the scope.",
     *     required=true,
     *     type="string",
     *     format="utf8"
     *   ),
     *
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *          @SWG\Schema(ref="#/definitions/Authorization_Successful")
     *     ),
     *   @SWG\Response(response=406, description="Not acceptable."),
     *   @SWG\Response(response=500, description="Internal server error.")
     * )
     */

    /**
     * @SWG\Post(
     *   path="/oauth/token",
     *   summary="Get a user token via authentication.",
     *   tags={"authentication"},
     *   operationId="none",

     *   @SWG\Parameter(
     *     name="oauth_token_request",
     *     in="body",
     *     description="An OAuth token request.",
     *     required=true,
     *     @SWG\Schema(
     *       ref="#/definitions/token_request_form",
     *     ),
     *   ),
     *
     *   @SWG\Response(
     *         response=200,
     *         description="Successful operation.",
     *          @SWG\Schema(ref="#/definitions/Authorization_Successful")
     *     ),
     *   @SWG\Response(response=400, description="Bad Request."),
     *   @SWG\Response(response=401, description="Unauthorized.")
     * )
     */
}
