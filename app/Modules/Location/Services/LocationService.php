<?php
namespace Awok\Modules\Location\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Location\Models\Country;
use Awok\Modules\Location\Models\Location;

class LocationService extends BaseService
{
    /**
     * @var Country
     */
    protected $countryModel;

    public function __construct(Location $location, Country $country)
    {
        $this->setBaseModel($location);
        $this->countryModel = $country;
    }

    /**
     * Query against country
     *
     * @param null $fields
     * @param null $filters
     * @param null $sort
     * @param null $relations
     * @param null $limit
     * @param null $dataKey
     *
     * @return mixed
     */
    public function fetchCountries(
        $fields = null,
        $filters = null,
        $sort = null,
        $relations = null,
        $limit = null,
        $dataKey = null
    ) {
        return $this->countryModel->restQueryBuilder($fields, $filters, $sort, $relations, $limit, $dataKey);
    }

    /**
     * @param $id
     * @param $fields
     * @param $relations
     *
     * @return mixed
     */
    public function getCountry($id, $fields, $relations)
    {
        return $this->countryModel->restQueryBuilder($fields, [['id' => $id]], null, $relations, null, null, false)->first();
    }

    /**
     * Creates a new location
     *
     * @param array $locationData
     *
     * @return mixed
     */
    public function create(array $locationData)
    {
        return $this->getBaseModel()->create($locationData);
    }

    /**
     * Create a new country
     *
     * @param array $countryData
     *
     * @return mixed
     */
    public function createCountry(array $countryData)
    {
        return $this->getBaseModel()->create($countryData);
    }
}