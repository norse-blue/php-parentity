<?php

namespace NorseBlue\Parentity\Traits;

trait IsMtiParentModel
{
    protected $isMtiParentModel = true;

    protected $childAliases = [];

    public function entity()
    {
        return $this->morphTo();
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
        $this->setAttribute($key, $value);
    }
}
