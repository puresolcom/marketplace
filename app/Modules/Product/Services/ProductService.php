<?php
namespace Awok\Modules\Product\Services;

use Awok\Core\Eloquent\Model;
use Awok\Modules\Product\Models\Attribute;
use Awok\Modules\Product\Models\AttributeValue;
use Awok\Modules\Product\Models\AttributeValueTranslation;
use Awok\Modules\Product\Models\Product;

class ProductService
{
    /**
     * @var \Awok\Modules\Product\Models\Product
     */
    protected $productModel;

    public function __construct(Product $product)
    {
        $this->productModel = $product;
    }

    /**
     * Query against products
     *
     * @param null   $fields
     * @param null   $filters
     * @param null   $sort
     * @param null   $relations
     * @param null   $limit
     * @param string $dataKey
     *
     * @return mixed
     */
    public function fetch(
        $fields = null,
        $filters = null,
        $sort = null,
        $relations = null,
        $limit = null,
        $dataKey = 'products'
    ) {
        return $this->productModel->restQueryBuilder($fields, $filters, $sort, $relations, $limit, $dataKey);
    }

    public function get($id, $fields, $relations)
    {
        return $this->productModel->restQueryBuilder($fields, [['id' => $id]], null, $relations, null, null, false)->first();
    }

    /**
     * Adds new product
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        // Exclude attributes and taxonomies before updating product
        $productData = array_except($data, ['attributes', 'taxonomies']);

        \DB::beginTransaction();

        try {
            $product = $this->productModel->create($productData);
            if (! empty($data['attributes'])) {
                // Set product attributes
                $this->setProductAttributesValues($product, $data['attributes']);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        \DB::commit();

        return $product;
    }

    /**
     * Set product attributes values
     *
     * @param \Awok\Core\Eloquent\Model $product
     * @param array                     $attributes
     *
     * @return mixed
     * @throws \Exception
     */
    public function setProductAttributesValues(Model $product, array $attributes)
    {
        $attributes = $this->normalizeAttributes($attributes);
        foreach ($attributes as $attribute) {
            $this->setProductAttributeValue($product, $attribute['slug'], $attribute['translations'], $attribute['options_ids']);
        }
    }

    /**
     * Normalize request attributes
     *
     * @param array $attributes
     *
     * @return array
     */
    private function normalizeAttributes(array $attributes)
    {
        $result = [];
        foreach ($attributes as $slug => $value) {

            if (! is_string($slug)) {
                continue;
            }

            $result[] = [
                'slug'         => $slug,
                'options_ids'  => $value,
                'translations' => $value,
            ];
        }

        return $result;
    }

    /**
     * Set a specific product attribute value
     *
     * @param \Awok\Core\Eloquent\Model $product
     * @param                           $attributeSlug
     * @param array                     $attributeValues
     * @param array                     $optionsIDs
     *
     * @throws \Exception
     */
    public function setProductAttributeValue(
        Model $product,
        $attributeSlug,
        array $attributeValues,
        array $optionsIDs = []
    ) {
        $attributeInstance = Attribute::findBySlug($attributeSlug);

        if (! $attributeInstance) {
            throw new \Exception("Attribute with slug {$attributeSlug} cannot be found");
        }

        if ($attributeInstance->type == 'select') {
            $this->setAttributeOptions($product, $optionsIDs, $attributeInstance);
        } else {
            $this->setAttributeValue($product, $attributeValues, $attributeInstance);
        }
    }

    /**
     * Sets product attribute options (select and multiple options values)
     *
     * @param \Awok\Core\Eloquent\Model $product
     * @param array                     $optionsIDs
     * @param                           $attributeInstance
     */
    protected function setAttributeOptions(Model $product, array $optionsIDs, $attributeInstance)
    {
        $optionsIDs                       = array_unique($optionsIDs);
        $currentAttributeValues           = AttributeValue::where(['attribute_id' => $attributeInstance->id]);
        $currentAttributeValuesOptionsIds = $currentAttributeValues->where('product_id', '=', $product->id)->get()->pluck('option_id')->toArray();
        $attributesOptionsToBeAdded       = array_diff($optionsIDs, $currentAttributeValuesOptionsIds);
        $attributesOptionsToBeDeleted     = array_diff(array_merge($attributesOptionsToBeAdded, $currentAttributeValuesOptionsIds), $optionsIDs);

        $currentAttributeValues->whereIn('option_id', $attributesOptionsToBeDeleted)->delete();

        foreach ($attributesOptionsToBeAdded as $optionID) {
            $product->attributes()->attach([$attributeInstance->id => ['option_id' => $optionID]]);
        }
    }

    /**
     * Sets product attributes values (translations)
     *
     * @param \Awok\Core\Eloquent\Model $product
     * @param array                     $attributeValues
     * @param                           $attributeInstance
     */
    protected function setAttributeValue(Model $product, array $attributeValues, $attributeInstance)
    {
        $productHasAttribute = $valueRelation = $product->attributes()->where('slug', '=', $attributeInstance->slug)->first();
        if (! $productHasAttribute) {
            $valueRelation = $product->attributes()->save($attributeInstance);
        }

        // Unset product attribute value
        if (empty($attributeValues)) {
            $this->unsetAttributeValue($product, $attributeInstance);
        }

        foreach ($attributeValues as $value) {

            if (! isset($value['value']) || empty($value['value'])) {
                $locale = $value['locale'] ?? null;

                $this->unsetAttributeValue($product, $attributeInstance, $locale);
            } else {
                $valueRelation->values()->where('product_id', '=', $product->id)->first()
                    ->translations()->updateOrCreate(['locale' => $value['locale']], ['value' => $value['value']]);
            }
        }
    }

    /**
     * Unset product attribute value (translations)
     *
     * @param \Awok\Core\Eloquent\Model $product
     * @param \Awok\Core\Eloquent\Model $attributeInstance
     * @param null                      $locale
     *
     * @return mixed
     */
    public function unsetAttributeValue(Model $product, Model $attributeInstance, $locale = null)
    {

        $attributeValues = AttributeValue::where('product_id', '=', $product->id)
            ->where('attribute_id', '=', $attributeInstance->id);

        // Delete Value locales
        if ($locale) {
            return AttributeValueTranslation::whereIn('translatable_id', array_pluck($attributeValues->get(), 'id'))
                ->where('locale', '=', $locale)
                ->delete();
        }

        // Delete Value
        return $attributeValues->delete();
    }

    /**
     * Update product
     *
     * @param       $id
     * @param array $data
     *
     * @return bool
     * @throws \Exception
     */
    public function update($id, array $data)
    {
        $product = $this->productModel->find($id);

        if (! $product) {
            throw new \Exception('Product could not be found', 400);
        }

        \DB::beginTransaction();

        try {
            // Exclude attributes and taxonomies before updating product
            $productData = array_except($data, ['attributes', 'taxonomies']);
            $product->fill($productData)->save();

            if (! empty($data['attributes'])) {
                // Set product attributes
                $this->setProductAttributesValues($product, $data['attributes']);
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        \DB::commit();

        return true;
    }

    /**
     * Deletes a product by id
     *
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        return ($delete = $this->productModel->find($id)) ? $delete->delete() : false;
    }
}