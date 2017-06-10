<?php

namespace Awok\Modules\Taxonomy\Services;

use Awok\Modules\Option\Services\OptionService;
use Awok\Modules\Product\Models\Product;
use Awok\Modules\Taxonomy\Models\Taxonomy;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Collection;

class TaxonomyService
{
    /**
     * @var OptionService
     */
    protected $option;

    protected $taxonomies = [];

    public function __construct()
    {
        $this->option = app('option');
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
    public function addTerm($term, array $args)
    {
        if (! isset($args['type']) || ! array_key_exists($args['type'], $this->getTaxonomies())) {
            throw new \Exception('Invalid term type/taxonomy');
        }

        $type     = $args['type'];
        $parentID = $args['parent_id'] ?? null;
        $name     = $args['name'] ?? $term;

        $slug = isset($args['slug']) ? str_slug($args['slug']) : (is_string($name) ? str_slug($name) : null);

        if (! $slug) {
            throw new \Exception('Could not detect a slug');
        }

        try {
            $this->parentTermMatches($parentID, $type);
        } catch (\Exception $e) {
            throw $e;
        }

        $slugExists = $this->termSlugExists($slug);

        if ($slugExists && isset($args['slug'])) {
            throw new \Exception("Taxonomy Slug {$slug} already exists");
        }

        if ($slugExists) {
            $slug .= '-'.$slugExists;
        }

        \DB::beginTransaction();
        $createdTaxonomyTerm = Taxonomy::create([
            'slug'      => $slug,
            'type'      => $type,
            'parent_id' => $parentID,
        ]);

        $translations = [];
        if (! is_array($name)) {
            $translations[] = [
                'locale' => app('config')->get('app.locale'),
                'value'  => $name,
            ];
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

    public function getTaxonomies()
    {
        return $this->taxonomies;
    }

    /**
     * @param $parentID
     * @param $type
     *
     * @throws \Exception
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
    }

    public function getTerm($id)
    {
        return Taxonomy::find($id);
    }

    /**
     * @param $translations
     * @param $term
     *
     * @throws \Exception
     */
    protected function setTermTranslation(Taxonomy $term, $translations)
    {
        foreach ($translations as $translation) {
            if (! isset($translation['name'])) {
                throw new \Exception('No term name was submitted');
            }
            $name = $translation['name'];
            $term->translations()->updateOrCreate(
                [
                    'locale' => $translation['locale'] ?? app('config')->get('app.locale'),
                ],
                [
                    'name' => $name,
                ]);
        }
    }

    public function update($slug, $type, $locale = null)
    {
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
}