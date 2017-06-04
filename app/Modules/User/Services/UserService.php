<?php

namespace Awok\Modules\User\Services;

use Awok\Modules\User\Models\User;

/**
 * Class UserService
 *
 * @package Awok\Modules\User\Services
 */
class UserService
{
    /**
     * @var \Awok\Modules\User\Models\User
     */
    protected $userModel;

    public function __construct(User $user)
    {
        $this->userModel = $user;
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
        return $this->userModel->create($userData);
    }
}