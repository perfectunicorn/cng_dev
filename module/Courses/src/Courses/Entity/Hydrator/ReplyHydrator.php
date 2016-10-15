<?php

namespace Courses\Entity\Hydrator;

use Courses\Entity\Topic;/// cambiar a topic
use Courses\Entity\Comment;
use Zend\Stdlib\Hydrator\HydratorInterface;

class ReplyHydrator implements HydratorInterface
{
   
    public function extract($object)
    {
        if (!$object instanceof Comment || $object->getTopic() == null) {
            return array();
        }

        $topic = $object->getTopic();

        return array(
            'id' => $topic->getId(),
            'topic_title' => $topic->getTitle(),
            'topic_slug' => $topic->getSlug(),
            'topic_content' => $topic->getContent(),
            'topic_created' => $topic->getCreated(),
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
        if (!$object instanceof Comment || $data['topic_id'] == null) {
            return $object;
        }

        $topic = new Topic();
        $topic->setId(isset($data['topic_id']) ? intval($data['topic_id']) : null);
        $topic->setTitle(isset($data['topic_title']) ? $data['topic_title'] : null);
        $topic->setSlug(isset($data['topic_slug']) ? $data['topic_slug'] : null);
        $topic->setContent(isset($data['topic_content']) ? $data['topic_content'] : null);
        $topic->setCreated(isset($data['topic_created']) ? $data['created'] : null);
        $object->setTopic($topic);

        return $object;
    }
}