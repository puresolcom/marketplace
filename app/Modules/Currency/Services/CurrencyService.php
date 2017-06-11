<?php
namespace Awok\Modules\Currency\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Currency\Models\Currency;

class CurrencyService extends BaseService
{
    public function __construct(Currency $currency)
    {
        $this->setBaseModel($currency);
    }

    /**
     * Creates a new Location
     *
     * @param array $storeData
     *
     * @return mixed
     */
    public function create(array $storeData)
    {
        return $this->getBaseModel()->create($storeData);
    }
}