<?php
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="api_key",
 *   type="apiKey",
 *   in="header",
 *   name="api_key"
 * )
 */
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="social-network_oauth",
 *   type="oauth2",
 *   authorizationUrl="http://gti525-social-network.herokuapp.com/api/v1/oauth/dialog",
 *   flow="implicit",
 *   scopes={
 *     "read:tickets": "read your tickets",
 *     "write:tickets": "modify tickets in user account"
 *   }
 * )
 */