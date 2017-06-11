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
