<?php

namespace Awok\Modules\Product\Services;

use Awok\Core\Eloquent\Model;
use Awok\Core\Foundation\BaseService;
use Awok\Modules\Product\Models\Attribute;
use Awok\Modules\Product\Models\AttributeOption;

class AttributeService extends BaseService
{
    public function __construct(Attribute $attribute)
    {
        $this->setBaseModel($attribute);
    }

    /**
     * Create new attribute
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function create(array $data)
    {
        $translatables = ['name'];
        $relations     = ['options'];
        $name          = $data['name'] ?? null;
        $slug          = isset($data['slug']) ? str_slug($data['slug']) : (is_string($name) ? str_slug($name) : null);

        // Exclude non-attribute table data
        $attributeData = array_except($data, array_merge($translatables, $relations));

        if (! $slug) {
            throw new \Exception('Slug must be provided');
        }

        $this->prechecks($data, $translatables, $slug);
        \DB::beginTransaction();
        try {
            $attribute = $this->getBaseModel()->create($attributeData);

            if ($attribute) {
                $this->setTranslation($attribute, array_only($data, $translatables), $translatables);
            }

            $this->setAssociations($data, $attribute);
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        \DB::commit();

        return $attribute;
    }

    /**
     * @param array $data
     * @param       $translatables
     * @param       $slug
     *
     * @throws \Exception
     */
    protected function prechecks(array $data, $translatables, $slug = null)
    {
        $validTranslations = $this->validateTranslatable($data, $translatables);

        if (! $validTranslations) {
            throw new \Exception('Invalid translations format');
        }

        if ($slug && $this->slugExists($slug)) {
            throw new \Exception('Slug already exists');
        }
    }

    /**
     * Validate translations
     *
     * @param array $data
     * @param array $keys
     *
     * @return bool
     */
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

    /**
     * @param $slug
     *
     * @return int count of similar slug existence
     */
    protected function slugExists($slug)
    {
        $slugExists = $this->baseModel->where('slug', 'regexp', "^{$slug}-?[0-9]*$")->count();

        return $slugExists;
    }

    public function setTranslation(Model $model, array $data, array $keys)
    {
        \DB::beginTransaction();
        foreach ($keys as $key) {
            foreach ($data[$key] as $translation) {

                if (! empty($translation['value'])) {
                    $translated = $model->translations()->updateOrCreate(['locale' => $translation['locale']], [$key => $translation['value']]);
                    if (! $translated) {
                        \DB::rollBack();

                        return false;
                    }
                    // Remote translation in case of empty value
                } else {
                    $deleted = $model->translations()->where('locale', '=', $translation['locale'])->delete();
                    if (! $deleted) {
                        \DB::rollBack();

                        return false;
                    }
                }
            }
        }
        \DB::commit();

        return true;
    }

    /**
     * @param array $data
     * @param Model $attribute
     */
    protected function setAssociations(array $data, Model $attribute)
    {
        if (! empty($data['options'])) {
            $this->setOptions($attribute, $data['options']);
        }
    }

    protected function setOptions(Model $attribute, array $data)
    {

        if (! $this->validateOptionTranslatable($data)) {
            throw new \Exception('Invalid options translations format');
        }

        try {
            $this->setOptionTranslations($attribute, $data);
        } catch (\Exception $e) {
            \DB::rollBack();
            throw $e;
        }
    }

    /**
     * Validate Options translations
     *
     * @param array $data
     *
     * @return bool
     */
    public function validateOptionTranslatable(array $data)
    {
        foreach ($data as $slug => $field) {
            foreach ($field as $translations) {
                if (! is_array($translations)) {
                    return false;
                }

                foreach ($translations as $translation) {
                    if (! array_key_exists('locale', $translation) || ! array_key_exists('value', $translation)) {
                        return false;
                    }
                }
            }
        }

        return true;
    }

    public function setOptionTranslations(Model $model, array $data)
    {
        \DB::beginTransaction();

        foreach ($data as $optionSlug => $fields) {
            foreach ($fields as $field => $translations) {
                foreach ($translations as $translation) {

                    $currentOption = $model->options()->where('slug', '=', $optionSlug)->first();

                    $slug            = str_slug($optionSlug);
                    $slugExistsCount = $this->optionSlugExists($model->id, $slug);
                    $slug            = $slugExistsCount ? $slug.'-'.$slugExistsCount : $slug;

                    $option = $currentOption ?? $model->options()->create(['slug' => $slug]);

                    try {
                        $this->setOptionTranslation($option, $translation, $field);
                    } catch (\Exception $e) {
                        \DB::rollBack();
                        throw $e;
                    }
                }
            }
        }
        \DB::commit();

        return true;
    }

    /**
     * @param $attribute_id
     * @param $slug
     *
     * @return int count of similar slug existence
     */
    protected function optionSlugExists($attribute_id, $slug)
    {
        $slugExists = AttributeOption::where('attribute_id', '=', $attribute_id)
            ->where('slug', 'regexp', "^{$slug}-?[0-9]*$")
            ->count();

        return $slugExists;
    }

    public function setOptionTranslation(Model $model, array $translation, $key)
    {
        \DB::beginTransaction();

        if (! empty($translation['value'])) {
            $translated = $model->translations()->updateOrCreate(['locale' => $translation['locale']], [$key => $translation['value']]);
            if (! $translated) {
                \DB::rollBack();

                return false;
            }
            // Remote translation in case of empty value
        } else {
            $deleted = $model->translations()->where('locale', '=', $translation['locale'])->delete();
            if (! $deleted) {
                \DB::rollBack();

                return false;
            }
        }

        \DB::commit();

        return true;
    }

    /**
     * Update Attribute
     *
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, array $data)
    {
        $attribute = Attribute::find($id);

        if (! $attribute) {
            throw new \Exception('Invalid attribute ID');
        }

        $translatables = ['name'];
        $relations     = ['options'];

        // Exclude non-attribute table data
        $attributeData = array_except($data, array_merge($translatables, $relations));

        $this->prechecks($data, $translatables);
        \DB::beginTransaction();
        try {
            $attribute->update($attributeData);

            if ($attribute) {
                $this->setTranslation($attribute, array_only($data, $translatables), $translatables);
            }

            $this->setAssociations($data, $attribute);
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        \DB::commit();

        return $attribute;
    }
}