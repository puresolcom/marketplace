<?php
namespace Awok\Modules\Location\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Location\Models\Location;

/**
 * Class CityController
 *
 * @package Awok\Modules\Location\Controllers
 */
class CityController extends Controller
{
    /**
     * @param \Awok\Core\Http\Request                $request
     * @param \Awok\Modules\Location\Models\Location $location
     *
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request, Location $location)
    {
        try {
            $result = $location->restQueryBuilder($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }
}
