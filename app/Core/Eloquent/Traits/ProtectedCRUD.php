<?php

namespace Awok\Core\Eloquent\Traits;

use Awok\Core\Authorization\Exceptions\UnauthorizedAccess;
use Awok\Core\Eloquent\Model;

trait ProtectedCRUD
{
    public $ownerKey = true;

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

    public function verifyOwnership()
    {
        // return if ownership is false or admin is logged in
        if (! app('auth')->user() || ! $this->ownerKey || app('auth')->user()->hasRole('admin')) {
            return true;
        } else {
            // Relational ownership
            $ownerKey = explode('.', $this->ownerKey);
            if (count($ownerKey) > 1) {
                $field        = array_pop($ownerKey);
                $relationName = implode('.', $ownerKey);

                if ($this->getRelationValue($relationName)->{$field} != app('auth')->user()->id) {
                    throw new UnauthorizedAccess();
                }
            } else {
                if ($this->{$this->ownerKey} != app('auth')->user()->id) {
                    throw new UnauthorizedAccess();
                }
            }
        }

        return true;
    }

    public function filterByOwner($builderInstance)
    {
        // return if ownership is false or admin is logged in
        if (! app('auth')->user() || ! $this->ownerKey || app('auth')->user()->hasRole('admin')) {
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
}
