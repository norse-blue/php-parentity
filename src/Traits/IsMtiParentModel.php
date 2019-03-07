<?php

namespace NorseBlue\Parentity\Traits;

use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Trait IsMtiParentModel
 *
 * @package NorseBlue\Parentity\Traits
 *
 * @property array $childTypeAliases
 * @property \NorseBlue\Parentity\Traits\IsMtiChildModel $entity
 * @property array $ownAttributes
 *
 * @method mixed create(mixed $entity_type, array $attributes = [])
 * @method static mixed create(mixed $entity_type, array $attributes = [])
 */
trait IsMtiParentModel
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
        if ($key !== 'entity' && !in_array($key, $this->getOwnAttributes(), true)) {
            return $this->entity->$key;
        }

        $resolveMap = [
            'ownAttributes' => [],
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
        if ($key !== 'entity' && !in_array($key, $this->getOwnAttributes(), true)) {
            $this->entity->$key = $value;
        }

        $this->setAttribute($key, $value);
    }

    /**
     * Gets the child entity relationship.
     */
    public function entity(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Gets the attributes owned by the parent.
     *
     * @return array
     */
    public function getOwnAttributes(): array
    {
        return array_merge($this->ownAttributes, ['created_at', 'updated_at', 'deleted_at']);
    }

    /**
     * Actually processes the call.
     *
     * @param string $method
     * @param array $parameters
     *
     * @return mixed
     */
    private function processCall($method, $parameters)
    {
        $childModel = false;
        if (is_string($parameters[0])) {
            $entity_type = array_shift($parameters);
            if (class_exists($entity_type)
                && in_array(IsMtiChildModel::class, class_uses($entity_type), true)
            ) {
                $childModel = $entity_type;
            } else {
                $childModel = $this->childTypeAliases[$entity_type] ?? $entity_type;
            }
        }

        $parent = $this->forwardCallTo($this->newQuery(), $method, $parameters);
        if ($childModel !== false) {
            $child = $childModel::$method($parent, $parameters[0]);
        }

        return $parent;
    }
}
