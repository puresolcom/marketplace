<?php
namespace Awok\Modules\Product\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Product\Models\Product;

class ProductController extends Controller
{
    /**
     * Registers a new store
     *
     * @route /store [POST]
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $expectedFields = [
            'name',
            'title',
            'description',
            'upc',
            'sku',
            'price',
            'currency_id',
            'store_id',
        ];
        $storeData      = $request->only($expectedFields);

        $this->validate($request, [
            'name'        => 'required|string',
            'title'       => 'string',
            'description' => 'string',
            'upc'         => 'required|max:12|unique:products',
            'sku'         => 'required|max:12',
            'price'       => 'required|numeric',
            'currency_id' => 'required|exists:currencies,id',
            'store_id'    => 'required|exists:stores,id',
        ]);

        try {
            $createProduct = app('product')->create($storeData);
        } catch (\Exception $e) {
            return $this->jsonResponse('', $e->getMessage(), 400);
        }

        return $this->jsonResponse($createProduct, 'Product added successfully');
    }

    public function test()
    {
        $product = Product::find(2)->with('attributes.values', 'attributes.options.translations')->get();

        return $this->jsonResponse($product->toArray());
    }
}
