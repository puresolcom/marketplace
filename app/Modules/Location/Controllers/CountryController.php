<?php
namespace Awok\Modules\Location\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Location\Services\LocationService;

/**
 * Class CountryController
 *
 * @package Awok\Modules\Location\Controllers
 */
class CountryController extends Controller
{
    /**
     * @var LocationService;
     */
    protected $location;

    public function __construct()
    {
        $this->location = app('location');
    }

    /**
     * @api                     {get}   /location/country/:id   1. Get Country
     * @apiDescription          Finds a specific object using the provided :id segment
     * @apiGroup                Country
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
            $result = $this->location->getCountry($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Object not found', 400);
    }

    /**
     * @api                     {get}   /location/country  2. Countries List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                Country
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
    public function list(Request $request)
    {
        try {
            $result = $this->location->fetchCountries($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api             {POST}                 /location/country      3. Create Country
     * @apiDescription  Create a new currency
     * @apiGroup        Country
     * @apiParam       {String}             name                    Name of the Country
     * @apiParam       {String}             slug                    Country code name
     * @apiParamExample {json}  Request-Example
     * {
     *  "name" : "Saudi Arabia",
     *  "slug" : "SA"
     * }
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $expectedFields = ['name', 'slug'];
        $currencyData   = $request->expect($expectedFields);

        $validator = $this->validate($request, [
            'name' => 'required',
            'slug' => 'required|alpha_dash|unique:countries',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        $created = $this->location->createCountry($currencyData);

        if (! $created) {
            return $this->jsonResponse(null, 'Unable to add country', 400);
        }

        return $this->jsonResponse($created, 'Country added successfully');
    }

    /**
     * @api            {PUT}                 /location/country/:id            4. Update country
     * @apiDescription Update country information
     * @apiGroup       Country
     * @apiParam       {String}             [name]                    Name of the country
     * @apiParamExample {json}  Request-Example
     * {
     *  "name" : "Saudi Arabia"
     * }
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expectedFields = ['name'];
        $countryData    = $request->expect($expectedFields);

        try {
            $updated = $this->location->updateCountry($id, $countryData);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        if (! $updated) {
            return $this->jsonResponse(null, 'Unable to update country', 400);
        }

        return $this->jsonResponse($updated, 'Country updated successfully');
    }

    /**
     * @api             {DELETE}    /location/country/:id   5. Delete country
     * @apiDescription  Hard delete a country
     * @apiGroup        Country
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $updated = $this->location->deleteCountry($id);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        if (! $updated) {
            return $this->jsonResponse(null, 'Unable to delete country', 400);
        }

        return $this->jsonResponse($updated, 'Country deleted successfully');
    }
}
