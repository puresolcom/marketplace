<?php

namespace Awok\Core\Eloquent;

use Illuminate\Database\Eloquent\Concerns\QueriesRelationships;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /**
     * Maps API symbols to SQL-like symbols
     *
     * @var array
     */
    protected $symbolMap = [
        ':' => '=',
    ];

    /**
     * Create a new Eloquent query builder for the model.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder|static
     */
    public function newEloquentBuilder($query)
    {
        return new Builder($query);
    }

    /**
     * Prepares a restful query
     *
     * @param null|array $fields
     * @param null|array $filters
     * @param null|array $sort
     * @param null|array $relations
     * @param int        $limit
     * @param string     $dataKey    key for output data (Pagination only)
     * @param bool       $pagination whether to paginate output
     *
     * @return mixed
     */
    public function restQueryBuilder(
        $fields = null,
        $filters = null,
        $sort = null,
        $relations = null,
        $limit = null,
        $dataKey = null,
        $pagination = true
    ) {
        $model   = $this;
        $select  = $this->prepareColumns($fields, $model);
        $with    = $this->prepareRelations($relations, $select);
        $filter  = $this->prepareFilters($filters, $with);
        $sort    = $this->prepareSorting($sort, $filter);
        $dataKey = $dataKey ?? 'results';

        return ($pagination) ? $this->paginateResult($limit, $dataKey, $sort) : $sort;
    }

    /**
     * @param                                     $fields
     * @param \Illuminate\Database\Eloquent\Model $results
     *
     * @return mixed
     */
    protected function prepareColumns($fields, $results)
    {
        // Preparing select columns
        $fields = ! empty($fields) ? $fields : ['*'];
        if (is_array($fields)) {
            $results = $results->select($fields);

            return $results;
        }

        return $results;
    }

    /**
     * @param $relations
     * @param $results
     *
     * @return mixed
     */
    protected function prepareRelations($relations, $results)
    {
        // Preparing relations
        if (is_array($relations)) {
            foreach ($relations as $relation) {
                $results = $results->with([
                    $relation['relationName'] => function ($query) use (
                        $relation
                    ) {
                        if (count($relation['relationFields']) > 0) {
                            $fields = array_merge(['id'], $relation['relationFields']);
                        } else {
                            $fields = ['*'];
                        }

                        return $query->select($fields);
                    },
                ]);
            }

            return $results;
        }

        return $results;
    }

    /**
     * @param $filters
     * @param $results
     *
     * @return bool|\Illuminate\Database\Eloquent\Builder
     */
    protected function prepareFilters($filters, $results)
    {
        // Preparing filters
        if (is_array($filters)) {
            foreach ($filters as $filter) {
                $results = $this->interpretFilterSymbols($results, $filter);
            }

            return $results;
        }

        return $results;
    }

    /**
     * Convert url comparison symbols to mysql symbols and maps relational
     * filtering
     *
     * @param Builder $model
     * @param         $filter
     *
     * @return bool|\Illuminate\Database\Eloquent\Builder|QueriesRelationships
     */
    protected function interpretFilterSymbols(Builder & $model, $filter)
    {
        if (! isset($filter['compare'])) {
            $filter = [
                'field'   => key($filter),
                'compare' => '=',
                'value'   => $filter[key($filter)],
            ];
        }

        if (array_key_exists($filter['compare'], $this->symbolMap)) {
            // Convert symbols
            $filter['compare'] = $this->symbolMap[$filter['compare']];
        }

        if (isset($filter['relational']) && true === $filter['relational']) {
            return $model->whereHas($filter['relationName'], function ($query) use ($filter) {
                return $this->appendClauses($query, $filter);
            });
        } else {
            return $this->appendClauses($model, $filter);
        }
    }

    /**
     * Applies clauses to the current query context/scope
     *
     * @param $model
     * @param $filter
     *
     * @return mixed
     */
    protected function appendClauses(&$model, $filter)
    {
        // Case null check
        if (is_string($filter['value']) && strtolower($filter['value']) == 'NULL') {
            if ($filter['compare'] == '=') {
                return $model->whereNull($filter['field']);
            } else {
                return $model->whereNotNull($filter['field']);
            }
        } else {
            // Case of multiple values
            if (is_array($filter['value'])) {
                return $model->whereIn($filter['field'], $filter['value']);
            }// Any other case
            else {
                return $model->where($filter['field'], $filter['compare'], $filter['value']);
            }
        }
    }

    /**
     * @param $sort
     * @param $results
     *
     * @return mixed;
     */
    protected function prepareSorting($sort, $results)
    {
        // Preparing Sorting
        if (is_array($sort)) {
            foreach ($sort as $order) {
                if (isset($order['relational']) && true === $order['relational']) {
                    $results->whereHas($order['relationName'], function ($query) use ($order) {
                        return $query->orderBy($order['orderBy'], $order['direction']);
                    });
                } else {
                    $results->orderBy($order['orderBy'], $order['direction']);
                }
            }
        }

        return $results;
    }

    /**
     * @param integer|null $limit
     * @param string       $dataKey
     * @param              $results
     *
     * @return mixed
     */
    protected function paginateResult($limit, $dataKey, $results)
    {
        // Paginate
        $limit = is_null($limit) ? config('pagination.limit', 20) : $limit;

        return $results->paginate($limit, ['*'], $pageName = 'page', $page = null, $dataKey);
    }
}