<?php
namespace Awok\Modules\Store\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Location\Models\Location;
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
     * @throws \Exception
     */
    public function create(array $storeData)
    {
        // Slugify the name
        $storeData['slug'] = str_slug($storeData['slug']) ?? str_slug($storeData['name']);

        // In case no user_id supplied, use logged in user id
        if (! isset($storeData['user_id'])) {
            $storeData['user_id'] = app('auth')->user()->id;
        }

        $city = $this->validCity($storeData['city_id']);
        if (! $city) {
            throw new \Exception('Invalid city ID provided');
        }

        if (! isset($storeData['country_id'])) {
            $storeData['country_id'] = $city->country_id;
        }

        return $this->getBaseModel()->create($storeData);
    }

    /**
     * Update A store
     *
     * @param array $storeData
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, array $storeData)
    {
        $store = $this->getBaseModel()->find($id);

        if (! $store) {
            throw new \Exception('Invalid store ID');
        }

        if (isset($storeData['city_id']) && ! empty($storeData['city_id'])) {
            $city = $this->validCity($storeData['city_id']);

            if (! $city) {
                throw new \Exception('Invalid city ID provided');
            }

            if (! isset($storeData['country_id'])) {
                $storeData['country_id'] = $city->country_id;
            }
        }

        return $store->update($storeData);
    }

    /**
     * Validate if city is valid
     *
     * @param $cityID
     *
     * @return bool
     */
    protected function validCity($cityID)
    {
        $city = Location::where(['id' => $cityID, 'type' => 'city'])->first();

        if (! $city) {
            return false;
        }

        return $city;
    }
}