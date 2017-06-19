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
     * @api             {POST}                 /user/user      3. Create User
     * @apiDescription  Create a new currency
     * @apiGroup        User
     * @apiParam       {String}             name                    Name of the User
     * @apiParam       {String}             slug                    User code name
     * @apiParam       {String}             type                    User type (city, area ..)
     * @apiParam       {Number}             [parent_id]             Parent User ID
     * @apiParam       {Number}             country_id              User Country ID
     * @apiParamExample {json} Request-Example:
     * {
     *  "name"          : "Dubai",
     *  "slug"          : "ae-du",
     *  "type"          : "city",
     *  "parent_id"     : 10,
     *  "country_id"    : 1
     * }
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $expectedFields = ['name', 'slug', 'type', 'parent_id', 'country_id'];
        $currencyData   = $request->expected($expectedFields);

        $validator = $this->validate($request, [
            'name'       => 'required',
            'slug'       => 'required|alpha_dash|unique:users',
            'type'       => ['required', Rule::in(['city', 'area'])],
            'parent_id'  => 'nullable|numeric|exists:users,id',
            'country_id' => 'nullable|required_if:type,city|numeric|exists:countries,id',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        $created = $this->user->create($currencyData);

        if (! $created) {
            return $this->jsonResponse(null, 'Unable to add user', 400);
        }

        return $this->jsonResponse($created, 'User added successfully');
    }

    /**
     * @api            {PUT}                 /user/:id            4. Update user
     * @apiDescription Update user information
     * @apiGroup       User
     * @apiParam       {String}             [name]                    Name of the User
     * @apiParam       {Number}             [parent_id]               Parent User ID
     * @apiParamExample {json} Request-Example:
     * {
     *  "name"          : "Dubai",
     *  "parent_id"     : 10
     * }
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expectedFields = ['name', 'parent_id'];
        $userData       = $request->expected($expectedFields);

        $validator = $this->validate($request, [
            'country_id' => 'nullable|numeric|exists:countries,id',
            'parent_id'  => 'nullable|numeric|exists:users,id',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $updated = $this->user->update($id, $userData);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        if (! $updated) {
            return $this->jsonResponse(null, 'Unable to update user', 400);
        }

        return $this->jsonResponse($updated, 'user updated successfully');
    }
}