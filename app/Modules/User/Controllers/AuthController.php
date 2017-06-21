<?php

namespace Awok\Modules\User\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\User\Services\UserService;
use GuzzleHttp\Exception\ClientException;

class AuthController extends Controller
{
    /**
     * @var UserService
     */
    protected $user;

    public function __construct()
    {
        $this->user = app('user');
    }

    /**
     * @api             {post}     /user/auth/login    1. Login
     * @apiDescription  Log a user into the  system and return OAuth 2 Tokens
     * @apiGroup        Authentication
     * @apiParam        {String}   username            Username/Email
     * @apiParam        {String}   Password            Password
     * @apiParamExample {json} Request-Example:
     * {
     *  "username" : "awesomeuser@exmaple.com",
     *  "password" : "p@ssw0rd"
     * }
     *
     * @param Request $request ;
     *
     * @return mixed
     */
    public function login(Request $request)
    {
        $loginFields      = ['username', 'password'];
        $loginCredentials = $request->only($loginFields);

        $validator = $this->validate($request, [
            'username' => 'required',
            'password' => 'required|min:6|max:32',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $response = $this->user->login($loginCredentials);
        } catch (ClientException $e) {
            $response = $e->getResponse();

            return $this->jsonResponse($response->getBody()->getContents(), $e->getMessage(), $response->getStatusCode());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(json_decode($response->getBody()->getContents()), $response->getStatusCode());
    }

    /**
     * @api             {post}  /user/auth/register   2. Register
     * @apiDescription  Registers a new user into the marketplace
     * @apiGroup        Authentication
     * @apiParam        {String}   name                First name
     * @apiParam        {String}   email               E-mail Address
     * @apiParam        {Number}   phone_primary       Primary Phone number
     * @apiParam        {Number}   [phone_secondary]   Secondary Phone number
     * @apiParam        {String}   password            Password
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
            $registerUser = $this->user->register($registrationData);
        } catch (\Exception $e) {
            return $this->jsonResponse('', $e->getMessage(), 400);
        }

        return $this->jsonResponse($registerUser, 'User Registered Successfully');
    }
}