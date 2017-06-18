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

    public function __construct(Country $country)
    {
        $this->setBaseModel(Location::class);
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
        return Country::create($countryData);
    }

    /**
     * Updates a location
     *
     * @param       $id
     * @param array $locationData
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, array $locationData)
    {
        $location = Location::find($id);

        if (! $location) {
            throw new \Exception('Unable to find location', 400);
        }

        return $location->update($locationData);
    }

    /**
     * Updates a country
     *
     * @param       $id
     * @param array $countryData
     *
     * @return mixed
     * @throws \Exception
     */
    public function updateCountry($id, array $countryData)
    {
        $country = Country::find($id);

        if (! $country) {
            throw new \Exception('Unable to find country', 400);
        }

        return $country->update($countryData);
    }

    /**
     * Delete a location
     *
     * @param   id
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete($id)
    {
        $location = Location::find($id);

        if (! $location) {
            throw new \Exception('Unable to find location', 400);
        }

        return $location->delete();
    }

    /**
     * Delete a country
     *
     * @param   id
     *
     * @return mixed
     * @throws \Exception
     */
    public function deleteCountry($id)
    {
        $country = Country::find($id);

        if (! $country) {
            throw new \Exception('Unable to find country', 400);
        }

        return $country->delete();
    }
}