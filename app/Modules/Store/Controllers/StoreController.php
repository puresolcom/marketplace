<?php
namespace Awok\Modules\Store\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;

class StoreController extends Controller
{
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
            $createStore = app('store')->create($storeData);
        } catch (\Exception $e) {
            return $this->jsonResponse('', $e->getMessage(), 400);
        }

        return $this->jsonResponse($createStore, 'Store created successfully');
    }
}
