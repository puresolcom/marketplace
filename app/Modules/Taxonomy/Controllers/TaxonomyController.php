<?php

namespace Awok\Modules\Taxonomy\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Taxonomy\Services\TaxonomyService;

class TaxonomyController extends Controller
{
    /**
     * @var TaxonomyService $taxonomyService
     */
    protected $taxonomyService;

    public function __construct()
    {
        $this->taxonomyService = app('taxonomy');
    }

    /**
     * @api                     {get}   /taxonomy/:id   1. Get Term
     * @apiDescription          Finds a specific object using the provided :id segment
     * @apiGroup                Taxonomy
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
            $result = $this->taxonomyService->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Product not found', 400);
    }

    /**
     * @api                     {get}          /taxonomy            2. Terms List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                Taxonomy
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
    public function fetch(Request $request)
    {
        try {
            $result = $this->taxonomyService->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api            {post}               /taxonomy   3. Create term
     * @apiDescription Create a new taxonomy term
     * @apiGroup       Taxonomy
     * @apiParam       {Object[]}           name        Taxonomy Term name (If string is used value will be inserted as
     *                 default locale "en")
     * @apiParam       {String}             type        Taxonomy Term Type
     * @apiParam       {String}             [slug]      Taxonomy Slug for term (Required in case of non-string name)
     * @apiParam       {Number}             [parent_id] Taxonomy Term parent ID
     *                 values)
     * @apiParamExample {json} Request-Example:
     *{
     *    "type": "category",
     *    "slug": "mobile-phones",
     *    "parent_id" : 100,
     *    "name": [
     *      {
     *      "locale": "en",
     *      "name"  : "Mobile Phones"
     *      },
     *      {
     *      "locale": "ar",
     *      "name"  : "هواتف جوالة"
     *      }
     *    ]
     *}
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        try {
            $this->taxonomyService->createTerm($request->get('name'), $request->except('name'));
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Taxonomy term created successfully');
    }

    /**
     * @api            {put}               /taxonomy        4. Update term
     * @apiDescription Update a current taxonomy term
     * @apiGroup       Taxonomy
     * @apiParam       {String}             [type]          Taxonomy Term Type
     * @apiParam       {Object[]}           [name]          Taxonomy Term name (If string is used value will be
     *                 inserted as default locale "en")
     * @apiParam       {String}             [slug]          Taxonomy Slug for term (Required in case of multilingual
     *                 name
     * @apiParam       {Number}             [parent_id]     Taxonomy Term parent ID
     *                 values)
     * @apiParamExample {json} Request-Example:
     *{
     *    "type": "category",
     *    "slug": "mobile-phones",
     *    "parent_id" : 100,
     *    "name": [
     *      {
     *      "locale": "en",
     *      "name"  : "Mobile Phones"
     *      },
     *      {
     *      "locale": "ar",
     *      "name"  : "هواتف جوالة"
     *      }
     *    ]
     *}
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->taxonomyService->updateTerm($id, $request->all());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Taxonomy term updated successfully');
    }

    /**
     * @api             {DELETE}    /taxonomy/:id   5. Delete taxonomy term
     * @apiDescription  Delete taxonomy term
     * @apiGroup        Taxonomy
     *
     * @param $id
     *
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        try {
            $deleted = $this->taxonomyService->deleteTerm($id);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        if (! $deleted) {
            return $this->jsonResponse(null, 'Unable to delete term', 400);
        }

        return $this->jsonResponse($deleted, 'Term deleted successfully');
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