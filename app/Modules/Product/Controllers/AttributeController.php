<?php
namespace Awok\Modules\Product\Controllers;

use Awok\Core\Http\Request;
use Awok\Http\Controllers\Controller;
use Awok\Modules\Product\Services\AttributeService;
use Illuminate\Validation\Rule;

class AttributeController extends Controller
{
    /**
     * @var AttributeService
     */
    protected $attributeService;

    public function __construct()
    {
        $this->attributeService = app('product.attribute');
    }

    /**
     * @api                     {get}   /product/attribute:id   1. Get Attribute
     * @apiDescription          Finds a specific object using the provided :id segment
     * @apiGroup                Attribute
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
            $result = $this->attributeService->get($id, $request->getFields(), $request->getRelations());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return ($result) ? $this->jsonResponse($result) : $this->jsonResponse(null, 'Attribute not found', 400);
    }

    /**
     * @api                     {get}   /product/attribute  2. Attributes List
     * @apiDescription          Getting paginated objects list
     * @apiGroup                Attribute
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
            $result = $this->attributeService->fetch($request->getFields(), $request->getFilters(), $request->getSort(), $request->getRelations(), $request->getPerPage());
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), $e->getCode() ?? 400);
        }

        return $this->jsonResponse($result);
    }

    /**
     * @api            {POST}              /attribute         3. Create attribute
     * @apiDescription Create a new attribute
     * @apiGroup       Attribute
     * @apiParam       {Object[]}           name            Name translations
     * @apiParam       {String}             slug            Attribute Slug (Alpha Numeric Dash)
     * @apiParam       {String}             [type]          Attribute Type (string, text, select, checkbox) (String by
     *                 default)
     * @apiParam       {Boolean}            [multiple]      Multiple values case of type (select,checkbox) (Only when
     *                 type is in (select, checkbox), false by default)
     * @apiParam       {Object[]}           [options]       Attribute options (Only when multiple is true)
     * @apiParam       {Boolean}            [required]      Attribute Required (false by default)
     * @apiParam       {Number}             [position]      Order in front-end
     * @apiParamExample {json} Request-Example:
     * {
     * "name":
     *      [
     *          {
     *              "locale": "en",
     *              "value"    : "Brand"
     *          },
     *          {
     *              "locale": "ar",
     *              "value"    : "الماركه"
     *          }
     *      ],
     * "slug"        :    "brand",
     * "type"        :    "string",
     * "multiple"    :    false,
     * "required"    :    true,
     * "position"    :    1
     * }
     * @apiParamExample {json} Request-Example-With-Options:
     * {
     * "name":
     *      [
     *          {
     *              "locale": "en",
     *              "value"    : "Sizes"
     *          },
     *          {
     *              "locale": "ar",
     *              "value"    : "الأحجام"
     *          }
     *      ],
     * "slug": "sizes",
     * "type": "select",
     * "multiple": true,
     * "options" :
     *          {
     *          "sizes-small":
     *          {
     *          "name":
     *              [
     *                  {
     *                      "locale": "en",
     *                      "value" : "Small"
     *                  },
     *                  {
     *                      "locale": "ar",
     *                      "value" : "صغير"
     *                  }
     *              ]
     *          },
     *      "sizes-large":
     *          {
     *              "name":
     *                  [
     *                      {
     *                          "locale": "en",
     *                          "value" : "Large"
     *                      },
     *                      {
     *                          "locale": "ar",
     *                          "value" : "كبير"
     *                      }
     *                  ]
     *          }
     *      },
     * "required" : true,
     * "position" : 5
     * }
     *
     * @param \Awok\Core\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $expectedFields = ['name', 'slug', 'type', 'multiple', 'options', 'required', 'position'];
        $attributeData  = $request->only($expectedFields);

        $validator = $this->validate($request, [
            'name'     => 'required',
            'slug'     => 'alpha_dash|unique:products_attributes',
            'type'     => [Rule::in(['string', 'text', 'select', 'checkbox'])],
            'multiple' => 'boolean|required_if:type,select|required_if:type,checkbox',
            'options'  => 'required_if:multiple,true|required_if:multiple,1',
            'required' => 'boolean',
            'position' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $result = $this->attributeService->create($attributeData);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse($result, 'Attribute added successfully');
    }

    /**
     * /**
     * @api            {PUT}              /attribute/:id         3. Update attribute
     * @apiDescription Create a new attribute
     * @apiGroup       Attribute
     * @apiParam       {Object[]}           [name]            Name translations
     * @apiParam       {String}             [type]          Attribute Type (string, text, select, checkbox) (String by
     *                 default)
     * @apiParam       {Boolean}            [multiple]      Multiple values case of type (select,checkbox) (Only when
     *                 type is in (select, checkbox), false by default)
     * @apiParam       {Object[]}           [options]       Attribute options (Only when multiple is true)
     * @apiParam       {Boolean}            [required]      Attribute Required (false by default)
     * @apiParam       {Number}             [position]      Order in front-end
     * @apiParamExample {json} Request-Example:
     * {
     * "name":
     *      [
     *          {
     *              "locale": "en",
     *              "value"    : "Brand"
     *          },
     *          {
     *              "locale": "ar",
     *              "value"    : "الماركه"
     *          }
     *      ],
     * "type"        :    "string",
     * "multiple"    :    false,
     * "required"    :    true,
     * "position"    :    1
     * }
     * @apiParamExample {json} Request-Example-With-Options:
     * {
     * "name":
     *      [
     *          {
     *              "locale": "en",
     *              "value"    : "Sizes"
     *          },
     *          {
     *              "locale": "ar",
     *              "value"    : "الأحجام"
     *          }
     *      ],
     * "type": "select",
     * "multiple": true,
     * "options" :
     *          {
     *          "sizes-small":
     *          {
     *          "name":
     *              [
     *                  {
     *                      "locale": "en",
     *                      "value" : "Small"
     *                  },
     *                  {
     *                      "locale": "ar",
     *                      "value" : "صغير"
     *                  }
     *              ]
     *          },
     *      "sizes-large":
     *          {
     *              "name":
     *                  [
     *                      {
     *                          "locale": "en",
     *                          "value" : "Large"
     *                      },
     *                      {
     *                          "locale": "ar",
     *                          "value" : "كبير"
     *                      }
     *                  ]
     *          }
     *      },
     * "required" : true,
     * "position" : 5
     * }
     *
     * @param \Awok\Core\Http\Request $request
     * @param                         $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $expectedFields = ['name', 'type', 'multiple', 'options', 'required', 'position'];
        $attributeData  = $request->only($expectedFields);

        $validator = $this->validate($request, [
            'type'     => [Rule::in(['string', 'text', 'select', 'checkbox'])],
            'multiple' => 'boolean|required_if:type,select|required_if:type,checkbox',
            'options'  => 'required_if:multiple,true|required_if:multiple,1',
            'required' => 'boolean',
            'position' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->jsonResponse(null, 'Error while validating your input', 400, $validator->getMessageBag()->all());
        }

        try {
            $this->attributeService->update($id, $attributeData);
        } catch (\Exception $e) {
            return $this->jsonResponse(null, $e->getMessage(), 400);
        }

        return $this->jsonResponse(null, 'Attribute Updated successfully');
    }
}