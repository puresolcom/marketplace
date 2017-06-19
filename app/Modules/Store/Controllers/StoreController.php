<?php
namespace Awok\Modules\Store\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Store\Services\StoreService;

class StoreController extends Controller
{
    /**
     * @var StoreService;
     */
    protected $storeService;

    public function __construct()
    {
        $this->storeService = app('store');
    }

    /**
     * @api                     {get}   /store/:id   1. Get Store
     * @apiDescription          Finds a specific object using the provided :id segment
     * @apiGroup                Store
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
            $result = $this->storeService->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Store not found', 400);
    }

    /**
     * @api                     {get}   /store      2. Stores List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                Store
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
            $result = $this->storeService->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api             {post}      /store  3. Create Store
     * @apiGroup        Store
     * @apiParam        {String}    name                Store name
     * @apiParam        {String}    slug                Store Slug (Sub-domain)
     * @apiParam        {String}    street_address_1    Store Physical Address 1
     * @apiParam        {String}    [street_address_2]  Store Physical Address 2
     * @apiParam        {Int}       city_id             Store City
     * @apiParam        {Int}       [country_id]        Country for the store (will be detected automatically from the
     * @apiParam        {Int}       [user_id]           Store owner user ID
     * @apiParam        {Int}       [postal_code]       Store Postal code
     * @apiParamExample {json}      Request-Example
     *{
     *      "name": "Almaya store",
     *      "slug": "almaya",
     *      "street_address_1": "G floor, Lake point tower, Cluster N, JLT",
     *      "street_address_2": "G floor, Lake point tower, Cluster Z, JLT",
     *      "city_id": 1,
     *      "country_id": 1,
     *      "postal_code": "12345"
     * }
     * @route           /store [POST]
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $expectedFields = [
            'name',
            'slug',
            'street_address_1',
            'street_address_2',
            'country_id',
            'city_id',
            'postal_code',
            'user_id',
        ];
        $storeData      = $request->expect($expectedFields);

        $validator = $this->validate($request, [
            'name'             => 'required|string',
            'slug'             => 'required|slug:stores',
            'street_address_1' => 'required|string',
            'street_address_2' => 'string',
            'city_id'          => 'required|exists:locations,id',
            'country_id'       => 'exists:countries,id',
            'user_id'          => 'exists:users,id',
            'postal_code'      => 'min:5|max:12',

        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $createStore = $this->storeService->create($storeData);
        } catch (\Exception $e) {
            return $this->jsonResponse('', $e->getMessage(), 400);
        }

        return $this->jsonResponse($createStore, 'Store created successfully');
    }

    /**
     * @api             {post}      /store  4. Update Store
     * @apiGroup        Store
     * @apiParam        {String}    [name]                Store name
     * @apiParam        {String}    [street_address_1]    Store Physical Address 1
     * @apiParam        {String}    [street_address_2]    Store Physical Address 2
     * @apiParam        {Int}       [city_id]             Store City
     * @apiParam        {Int}       [country_id]          Country for the store (will be detected automatically from the
     * @apiParam        {Int}       [postal_code]         Store Postal code
     * @apiParamExample {json}      Request-Example
     *{
     *      "name": "Almaya store",
     *      "street_address_1": "G floor, Lake point tower, Cluster N, JLT",
     *      "street_address_2": "G floor, Lake point tower, Cluster Z, JLT",
     *      "city_id": 1,
     *      "country_id": 1,
     *      "postal_code": "12345"
     * }
     * @route           /store [PUT]
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expectedFields = [
            'name',
            'street_address_1',
            'street_address_2',
            'city_id',
            'country_id',
            'postal_code',
        ];
        $storeData      = $request->expect($expectedFields);

        $validator = $this->validate($request, [
            'name'             => 'string',
            'street_address_1' => 'string',
            'street_address_2' => 'string',
            'city_id'          => 'exists:locations,id',
            'country_id'       => 'exists:countries,id',
            'postal_code'      => 'min:5|max:12',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $createStore = $this->storeService->update($id, $storeData);
        } catch (\Exception $e) {
            return $this->jsonResponse('', $e->getMessage(), 400);
        }

        return $this->jsonResponse($createStore, 'Store Updated successfully');
    }

    /**
     * @api             {DELETE}    /store/:id   5. Delete store
     * @apiDescription  Soft delete a store
     * @apiGroup        Store
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $delete = $this->storeService->delete($id);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        if (! $delete) {
            return $this->jsonResponse(null, 'Unable to delete store', 400);
        }

        return $this->jsonResponse($delete, 'Store deleted successfully');
    }
}
