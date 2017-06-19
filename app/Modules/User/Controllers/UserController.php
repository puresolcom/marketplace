<?php

namespace Awok\Modules\User\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\User\Services\UserService;

class UserController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    public function __construct()
    {
        $this->userService = app('user');
    }

    /**
     * @api                     {get}   /user/:id   1. Get User
     * @apiDescription          Finds a specific object using the provided :id segment
     * @apiGroup                User
     * @apiParam {String}       [fields]             Comma-separated list of required fields
     * @apiParam {String}       [with]               Comma-separated list of object relations
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, $id)
    {
        try {
            $result = $this->userService->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'user not found', 400);
    }

    /**
     * @api                     {get}   /user       2. Users List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                User
     * @apiParam {String}       [fields]             Comma-separated list of required fields
     * @apiParam {String}       [with]               Comma-separated list of object relations
     * @apiParam {String}       [q]                  Comma-separated list of filters
     * @apiParam {String}       [sort]               Comma-separated list of sorting rules
     * @apiParam {Number}       [limit]              Max number of results per response
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        try {
            $result = $this->userService->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api            {PUT}                 /user/:id            3. Update user
     * @apiDescription Update user information
     * @apiGroup       User
     * @apiParam       {String}             [name]                    Full name
     * @apiParam       {Number}             [phone_primary]           Primary phone number
     * @apiParam       {Number}             [phone_secondary]         Secondary phone number
     * @apiParam       {String}             [password]                Account Password
     * @apiParam       {Boolean}            [active]                  Activate account
     * @apiParam       {Boolean}            [approved]                Approve account
     *
     * @apiParamExample {json} Request-Example:
     * {
     *  "name"              : "Mohammed Anwar",
     *  "phone_primary"     : "1234567890",
     *  "phone_secondary"   : "1234567890",
     *  "password"          : "p@ssw0rd",
     *  "active":           : true,
     *  "approved":         : true
     * }
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expectedFields = ['name', 'phone_primary', 'phone_secondary', 'password', 'active', 'approved'];
        $userData       = $request->expect($expectedFields);

        $validator = $this->validate($request, [
            'name'            => 'string',
            'phone_primary'   => 'numeric',
            'phone_secondary' => 'numeric',
            'password'        => 'min:6|max:32',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $updated = $this->userService->update($id, $userData);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        if (! $updated) {
            return $this->jsonResponse(null, 'Unable to update user', 400);
        }

        return $this->jsonResponse($updated, 'user updated successfully');
    }

    /**
     * @api             {DELETE}    /user/:id   4. Delete user
     * @apiDescription  Soft delete a user
     * @apiGroup        User
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $delete = $this->userService->delete($id);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        if (! $delete) {
            return $this->jsonResponse(null, 'Unable to delete user', 400);
        }

        return $this->jsonResponse($delete, 'User deleted successfully');
    }
}