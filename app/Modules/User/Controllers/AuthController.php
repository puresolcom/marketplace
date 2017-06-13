<?php

namespace Awok\Modules\User\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Dusterio\LumenPassport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
    /**
     * @api            {post}     /user/auth/login    1. Login
     * @apiDescription Log a user into the  system and return OAuth 2 Tokens
     * @apiGroup       Authentication
     * @apiParam {String}   username            Username/Email
     * @apiParam {String}   Password            Password
     * @apiParamExample {json} Request-Example:
     * {
     *  "username" : "awesomeuser@exmaple.com",
     *  "password" : "p@ssw0rd"
     * }
     *
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     *
     * @return mixed
     */
    public function login(ServerRequestInterface $serverRequest)
    {
        return app(AccessTokenController::class)->issueToken($serverRequest);
    }

    /**
     * @api            {post}  /user/auth/register   2. Register
     * @apiDescription Registers a new user into the marketplace
     * @apiGroup       Authentication
     * @apiParam {String}   name                First name
     * @apiParam {String}   email               E-mail Address
     * @apiParam {String}   phone_primary       Primary Phone number
     * @apiParam {String}   [phone_secondary]   Secondary Phone number
     * @apiParam {String}   password            Password
     * @apiParamExample {json} Request-Example:
     * {
     *  "name" : "Mohammed Anwar",
     *  "email": "mohammed@anwar.tld",
     *  "phone_primary": "1234567890",
     *  "phone_secondary": "1234567890",
     *  "password" : "p@ssw0rd"
     * }
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $registrationFields = [
            'name',
            'email',
            'phone_primary',
            'phone_secondary',
            'password',
        ];
        $registrationData   = $request->only($registrationFields);

        $validator = $this->validate($request, [
            'name'          => 'required|string',
            'email'         => 'required|email|unique:users',
            'phone_primary' => 'required|numeric',
            'password'      => 'required|min:6|max:32',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $registerUser = app('user')->register($registrationData);
        } catch (\Exception $e) {
            return $this->jsonResponse('', $e->getMessage(), 400);
        }

        return $this->jsonResponse($registerUser, 'User Registered Successfully');
    }
}