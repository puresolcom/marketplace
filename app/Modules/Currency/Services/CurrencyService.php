<?php
namespace Awok\Modules\Currency\Services;

use Awok\Core\Foundation\BaseService;
use Awok\Modules\Currency\Models\Currency;

class CurrencyService extends BaseService
{
    public function __construct()
    {
        $this->setBaseModel(Currency::class);
    }

    /**
     * Creates a new currency
     *
     * @param array $currencyData
     *
     * @return mixed
     */
    public function create(array $currencyData)
    {
        return $this->getBaseModel()->create($currencyData);
    }

    /**
     * Updates a currency
     *
     * @param       $id
     * @param array $currencyData
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, array $currencyData)
    {
        $currency = Currency::find($id);

        if (! $currency) {
            throw new \Exception('Unable to find currency', 400);
        }

        return $currency->update($currencyData);
    }

    /**
     * Delete a currency
     *
     * @param   id
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete($id)
    {
        $currency = Currency::find($id);

        if (! $currency) {
            throw new \Exception('Unable to find currency', 400);
        }

        if ($this->hasProducts($currency)) {
            throw new \Exception('This currency cannot be deleted', 400);
        }

        return $currency->delete();
    }

    /**
     * @param \Awok\Modules\Currency\Models\Currency $currency
     *
     * @return bool
     */
    public function hasProducts(Currency $currency)
    {
        return $currency->products()->count() ?? false;
    }
}