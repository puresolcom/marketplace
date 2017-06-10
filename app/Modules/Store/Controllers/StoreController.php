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
    protected $store;

    public function __construct()
    {
        $this->store = app('store');
    }

    /**
     * Registers a new store
     *
     * @route /store [POST]
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
        $storeData      = $request->only($expectedFields);

        $this->validate($request, [
            'name'             => 'required|string',
            'slug'             => 'required|slug:stores',
            'street_address_1' => 'required|string',
            'city_id'          => 'required|exists:locations,id',
            'country_id'       => 'required|exists:countries,id',
            'postal_code'      => 'required|min:5|max:12',
        ]);

        try {
            $createStore = $this->store->create($storeData);
        } catch (\Exception $e) {
            return $this->jsonResponse('', $e->getMessage(), 400);
        }

        return $this->jsonResponse($createStore, 'Store created successfully');
    }

    /**
     * Get single store
     *
     * @route /store/{id} [GET]
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, $id)
    {
        try {
            $result = $this->store->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Store not found', 400);
    }
}
