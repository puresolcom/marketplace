<?php

namespace Awok\Modules\User\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Option\Services\OptionService;
use Awok\Modules\User\Models\Role;
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

    /**
     * @var \Awok\Modules\User\Models\Role
     */
    protected $roleModel;

    public function __construct(Role $roleModel)
    {
        $this->roleModel = $roleModel;
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
        $user = $this->findWhere(['email' => $loginCredentials['username']], [
            'id',
            'name',
            'email',
            'password',
            'phone_primary',
            'phone_secondary',
            'active',
            'approved',
            'created_at',
        ])->first();

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

        return array_merge(json_decode($response->getBody()->getContents(), true), ['user' => $user->toArray()]);
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
     * Create new user
     *
     * @param $userData
     *
     * @return mixed
     */
    public function create(array $userData)
    {
        \DB::beginTransaction();
        $created = $this->getBaseModel()->create($userData);

        if ($created) {
            $sellerRole = $this->roleModel->where('role', '=', 'seller')->first();

            if (! $sellerRole) {
                throw new \Exception('Seller role cannot be found');
            }

            $this->attachRole($created->id, [$sellerRole->id]);
        }

        \DB::commit();

        return $created;
    }

    /**
     * Update current user
     *
     * @param       $id
     * @param array $userData
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, array $userData)
    {
        $user = $this->getBaseModel()->find($id);

        if (array_has($userData, 'password')) {
            $userData['password'] = app('hash')->make($userData['password']);
        }

        if (array_has($userData, 'approved')) {
            $userData['approved_by'] = app('auth')->user()->id;
        }

        if (! $user) {
            throw new \Exception('Unable to find user', 400);
        }

        return $user->update($userData);
    }

    /**
     * Query against model
     *
     * @param null   $fields
     * @param null   $filters
     * @param null   $sort
     * @param null   $relations
     * @param null   $limit
     * @param string $dataKey
     *
     * @return mixed
     */
    public function fetchRoles(
        $fields = null,
        $filters = null,
        $sort = null,
        $relations = null,
        $limit = null,
        $dataKey = null
    ) {

        $result = $this->makeModel(Role::class)->restQueryBuilder($fields, $filters, $sort, $relations, $limit, $dataKey);
        $this->resetModel();

        return $result;
    }

    /**
     * Get user roles
     *
     * @param $userID int
     *
     * @throws \Exception
     * @return array|false
     */
    public function getRoles($userID)
    {
        $user = $this->get($userID, null, null);

        if (! $user) {
            throw new \Exception('User cannot be found');
        }

        return $user->roles;
    }

    /**
     * Set user roles
     *
     * @param       $userID
     * @param array $roles
     *
     * @return bool
     */
    public function setRoles($userID, array $roles)
    {
        \DB::beginTransaction();
        if (isset($roles['sync']) && is_array($roles['sync'])) {
            if (! $this->syncRoles($userID, $roles['sync'])) {
                \DB::rollBack();
            }
        } else {
            if (isset($roles['attach']) && is_array($roles['attach'])) {
                if (! $this->attachRole($userID, $roles['attach'])) {
                    \DB::rollBack();
                }
            }

            if (isset($roles['detach']) && is_array($roles['detach'])) {
                if (! $this->detachRole($userID, $roles['detach'])) {
                    \DB::rollBack();
                }
            }
        }

        \DB::commit();

        return true;
    }

    /**
     * Reset current user rules to the passed roles ids only (Will delete other roles)
     *
     * @param       $userID
     * @param array $rolesIDs
     *
     * @throws  \Exception
     * @return bool
     */
    public function syncRoles($userID, array $rolesIDs)
    {
        $user = $this->get($userID, null, null);

        if (! $user) {
            throw new \Exception('User cannot be found');
        }

        return $user->roles()->sync($rolesIDs);
    }

    /**
     * Attach user role/s
     *
     * @param $userID
     * @param $roleIDs
     *
     * @throws  \Exception
     * @return mixed
     */
    public function attachRole($userID, $roleIDs)
    {
        $user = $this->get($userID, null, null);

        if (! $user) {
            throw new \Exception('User cannot be found');
        }

        return $user->roles()->syncWithoutDetaching($roleIDs);
    }

    /**
     * Detach user role/s
     *
     * @param $userID
     * @param $roleIDs
     *
     * @throws  \Exception
     * @return bool
     */
    public function detachRole($userID, $roleIDs)
    {
        $user = $this->get($userID, null, null);

        if (! $user) {
            throw new \Exception('User cannot be found');
        }

        return $user->roles()->detach($roleIDs);
    }
}