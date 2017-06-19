<?php

namespace Awok\Modules\User\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Option\Services\OptionService;
use Awok\Modules\User\Models\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class UserService
 *
 * @package Awok\Modules\User\Services
 */
class UserService extends BaseService
{
    /**
     * @var OptionService;
     */
    protected $option;

    public function __construct()
    {
        $this->setBaseModel(User::class);
        $this->option = app('option');
    }

    /**
     * @param array $loginCredentials
     *
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \Exception, ClientException
     */
    public function login(array $loginCredentials)
    {
        $user = $this->findWhere(['email' => $loginCredentials['username']], ['id', 'email', 'password'])->first();
        if (! $user || ! app('hash')->check($loginCredentials['password'], $user->password)) {
            throw new \Exception('Invalid Login Credentials', 400);
        }

        $clientID          = $this->option->get('auth', 'oauth_client_id');
        $clientSecret      = $this->option->get('auth', 'oauth_client_secret');
        $oauthProvisionKey = $this->option->get('auth', 'oauth_provision_key');

        $http = new Client();

        /**
         * @throws ClientException
         */
        $response = $http->request('POST', config('app.gateway_url').'/oauth2/token', [
            'verify'      => false,
            'curl'        => [
                CURLOPT_SSLVERSION     => 1,
                CURLOPT_SSL_VERIFYPEER => false,
            ],
            'form_params' => [
                'client_id'            => $clientID,
                'client_secret'        => $clientSecret,
                'provision_key'        => $oauthProvisionKey,
                'grant_type'           => 'password',
                'authenticated_userid' => $user->id,
                'username'             => $loginCredentials['username'] ?? null,
                "password"             => $loginCredentials['password'] ?? null,
            ],
        ]);

        return $response;
    }

    /**
     * @param $userData
     *
     * @return mixed
     */
    public function register(array $userData)
    {
        // Appends custom fields
        $userData['password'] = app('hash')->make($userData['password']);
        $userData['active']   = false;
        $userData['approved'] = false;

        return $this->create($userData);
    }

    /**
     * @param $userData
     *
     * @return mixed
     */
    public function create(array $userData)
    {
        return $this->getBaseModel()->create($userData);
    }
}