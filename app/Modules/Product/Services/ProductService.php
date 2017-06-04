<?php
namespace Awok\Modules\Product\Services;

use Awok\Modules\Product\Models\Product;

class ProductService
{
    /**
     * @var \Awok\Modules\Product\Models\Product
     */
    protected $storeModel;

    public function __construct(Product $store)
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