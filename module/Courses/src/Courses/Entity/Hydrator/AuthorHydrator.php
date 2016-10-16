<?php

namespace Courses\Entity\Hydrator;

use Courses\Entity\Course;
//use Courses\Entity\Topic;
use User\Entity\User;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AuthorHydrator implements HydratorInterface
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
        if (!$object instanceof Course ||$object->getAuthor() == null) {
            return array();
        }

        $author = $object->getAuthor();

        return array(
            'id' => $author->getId(),
            'firstName' => $author->getFirstName(),
            'lastName' => $author->getLastName(),
            'email' => $author->getEmail(),
            'created' => $author->getCreated(),
            'userGroup' => $author->getUserGroup(),
            'nickname' => $author->getNickname(),
            'gender' => $author->getGender(),
            'age' => $author->getAge(),
            'bio' => $author->getBio(),
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
        if (!$object instanceof Course || $data['author_id'] == null) {
            return $object;
        }

        $author = new User();
        $author->setId(isset($data['author_id']) ? intval($data['author_id']) : null);
        $author->setFirstName(isset($data['author_first_name']) ? $data['author_first_name'] : null);
        $author->setLastName(isset($data['author_last_name']) ? $data['author_last_name'] : null);
        $author->setEmail(isset($data['author_email']) ? $data['author_email'] : null);
        $author->setCreated(isset($data['author_created']) ? $data['author_created'] : null);
        $author->setUserGroup(isset($data['author_user_group']) ? $data['author_user_group'] : null);
        $author->setNickname(isset($data['author_nickname']) ? $data['author_nickname'] : null);
        $author->setGender(isset($data['author_gender']) ? $data['author_gender'] : null);
        $author->setAge(isset($data['author_age']) ? $data['author_age'] : null);
        $author->setBio(isset($data['author_bio']) ? $data['author_bio'] : null);
        $object->setAuthor($author);

        return $object;
    }
}