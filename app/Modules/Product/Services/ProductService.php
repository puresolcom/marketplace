<?php
namespace Awok\Modules\Product\Services;

use Awok\Core\Eloquent\Model;
use Awok\Core\Foundation\BaseService;
use Awok\Modules\Product\Models\Attribute;
use Awok\Modules\Product\Models\AttributeValue;
use Awok\Modules\Product\Models\AttributeValueTranslation;
use Awok\Modules\Product\Models\Product;
use Awok\Modules\Taxonomy\Services\TaxonomyService;

class ProductService extends BaseService
{
    /**
     * @var TaxonomyService
     */
    protected $taxonomy;

    public function __construct()
    {
        $this->setBaseModel(Product::class);
        $this->taxonomy = app('taxonomy');
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
        $translatables = ['title', 'description'];
        $relations     = ['attributes', 'categories', 'tags'];
        // Exclude attributes and taxonomies before updating product
        $productData = array_except($data, array_merge($translatables, $relations));

        $validTranslations = $this->validateTranslatable($data, $translatables);

        if (! $validTranslations) {
            throw new \Exception('Invalid translations format');
        }

        \DB::beginTransaction();
        try {
            $product = $this->getBaseModel()->create($productData);

            $this->setTranslation($product, array_only($data, $translatables), $translatables);

            $this->setProductAssociations($data, $product);
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        \DB::commit();

        return $product->with(['attributes', 'categories', 'tags']);
    }

    public function validateTranslatable(array $data, array $keys)
    {
        foreach ($keys as $key) {
            if (! array_has($data, $key)) {
                return false;
            }

            foreach ($data[$key] as $translation) {
                if (! array_key_exists('locale', $translation) || ! array_key_exists('value', $translation)) {
                    return false;
                }
            }
        }

        return true;
    }

    public function setTranslation(Product $model, array $data, array $keys)
    {
        \DB::beginTransaction();
        foreach ($keys as $key) {
            foreach ($data[$key] as $translation) {
                $translated = $model->translations()->updateOrCreate(['locale' => $translation['locale']], [$key => $translation['value']]);
                if (! $translated) {
                    \DB::rollBack();

                    return false;
                }
            }
        }
        \DB::commit();

        return true;
    }

    /**
     * @param array $data
     * @param       $product
     */
    protected function setProductAssociations(array $data, $product)
    {
        if (! empty($data['attributes'])) {
            $this->setProductAttributes($product, $data['attributes']);
        }

        if (! empty($data['categories'])) {
            $this->taxonomy->setProductCategories($product, $data['categories']);
        }

        if (! empty($data['tags'])) {
            $this->taxonomy->setProductTags($product, $data['tags']);
        }
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
    public function setProductAttributes(Model $product, array $attributes)
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

        foreach ($attributesOptionsToBeAdded as $optionID) {
            $product->attributes()->attach([$attributeInstance->id => ['option_id' => $optionID]]);
        }

        $currentAttributeValues->whereIn('option_id', $attributesOptionsToBeDeleted)->delete();
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
        $product = $this->getBaseModel()->find($id);

        if (! $product) {
            throw new \Exception('Product could not be found', 400);
        }

        if (empty($data)) {
            throw new \Exception('No product information to update', 400);
        }

        try {
            $translatables = ['title', 'description'];
            $relations     = ['attributes', 'categories', 'tags'];
            // Exclude attributes and taxonomies before updating product
            $productData = array_except($data, array_merge($translatables, $relations));

            $validTranslations = $this->validateTranslatable($data, $translatables);

            if (! $validTranslations) {
                throw new \Exception('Invalid translations format');
            }

            \DB::beginTransaction();
            $product->fill($productData)->save();

            $this->setTranslation($product, array_only($data, $translatables), $translatables);

            $this->setProductAssociations($data, $product);
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
        return ($delete = $this->getBaseModel()->find($id)) ? $delete->delete() : false;
    }

    /**
     * Gets array of product attributes and values
     *
     * @param $productID
     *
     * @return array
     * @throws \Exception
     */
    public function getProductAttributes($productID)
    {
        if (! ($product = Product::find($productID))) {
            throw new \Exception('Product could not be found');
        }

        $attributes = $product->attributes;

        $result = [];

        foreach ($attributes as $attribute) {
            $result[$attribute->slug]                 = $attribute->toArray();
            $result[$attribute->slug]['translations'] = $attribute->translations;
            $result[$attribute->slug]['values']       = $attribute->values()->where('product_id', '=', $productID)->with('translations')->get()->toArray();
        }

        return $result;
    }
}