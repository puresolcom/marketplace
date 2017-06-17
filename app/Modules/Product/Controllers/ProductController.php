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
     * @api            {POST}              /product         3. Create product
     * @apiDescription Create a new product
     * @apiGroup       Product
     * @apiParam       {Object[]}             title          Title translations
     * @apiParam       {Object[]}             description    Description translations
     * @apiParam       {Number}               stock          Stock quantity
     * @apiParam       {String}               upc            UPC code of the product
     * @apiParam       {String}               sku            SKU code of the product
     * @apiParam       {Decimal}              price          Price of the product
     * @apiParam       {Decimal}              discount_price Price to be deducted from origin price as discount
     * @apiParam       {Number}               currency_id    ID of the base currency for this product
     * @apiParam       {Number}               store_id       ID of the store to assign this product to
     * @apiParam       {Number[]}             [categories]   Array of categories IDs for this product
     * @apiParam       {Number[]}             [tags]         Array of tags IDs for this product
     * @apiParam       {Object[]}             [attributes]   Array of custom product attributes values and translations
     * @apiParamExample {json} Request-Example:
     *{
     *"title"            :
     *[
     *    {
     *        "locale": "en",
     *        "value": "Sample Product"
     *    },
     *    {
     *        "locale": "ar",
     *            "value": "منتج رمزى"
     *        }
     *],
     *"description"    :
     *    [
     *        {
     *            "locale": "en",
     *            "value": "Sample Description"
     *        },
     *        {
     *            "locale": "ar",
     *            "value": "وصف رمزى"
     *        }
     *    ],
     *    "stock"            : 10,
     *    "upc"              : "123456789124",
     *    "sku"              : "123456789124",
     *    "price"            : "99.99",
     *    "discount_price"   : "49.99",
     *    "currency_id"      : 5,
     *    "store_id"         : 10,
     *    "categories"       : [100,200],
     *    "tags"             : [300,400],
     *    "attributes"       : {
     *    "color":
     *            [
     *                {
     *                    "locale"   : "en",
     *                    "value"    : "red"
     *                },
     *                {
     *                    "locale"   : "ar",
     *                    "value"    : "احمر"
     *                }
     *            ],
     *        "material":
     *            [
     *                {
     *                    "locale"   : "en",
     *                    "value"    : "Leather"
     *                }
     *            ],
     *        "size": [1,2]
     *    }
     *}
     * @apiParamExample {json} Value-Based-Attribute-Example:
     *    "attributes"    :{
     *                          "color":
     *                          [
     *                              {
     *                                  "locale": "en",
     *                                  "value" : "red"
     *                              },
     *                              {
     *                                  "locale": "ar",
     *                                  "value"    : "احمر"
     *                              }
     *                          ]
     *                      }
     * @apiParamExample {json} Option-Based-Attribute-Example:
     *    "attributes"    :{
     *                          "size": [1,2]
     *                      }
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
        $productData    = $request->expected($expectedFields);

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
            return $this->jsonResponse(null, $e, 400);
        }

        return $this->jsonResponse(null, 'Product added successfully');
    }

    /**
     * @api            {PUT}                 /product/:id   4. Update product
     * @apiDescription Update product information
     * @apiGroup       Product
     * @apiParam       {Object[]}             [title]          Title translations
     * @apiParam       {Object[]}             [description]    Description translations
     * @apiParam       {Number}               [stock]          Stock quantity
     * @apiParam       {String}               [upc]            UPC code of the product
     * @apiParam       {String}               [sku]            SKU code of the product
     * @apiParam       {Decimal}              [price]          Price of the product
     * @apiParam       {Decimal}              [discount_price] Price to be deducted from origin price as discount
     * @apiParam       {Number}               [urrency_id]     ID of the base currency for this product
     * @apiParam       {Number}               [store_id]       ID of the store to assign this product to
     * @apiParam       {Number[]}             [categories]     Array of categories IDs for this product
     * @apiParam       {Number[]}             [tags]           Array of tags IDs for this product
     * @apiParam       {Object[]}             [attributes]     Array of custom product attributes values and
     *                 translations
     * @apiParamExample {json} Request-Example:
     *{
     *"title"            :
     *[
     *    {
     *        "locale": "en",
     *        "value": "Sample Product"
     *    },
     *    {
     *        "locale": "ar",
     *            "value": "منتج رمزى"
     *        }
     *],
     *"description"    :
     *    [
     *        {
     *            "locale": "en",
     *            "value": "Sample Description"
     *        },
     *        {
     *            "locale": "ar",
     *            "value": "وصف رمزى"
     *        }
     *    ],
     *    "stock"            : 10,
     *    "upc"              : "123456789124",
     *    "sku"              : "123456789124",
     *    "price"            : "99.99",
     *    "discount_price"   : "49.99",
     *    "currency_id"      : 5,
     *    "store_id"         : 10,
     *    "categories"       : [100,200],
     *    "tags"             : [300,400],
     *    "attributes"       : {
     *    "color":
     *            [
     *                {
     *                    "locale"   : "en",
     *                    "value"    : "red"
     *                },
     *                {
     *                    "locale"   : "ar",
     *                    "value"    : "احمر"
     *                }
     *            ],
     *        "material":
     *            [
     *                {
     *                    "locale"   : "en",
     *                    "value"    : "Leather"
     *                }
     *            ],
     *        "size": [1,2]
     *    }
     *}
     * @apiParamExample {json} Value-Based-Attribute-Example:
     *    "attributes"    :{
     *                          "color":
     *                          [
     *                              {
     *                                  "locale": "en",
     *                                  "value" : "red"
     *                              },
     *                              {
     *                                  "locale": "ar",
     *                                  "value"    : "احمر"
     *                              }
     *                          ]
     *                      }
     * @apiParamExample {json} Option-Based-Attribute-Example:
     *    "attributes"    :{
     *                          "size": [1,2]
     *                      }
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
            return $this->jsonResponse(null, $e, 400);
        }

        return $this->jsonResponse(null, 'Product updated successfully');
    }

    /**
     * @api                     {get}   /product/:id   1. Get Product
     * @apiDescription          Finds a specific object using the provided :id segment
     * @apiGroup                Product
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
            $result = $this->productService->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Product not found', 400);
    }

    /**
     * @api                     {get}   /product  2. Products List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                Product
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
            $result = $this->productService->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api                     {get}   /product/:id/attributes 5. Get Product Attributes
     * @apiDescription          Listing product attributes along with their values
     * @apiGroup                Product
     *
     * @param $productID
     *
     * @return \Illuminate\Http\Response
     */
    public function getProductAttributes($productID)
    {
        try {
            $result = $this->productService->getProductAttributes($productID);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e, $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }
}
