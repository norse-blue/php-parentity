<?php

namespace NorseBlue\Parentity\Traits;

trait IsMtiParentModel
{
    protected $childAliases = [];

    protected $ownAttributes = [];

    public function entity()
    {
        return $this->morphTo();
    }

    public function getOwnAttributes()
    {
        return array_merge($this->ownAttributes, ['created_at', 'updated_at', 'deleted_at']);
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
        $childModel = is_string($parameters[0]) ? $this->childAliases[($entity_type = array_shift($parameters))] ?? $entity_type : false;

        $parent = $this->forwardCallTo($this->newQuery(), $method, $parameters);
        if ($childModel !== false) {
            $child = $childModel::create($parent, $parameters[0]);
        }

        return $parent;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key != 'entity' && !in_array($key, $this->getOwnAttributes())) {
            return $this->entity->$key;
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
        if ($key != 'entity' && !in_array($key, $this->getOwnAttributes())) {
            $this->entity->$key = $value;
        }

        $this->setAttribute($key, $value);
    }
}
