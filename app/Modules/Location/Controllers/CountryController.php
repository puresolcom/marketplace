<?php
namespace Awok\Modules\Location\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Location\Models\Country;

/**
 * Class CountryController
 *
 * @package Awok\Modules\Location\Controllers
 */
class CountryController extends Controller
{
    /**
     * @param \Awok\Core\Http\Request               $request
     * @param \Awok\Modules\Location\Models\Country $country
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, Country $country)
    {
        try {
            $result = $country->restQueryBuilder($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }
}
