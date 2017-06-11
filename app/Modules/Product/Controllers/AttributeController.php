<?php
namespace Awok\Modules\Product\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;

class AttributeController extends Controller
{
    protected $attributeService;

    public function __construct()
    {
        $this->attributeService = app('product.attribute');
    }

    /**
     * Get paginated attributes
     *
     * @route /product [GET]
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function fetch(Request $request)
    {
        try {
            $result = $this->attributeService->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * Get single attribute
     *
     * @route /product/{id} [GET]
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request, $id)
    {
        try {
            $result = $this->attributeService->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Attribute not found', 400);
    }
}