<?php
namespace Awok\Modules\Store\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Store\Models\Store;

class StoreService extends BaseService
{
    public function __construct()
    {
        $this->setBaseModel(Store::class);
    }

    /**
     * Creates a new store
     *
     * @param array $storeData
     *
     * @return mixed
     */
    public function create(array $storeData)
    {
        // Slugify the name
        $storeData['slug'] = str_slug($storeData['slug']) ?? str_slug($storeData['name']);

        // In case no user_id supplied, use logged in user id
        if (! isset($storeData['user_id'])) {
            $storeData['user_id'] = app('auth')->user()->id;
        }

        return $this->getBaseModel()->create($storeData);
    }
}