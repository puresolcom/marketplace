<?php
namespace Awok\Modules\Currency\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Currency\Services\CurrencyService;

/**
 * Class CurrencyController
 *
 * @package Awok\Modules\Currency\Controllers
 */
class CurrencyController extends Controller
{
    /**
     * @var CurrencyService
     */
    protected $currency;

    public function __construct()
    {
        $this->currency = app('currency');
    }

    /**
     * @api                     {get}   /currency/:id   1. Get Currency
     * @apiDescription          Finds a specific object using the provided :id segment
     * @apiGroup                Currency
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
            $result = $this->currency->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Object not found', 400);
    }

    /**
     * @api                     {get}   /currency 2. Currencies List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                Currency
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
            $result = $this->currency->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api            {POST}                 /currency      3. Create Currency
     * @apiDescription Create a new currency
     * @apiGroup       Currency
     * @apiParam       {String}             name                    Name of the currency
     * @apiParam       {String}             symbol                  Currency Symbol
     * @apiParam       {String}             conversion_factor       Currency Conversion factor against base currency
     * @apiParamExample {json} Request-Example:
     *{
     *    "name"                : "Egyptian Pound",
     *    "symbol"              : "EGP",
     *    "conversion_factor"   : "0.22"
     *}
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $expectedFields = ['name', 'symbol', 'conversion_factor'];
        $currencyData   = array_filter($request->only($expectedFields));

        $validator = $this->validate($request, [
            'name'              => 'required',
            'symbol'            => 'required',
            'conversion_factor' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        $created = $this->currency->create($currencyData);

        if (! $created) {
            return $this->jsonResponse(null, 'Unable to add currency', 400);
        }

        return $this->jsonResponse($created, 'Currency added successfully');
    }

    /**
     * @api            {PUT}                 /currency/:id            4. Update Currency
     * @apiDescription Update currency information
     * @apiGroup       Currency
     * @apiParam       {String}             [name]                    Name of the currency
     * @apiParam       {String}             [symbol]                  Currency Symbol
     * @apiParam       {String}             [conversion_factor]       Currency Conversion factor against base currency
     * @apiParamExample {json} Request-Example:
     *{
     *    "name"                : "Egyptian Pound",
     *    "symbol"              : "EGP",
     *    "conversion_factor"   : "0.22"
     *}
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expectedFields = ['name', 'symbol', 'conversion_factor', 'active'];
        $currencyData   = array_filter($request->only($expectedFields));

        try {
            $updated = $this->currency->update($id, $currencyData);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        if (! $updated) {
            return $this->jsonResponse(null, 'Unable to update currency', 400);
        }

        return $this->jsonResponse($updated, 'Currency updated successfully');
    }

    /**
     * @api             {DELETE}    /currency/:id   5. Delete currency
     * @apiDescription  Hard delete a currency
     * @apiGroup        Currency
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $updated = $this->currency->delete($id);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        if (! $updated) {
            return $this->jsonResponse(null, 'Unable to delete currency', 400);
        }

        return $this->jsonResponse($updated, 'Currency deleted successfully');
    }
}
