<?php

namespace Management\Entity\Hydrator;

use Management\Entity\Tag;
use Zend\Stdlib\Hydrator\HydratorInterface;

class TagHydrator implements HydratorInterface
{
    /**
     * Extract values from an object
     *
     * @param  object $object
     *
     * @return array
     */
    public function extract($object)
    {
        if (!$object instanceof Tag) {
            return array();
        }

        return array(
            'id' => $object->getId(),
            'name' => $object->getName(),
            'slug' => $object->getSlug(),
        );
    }

    /**
     * Hydrate $object with the provided $data.
     *
     * @param  array $data
     * @param  object $object
     *
     * @return object
     */
    public function hydrate(array $data, $object)
    {
        if (!$object instanceof Tag) {
            return $object;
        }

        $object->setId(isset($data['id']) ? intval($data['id']) : null);
        $object->setName(isset($data['name']) ? $data['name'] : null);
        $object->setSlug(isset($data['slug']) ? $data['slug'] : null);

        return $object;
    }
} 