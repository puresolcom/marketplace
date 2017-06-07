<?php
namespace Awok\Modules\Product\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Registers a new product
     *
     * @route /product [POST]
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $expectedFields = [
            'name',
            'description',
            'upc',
            'sku',
            'price',
            'currency_id',
            'store_id',
            'attributes',
            'taxonomies',
        ];
        $productData    = $request->only($expectedFields);

        $this->validate($request, [
            'name'        => 'required|string',
            'description' => 'string',
            'upc'         => 'required|max:12|unique:products',
            'sku'         => 'required|max:12',
            'price'       => 'required|numeric',
            'currency_id' => 'required|exists:currencies,id',
            'store_id'    => 'required|exists:stores,id',
        ]);

        try {
            app('product')->create($productData);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Product added successfully');
    }

    /**
     * Updates a product
     *
     * @route /product/{id} [PUT]
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            app('product')->update($id, $request->all());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Product updated successfully');
    }

    public function get(Request $request, $id)
    {
        try {
            $result = app('product')->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Product not found', 400);
    }

    public function fetch(Request $request)
    {
        try {
            $result = app('product')->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }
}
