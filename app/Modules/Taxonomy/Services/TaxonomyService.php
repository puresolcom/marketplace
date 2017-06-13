<?php

namespace Awok\Modules\Taxonomy\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Option\Services\OptionService;
use Awok\Modules\Product\Models\Product;
use Awok\Modules\Taxonomy\Models\Taxonomy;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;

class TaxonomyService extends BaseService
{
    /**
     * @var OptionService
     */
    protected $option;

    protected $taxonomies = [];

    public function __construct(Taxonomy $taxonomyModel)
    {
        $this->option = app('option');
        $this->setBaseModel($taxonomyModel);
        $this->registerDefaultTaxonomies();
    }

    public function registerDefaultTaxonomies()
    {
        $this->register('category', []);
        $this->register('tag', []);
    }

    /**
     * Register taxonomy
     *
     * @param string $taxonomy
     * @param array  $args
     *
     * @return bool
     * @throws \Exception
     */
    public function register($taxonomy, array $args)
    {
        if (preg_match('/^[a-zA-Z0-9_]+$/', $taxonomy) !== 1) {
            throw new \Exception(sprintf('Invalid characters used for taxonomy (%s)', $taxonomy));
        }

        if (! in_array($taxonomy, $this->taxonomies)) {
            $this->taxonomies[$taxonomy] = $args;
        }

        return true;
    }

    /**
     * Add a taxonomy term
     *
     * @param       $term
     * @param array $args
     *
     * @return bool
     * @throws \Exception
     */
    public function createTerm($term, array $args)
    {
        $type     = $args['type'] ?? null;
        $parentID = $args['parent_id'] ?? null;
        $name     = $args['name'] ?? $term;
        $slug     = isset($args['slug']) ? str_slug($args['slug']) : (is_string($name) ? str_slug($name) : null);

        $this->termPrechecks($type, $slug, $parentID);

        $slugExists = $this->termSlugExists($slug);

        if ($slugExists && isset($args['slug'])) {
            throw new \Exception("Taxonomy Slug ({$slug}) already exists");
        } elseif ($slugExists && ! isset($args['slug'])) {
            $slug .= '-'.$slugExists;
        }

        \DB::beginTransaction();

        $createdTaxonomyTerm = Taxonomy::create(['slug' => $slug, 'type' => $type, 'parent_id' => $parentID]);

        if (! is_array($name)) {
            $translations[] = ['locale' => app('config')->get('app.locale'), 'name' => $name];
        } else {
            $translations = $name;
        }

        if ($createdTaxonomyTerm) {
            try {
                $this->setTermTranslation($createdTaxonomyTerm, $translations);
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        }
        \DB::commit();

        return true;
    }

    /**
     * @param $type
     * @param $slug
     * @param $parentID
     *
     * @throws \Exception
     */
    protected function termPrechecks($type, $slug, $parentID)
    {
        if (! $this->validTaxonomyType($type)) {
            throw new \Exception('Invalid term type/taxonomy');
        }

        if (! $slug) {
            throw new \Exception('Could not detect a slug');
        }
        try {
            $this->parentTermMatches($parentID, $type);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Determine if taxonomy type is valid
     *
     * @param $type
     *
     * @return bool
     */
    protected function validTaxonomyType($type)
    {
        if (! isset($type) || ! array_key_exists($type, $this->getTaxonomies())) {
            return false;
        }

        return true;
    }

    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

    /**
     * @param $parentID
     * @param $type
     *
     * @throws \Exception
     * @return bool
     */
    protected function parentTermMatches($parentID, $type)
    {
        if ($parentID) {
            $parentTerm = $this->getTerm($parentID);
            if (! $parentTerm) {
                throw new \Exception('Parent term does not exist');
            }

            if ($parentTerm->type != $type) {
                throw new \Exception('Parent/child term type mis-match');
            }
        }

        return true;
    }

    public function getTerm($id)
    {
        return Taxonomy::find($id);
    }

    /**
     * @param $slug
     *
     * @return int count of similar slug existence
     */
    protected function termSlugExists($slug)
    {
        $slugExists = Taxonomy::where('slug', 'regexp', "^{$slug}-?[0-9]*$")->count();

        return $slugExists;
    }

    /**
     * @param $translations
     * @param $term
     *
     * @return  bool;
     */
    protected function setTermTranslation(Taxonomy $term, $translations)
    {
        foreach ($translations as $translation) {
            $locale = $translation['locale'] ?? null;
            if (! $locale) {
                $locale = app('config')->get('app.locale');
            }
            $name = $translation['name'] ?? null;

            if ($name) {
                $term->translations()->updateOrCreate(
                    [
                        'locale' => $locale,
                    ],
                    [
                        'name' => $name,
                    ]);
            } else {
                $term->translations()->where('locale', '=', $locale)->delete();
            }
        }

        return true;
    }

    /**
     * Update current term
     *
     * @param       $termID
     * @param array $args
     *
     * @return bool
     * @throws \Exception
     */
    public function updateTerm($termID, array $args)
    {
        $term = Taxonomy::find($termID);

        if (! $term) {
            throw new \Exception("Taxonomy term ID: ({$termID}) cannot be found");
        }

        $name        = $args['name'] ?? null;
        $type        = $args['type'] ?? null;
        $parentID    = $args['parent_id'] ?? null;
        $currentSlug = $term->slug;

        if (isset($args['slug']) && $slugExists = $this->termSlugExists($currentSlug)) {
            throw new \Exception("Taxonomy Slug ({$currentSlug}) is reserved");
        }

        if ($type && ! $this->validTaxonomyType($type)) {
            throw new \Exception('Invalid term type/taxonomy');
        }

        try {
            $this->parentTermMatches($parentID, $type);
        } catch (\Exception $e) {
            throw $e;
        }

        \DB::beginTransaction();

        $updatedTaxonomyTerm = $term->update(array_only($args, ['slug', 'type', 'parent_id']));

        if (isset($name) && ! is_array($name)) {
            $translations[] = ['locale' => app('config')->get('app.locale'), 'name' => $name];
        } elseif (isset($name)) {
            $translations = $name;
        }

        if ($updatedTaxonomyTerm && isset($translations)) {
            try {
                $this->setTermTranslation($term, $translations);
            } catch (\Exception $e) {
                \DB::rollBack();
                throw $e;
            }
        }
        \DB::commit();

        return true;
    }

    /**
     * Set Product Categories
     *
     * @param \Awok\Modules\Product\Models\Product $product
     * @param  array                               $categories
     *
     * @return bool
     */
    public function setProductCategories(Product $product, $categories)
    {
        return $this->setProductTaxonomyTerms($product, $categories, 'category');
    }

    /**
     * Set Product Taxonomy
     *
     * @param \Awok\Modules\Product\Models\Product $product
     * @param array                                $taxonomies
     * @param string                               $type
     *
     * @return bool
     * @throws \Exception
     */
    public function setProductTaxonomyTerms(Product $product, $taxonomies, $type = 'category')
    {
        $providedCategoriesIDs  = ($taxonomies instanceof Collection) ? $taxonomies->pluck('id')->toArray() : array_values($taxonomies);
        $taxonomies             = Taxonomy::where('type', '=', $type)->whereIn('id', $providedCategoriesIDs)->get();
        $availableCategoriesIDs = $taxonomies->pluck('id')->toArray();
        $notFoundCategoriesIds  = array_diff($providedCategoriesIDs, $availableCategoriesIDs);

        if (! empty($notFoundCategoriesIds)) {
            throw new \Exception(sprintf('Invalid %s ids (%s)', ucfirst($type), implode(', ', $notFoundCategoriesIds)));
        }

        $currentProductCategories    = $product->categories()->get();
        $currentProductCategoriesIDs = $currentProductCategories->pluck('id')->toArray();
        $categoriesIDsToBeAdded      = array_diff($providedCategoriesIDs, $currentProductCategoriesIDs);
        $categoriesIDsToBeDeleted    = array_diff(array_merge($categoriesIDsToBeAdded, $currentProductCategoriesIDs), $providedCategoriesIDs);

        if (empty($categoriesIDsToBeAdded) && empty($categoriesIDsToBeDeleted)) {
            return true;
        }

        foreach ($categoriesIDsToBeAdded as $categoryID) {
            $product->taxonomies()->attach($categoryID);
        }

        $product->taxonomies()->detach($categoriesIDsToBeDeleted);

        return true;
    }

    public function setProductTags(Product $product, $tags)
    {
        return $this->setProductTaxonomyTerms($product, $tags, 'tag');
    }

    public function syncWithBitrix()
    {
        $remoteSyncUrl = $this->option->get('taxonomy', 'remote_sync_api_url');

        $httpClient = new Client();
        $res        = $httpClient->request('GET', $remoteSyncUrl, ['verify' => false]);

        if ($res->getStatusCode() !== 200) {
            throw new \Exception('Unexpected response from remote server');
        }

        $categories = \GuzzleHttp\json_decode($res->getBody()->getContents(), true);

        $locale = app('config')->get('app.locale');
        foreach ($categories['DATA'] as $category) {
            $slug       = str_slug($category['CODE']);
            $slugExists = $this->termSlugExists($slug);

            if ($slugExists) {
                continue;
            }

            $taxonomy = Taxonomy::create([
                'id'        => $category['ID'],
                'slug'      => $slug,
                'type'      => 'category',
                'parent_id' => $category['IBLOCK_SECTION_ID'],
            ]);

            if ($taxonomy) {
                $taxonomy->translations()->updateOrCreate(
                    [
                        'locale' => $locale,
                    ],
                    [
                        'name' => $category['NAME'],
                    ]);
            }
        }

        return true;
    }
}