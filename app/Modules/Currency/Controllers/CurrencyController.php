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
     * @api                     {get}   /currency/:id   Get Currency
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
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Object not found', 400);
    }

    /**
     * @api                     {get}   /currency Currencies List
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
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }
}
