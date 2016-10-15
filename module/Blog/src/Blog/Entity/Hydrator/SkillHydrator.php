<?php

namespace Blog\Entity\Hydrator;

use Blog\Entity\Tag;
use Blog\Entity\Skill;
use Zend\Stdlib\Hydrator\HydratorInterface;

class SkillHydrator implements HydratorInterface
{
    
    public function extract($object)
    {
        if (!$object instanceof Skill || $object->getTag() == null) {
            return array();
        }

        $tag = $object->getTag();

        return array(
            'id' => $tag->getId(),
            'name' => $tag->getName(),
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
        if (!$object instanceof Skill || $data['tag_id'] == null) {
            return $object;
        }

        $tag = new Tag();
        $tag->setId(isset($data['tag_id']) ? intval($data['tag_id']) : null);
        $tag->setName(isset($data['tag_name']) ? $data['tag_name'] : null);

        $object->setTag($tag);
        
        return $object;
    }
}