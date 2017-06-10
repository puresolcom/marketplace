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
    protected $taxonomy;

    public function __construct()
    {
        $this->taxonomy = app('taxonomy');
    }

    public function create(Request $request)
    {
        try {
            $this->taxonomy->addTerm($request->get('name'), $request->except('name'));
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Taxonomy term created successfully');
    }

    public function syncWithBitrix()
    {
        try {
            $this->taxonomy->syncWithBitrix();
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Taxonomies Synced successfully');
    }
}