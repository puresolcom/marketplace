<?php

namespace Awok\Core\Eloquent\Traits;

use Awok\Core\Authorization\Exceptions\UnauthorizedAccess;
use Awok\Core\Eloquent\Model;

trait ProtectedCRUD
{
    public $protected = false;

    public $ownerKey = 'user_id';

    public static function boot()
    {
        parent::boot();

        static::saving(function (Model $model) {
            return $model->verifyOwnership();
        });

        static::deleting(function (Model $model) {
            return $model->verifyOwnership();
        });
    }

    public function verifyOwnership($model = null)
    {
        $model = $model ?? $this;

        // return if ownership is false or admin is logged in
        if (! $model->protected || ! app('auth')->user() || app('auth')->user()->hasRole('admin')) {
            return true;
        } else {
            // Relational ownership
            $ownerKey = explode('.', $model->ownerKey);
            if (count($ownerKey) > 1) {
                $field        = array_pop($ownerKey);
                $relationName = $ownerKey;

                $relation  = null;
                $modelCopy = clone $model;

                foreach ($relationName as $relationLevel) {
                    if (! empty($relation)) {
                        $relation = $relation->getRelationValue($relationLevel);
                    } else {
                        $relation = $modelCopy->getRelationValue($relationLevel);
                    }
                }

                if (! isset($relation->{$field})) {
                    throw new \Exception('Ownership defined in protected model '.get_class($model).' seems to be invalid, unable to resolve the relationship');
                }

                if ($relation->{$field} != app('auth')->user()->id) {
                    throw new UnauthorizedAccess();
                }
            } else {
                if ($model->{$model->ownerKey} != app('auth')->user()->id) {
                    throw new UnauthorizedAccess();
                }
            }
        }

        return true;
    }

    public function filterByOwner($builderInstance)
    {
        // return if ownership is false or admin is logged in
        if (! $this->protected || ! app('auth')->user() || app('auth')->user()->hasRole('admin')) {
            return true;
        } else {
            // Relational ownership
            $ownerKey = explode('.', $this->ownerKey);
            if (count($ownerKey) > 1) {
                $field        = array_pop($ownerKey);
                $relationName = implode('.', $ownerKey);
                $builderInstance->whereHas($relationName, function ($q) use ($field) {
                    $q->where($field, app('auth')->user()->id);
                });

                return true;
            }
            $builderInstance->where($this->ownerKey, app('auth')->user()->id);

            return true;
        }
    }

    /**
     * Find a model by its primary key.
     *
     * @param  mixed $id
     * @param  array $columns
     *
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|null
     */
    public function protectedFind($id, $columns = ['*'])
    {
        if (is_array($id)) {
            return $this->findMany($id, $columns);
        }

        $model = $this->where($this->getQualifiedKeyName(), '=', $id);

        $result = $model->first($columns);

        if ($this->protected) {
            $this->verifyOwnership($result);
        }

        return $result;
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ($method == 'find') {
            return $this->protectedFind(...$parameters);
        }

        return parent::__call($method, $parameters);
    }
}
