<?php
namespace Awok\Modules\Location\Services;

use Awok\Modules\Location\Models\Location;

class LocationService
{
    /**
     * @var \Awok\Modules\Location\Models\Location
     */
    protected $storeModel;

    public function __construct(Location $store)
    {
        $this->storeModel = $store;
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
        return $this->storeModel->create($storeData);
    }
}