<?php

namespace Awok\Core\Foundation;

use Awok\Core\Eloquent\Model;

abstract class BaseService
{
    protected $baseModel;

    protected $baseModelFQN;

    protected function getBaseModel()
    {
        return $this->makeModel();
    }

    public function setBaseModel($baseModelFQN)
    {
        $this->baseModelFQN = $baseModelFQN;
        $this->baseModel    = app($baseModelFQN);
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

        $result = $this->getBaseModel()->restQueryBuilder($fields, $filters, $sort, $relations, $limit, $dataKey);
        $this->resetModel();

        return $result;
    }

    /**
     * @param $id
     * @param $fields
     * @param $relations
     *
     * @return mixed
     */
    public function get($id, $fields, $relations)
    {
        $result = $this->getBaseModel()->restQueryBuilder($fields, [['id' => $id]], null, $relations, null, null, false)->first();
        $this->resetModel();

        return $result;
    }

    public function findWhere(array $where, $columns = ['*'])
    {
        $this->applyConditions($where);

        $model = $this->baseModel->get($columns);
        $this->resetModel();

        return $model;
    }

    /**
     * Applies the given where conditions to the model.
     *
     * @param array $where
     *
     * @return void
     */
    protected function applyConditions(array $where)
    {
        foreach ($where as $field => $value) {
            if (is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->baseModel = $this->baseModel->where($field, $condition, $val);
            } else {
                $this->baseModel = $this->baseModel->where($field, '=', $value);
            }
        }
    }

    /**
     * Deletes object by id
     *
     * @param $id
     *
     * @return bool
     */
    public function delete($id)
    {
        return ($delete = $this->getBaseModel()->find($id)) ? $delete->delete() : false;
    }

    public function makeModel($modelFQN = null)
    {
        $model = app($modelFQN ?? $this->baseModelFQN);

        if (! $model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }

        return $this->baseModel = $model;
    }

    public function resetModel()
    {
        $this->makeModel();
    }
}