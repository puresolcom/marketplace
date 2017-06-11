<?php
namespace Awok\Modules\Product\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Product\Services\ProductService;

class ProductController extends Controller
{
    /**
     * @var ProductService
     */
    protected $productService;

    public function __construct()
    {
        $this->productService = app('product');
    }

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
            'title',
            'description',
            'upc',
            'sku',
            'price',
            'discount_price',
            'stock',
            'currency_id',
            'store_id',
            'attributes',
            'categories',
            'tags',
        ];
        $productData    = $request->only($expectedFields);

        $validator = $this->validate($request, [
            'title'          => 'required',
            'description'    => 'required',
            'upc'            => 'required|max:12|unique:products',
            'sku'            => 'required|max:12',
            'price'          => 'required|numeric',
            'discount_price' => 'numeric',
            'stock'          => 'required|numeric',
            'currency_id'    => 'required|exists:currencies,id',
            'store_id'       => 'required|exists:stores,id',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $this->productService->create($productData);
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
            $this->productService->update($id, $request->all());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Product updated successfully');
    }

    /**
     * Get single product
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
            $result = $this->productService->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Product not found', 400);
    }

    /**
     * Get paginated products
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
            $result = $this->productService->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }
}
