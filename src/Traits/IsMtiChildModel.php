<?php

namespace NorseBlue\Parentity\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Trait IsMtiChildModel
 *
 * @package NorseBlue\Parentity\Traits
 *
 * @property \NorseBlue\Parentity\Traits\IsMtiParentModel $parent
 * @property string $parentEntity
 * @property string $parentModel
 *
 * @method mixed create(array $attributes)
 * @method static mixed create(array $attributes)
 */
trait IsMtiChildModel
{
    use ResolvesProperties;

    /** {@inheritdoc} */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['create'])) {
            return $this->processCall($method, $parameters);
        }

        return parent::__call($method, $parameters);
    }

    /** {@inheritdoc} */
    public function __get($key)
    {
        if ($key !== 'parent'
            && !in_array($key, ['created_at', 'updated_at', 'deleted_at'])
            && in_array($key, $this->parent->getOwnAttributes(), true)
        ) {
            return $this->parent->$key;
        }

        $resolveMap = [
            'parentEntity' => '',
            'parentModel' => '',
        ];
        if (array_key_exists($key, $resolveMap)) {
            return $this->resolveValue($key, null, $resolveMap);
        }

        return $this->getAttribute($key);
    }

    /** {@inheritdoc} */
    public function __isset($key)
    {
        return parent::__isset($key);
    }

    /** {@inheritdoc} */
    public function __set($key, $value)
    {
        if ($key !== 'parent'
            && !in_array($key, ['created_at', 'updated_at', 'deleted_at'])
            && in_array($key, $this->parent->getOwnAttributes(), true)
        ) {
            $this->parent->$key = $value;
        }

        $this->setAttribute($key, $value);
    }

    /**
     * Gets the parent relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function parent(): MorphOne
    {
        return $this->morphOne($this->parentModel, $this->parentEntity);
    }

    /**
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    private function processCall($method, $parameters)
    {
        if ($parameters[0] instanceof Model && in_array(IsMtiParentModel::class, class_uses($parameters[0]), true)) {
            $parent = array_shift($parameters);
        } else {
            $parent = $this->parentModel::$method(...$parameters);
        }
        $child = $this->forwardCallTo($this->newQuery(), $method, $parameters);
        $child->parent()->save($parent);

        return $child;
    }
}
