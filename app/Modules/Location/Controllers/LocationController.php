<?php
namespace Awok\Modules\Location\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Location\Services\LocationService;

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
}
