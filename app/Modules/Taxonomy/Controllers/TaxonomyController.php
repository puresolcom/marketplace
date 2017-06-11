<?php

namespace Awok\Modules\Taxonomy\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Taxonomy\Services\TaxonomyService;

class TaxonomyController extends Controller
{
    /**
     * @var TaxonomyService
     */
    protected $taxonomyService;

    public function __construct()
    {
        $this->taxonomyService = app('taxonomy');
    }

    public function create(Request $request)
    {
        try {
            $this->taxonomyService->addTerm($request->get('name'), $request->except('name'));
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Taxonomy term created successfully');
    }

    /**
     * Get single term
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
            $result = $this->taxonomyService->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Product not found', 400);
    }

    /**
     * Get paginated terms
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
            $result = $this->taxonomyService->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    public function syncWithBitrix()
    {
        try {
            $this->taxonomyService->syncWithBitrix();
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Taxonomies Synced successfully');
    }
}