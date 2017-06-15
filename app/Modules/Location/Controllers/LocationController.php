<?php
namespace Awok\Modules\Location\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Location\Services\LocationService;
use Illuminate\Validation\Rule;

/**
 * Class LocationController
 *
 * @package Awok\Modules\Location\Controllers
 */
class LocationController extends Controller
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
     * @api                     {get}   /location/:id   1. Get Location
     * @apiDescription          Finds a specific object using the provided :id segment
     * @apiGroup                Location
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
            $result = $this->location->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Object not found', 400);
    }

    /**
     * @api                     {get}   /location  2. Locations List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                Location
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
            $result = $this->location->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api             {POST}                 /location/location      3. Create Location
     * @apiDescription  Create a new currency
     * @apiGroup        Location
     * @apiParam       {String}             name                    Name of the Location
     * @apiParam       {String}             slug                    Location code name
     * @apiParam       {String}             type                    Location type (city, area ..)
     * @apiParam       {Number}             [parent_id]             Parent Location ID
     * @apiParam       {Number}             country_id              Location Country ID
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
        $currencyData   = array_filter($request->only($expectedFields));

        $validator = $this->validate($request, [
            'name'       => 'required',
            'slug'       => 'required|alpha_dash|unique:locations',
            'type'       => ['required', Rule::in(['city', 'area'])],
            'parent_id'  => 'numeric|exists:locations,id',
            'country_id' => 'required_if:type,city|numeric|exists:countries,id',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        $created = $this->location->create($currencyData);

        if (! $created) {
            return $this->jsonResponse(null, 'Unable to add location', 400);
        }

        return $this->jsonResponse($created, 'Location added successfully');
    }

    /**
     * @api            {PUT}                 /location/:id            4. Update location
     * @apiDescription Update location information
     * @apiGroup       Location
     * @apiParam       {String}             [name]                    Name of the Location
     * @apiParam       {Number}             [parent_id]               Parent Location ID
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
        $locationData   = array_filter($request->only($expectedFields));

        $validator = $this->validate($request, [
            'parent_id' => 'numeric|exists:locations,id',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $updated = $this->location->update($id, $locationData);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        if (! $updated) {
            return $this->jsonResponse(null, 'Unable to update location', 400);
        }

        return $this->jsonResponse($updated, 'location updated successfully');
    }

    /**
     * @api             {DELETE}    /location/:id   5. Delete location
     * @apiDescription  Hard delete a location
     * @apiGroup        Location
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $updated = $this->location->delete($id);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        if (! $updated) {
            return $this->jsonResponse(null, 'Unable to delete location', 400);
        }

        return $this->jsonResponse($updated, 'Location deleted successfully');
    }
}
