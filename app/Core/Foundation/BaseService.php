<?php

namespace Awok\Core\Foundation;

use Awok\Core\Eloquent\Model;

abstract class BaseService
{
    protected $baseModel;

    protected function getBaseModel()
    {
        return $this->baseModel;
    }

    public function setBaseModel(Model $baseModel)
    {
        $this->baseModel = $baseModel;
    }

    /**
     * Query against model
     *
     * @param null   $fields
     * @param null   $filters
     * @param null   $sort
     * @param null   $relations
     * @param null   $limit
     * @param string $dataKey
     *
     * @return mixed
     */
    public function fetch(
        $fields = null,
        $filters = null,
        $sort = null,
        $relations = null,
        $limit = null,
        $dataKey = null
    ) {
        return $this->getBaseModel()->restQueryBuilder($fields, $filters, $sort, $relations, $limit, $dataKey);
    }

    public function get($id, $fields, $relations)
    {
        return $this->getBaseModel()->restQueryBuilder($fields, [['id' => $id]], null, $relations, null, null, false)->first();
    }
}