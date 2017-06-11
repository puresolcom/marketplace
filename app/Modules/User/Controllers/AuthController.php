<?php

namespace Awok\Modules\User\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Dusterio\LumenPassport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;

class AuthController extends Controller
{
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

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $serverRequest
     *
     * @return mixed
     */
    public function login(ServerRequestInterface $serverRequest)
    {
        return app(AccessTokenController::class)->issueToken($serverRequest);
    }

    public function protected ()
    {
        return 'Welcome !';
    }
}