<?php

namespace NorseBlue\Parentity\Traits;

use NorseBlue\Parentity\Eloquent\MtiParentModel;

trait IsMtiChildModel
{
    protected $parentModel = '';

    protected $parentEntity = '';

    public function parent()
    {
        return $this->morphOne($this->parentModel, $this->parentEntity);
    }

    /**
     * Handle dynamic method calls into the model.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['create'])) {
            return $this->processCall($method, $parameters);
        }

        return parent::__call($method, $parameters);
    }

    private function processCall($method, $parameters)
    {
        $parent = $parameters[0] instanceof MtiParentModel ? array_shift($parameters) : $this->parentModel::create($parameters[0]);
        $child = $this->forwardCallTo($this->newQuery(), $method, $parameters);
        $child->parent()->save($parent);

        return $child;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key != 'parent' && !in_array($key, ['created_at', 'updated_at', 'deleted_at']) && in_array($key, $this->parent->getOwnAttributes())) {
            return $this->parent->$key;
        }

        return $this->getAttribute($key);
    }

    /**
     * Dynamically set attributes on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function __set($key, $value)
    {
        if ($key != 'parent' && !in_array($key, ['created_at', 'updated_at', 'deleted_at']) && in_array($key, $this->parent->getOwnAttributes())) {
            $this->parent->$key = $value;
        }

        $this->setAttribute($key, $value);
    }
}
