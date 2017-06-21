<?php

namespace Awok\Modules\User\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\User\Services\UserService;

class RoleController extends Controller
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
     * @api                     {get}   /user/roles  1. Roles List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                Role
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
            $result = $this->userService->fetchRoles($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api             {get}     /user/:id/roles    5. Get User Roles
     * @apiDescription  Get user roles
     * @apiGroup        User
     *
     * @param $userID
     *
     * @return mixed
     */
    public function getUserRoles($userID)
    {
        try {
            $userRoles = $this->userService->getRoles($userID);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, 400);
        }

        return $this->jsonResponse($userRoles);
    }

    /**
     * @api             {put}     /user/:id/roles    6. Update user roles
     *
     * @apiDescription  update user roles
     * @apiGroup        User
     * @apiParamExample {json} Request-Example-Attach-Detach-Roles:
     * {
     *      "attach": [10,15,35],
     *      "detach": [5]
     * }
     *
     * @apiParamExample {json} Request-Example-Sync-Roles:
     * {
     *      "sync": [10,15,35]
     * }
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $userID
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $userID)
    {
        try {
            $this->userService->setRoles($userID, $request->expect(['sync', 'attach', 'detach']));
        } catch
        (\Exception $e) {
            return $this->jsonResponse(null, $e, 400);
        }

        return $this->jsonResponse(null, 'Roles updated successfully');
    }
}