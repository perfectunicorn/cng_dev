<?php

namespace Management\Repository;

use Management\Entity\Tag;
use Management\Entity\Category;

use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;

class ManagementRepositoryImpl implements ManagementRepository
{
    use AdapterAwareTrait;
 
     /*
     * Categories' options
     */
    
    public function saveCategory(Category $category)
    {
          
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'name' => $category->getName(),
                'slug' => $category->getSlug(),
            ))
            ->into('category');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    public function fetchCategories($page)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id',
                'name',
                'slug',
            ))
            ->from(array('p' => 'category'))
            ->order('p.name DESC');

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CategoryHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Category());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(100);

        return $paginator;
    }

    public function findCategory($categorySlug)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id',
                'name',
                'slug',
            ))
            ->from(array('p' => 'category'))
            ->where(array(
                'p.slug' => $categorySlug,
            ))
            ->order('p.name DESC');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new CategoryHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Category());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }

    
    public function updateCategory(Category $category)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->update('category')
            ->set(array(
                'name' => $category->getTitle(),
                'slug' => $category->getSlug(),
            ))
            ->where(array(
                'id' => $category->getId(),
            ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    public function deleteCategory($categoryId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $delete = $sql->delete()
            ->from('category')
            ->where(array(
                'id' => $categoryId,
            ));

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }
    
    /*
     * Tags' options
     */
    
    public function saveTag(Tag $tag)
    {
          
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->insert()
            ->values(array(
                'name' => $tag->getName(),
                'slug' => $tag->getSlug(),
            ))
            ->into('tags');

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    public function fetchTags($page)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id',
                'name',
                'slug',
            ))
            ->from(array('p' => 'tags'))
            ->order('p.name DESC');

        $hydrator = new AggregateHydrator();
        $hydrator->add(new TagHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Tag());
        $paginatorAdapter = new \Zend\Paginator\Adapter\DbSelect($select, $this->adapter, $resultSet);
        $paginator = new \Zend\Paginator\Paginator($paginatorAdapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage(100);

        return $paginator;
    }

    public function findTag($tagSlug)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $select = $sql->select();
        $select->columns(array(
                'id',
                'name',
                'slug',
            ))
            ->from(array('p' => 'tags'))
            ->where(array(
                'p.slug' => $tagSlug,
            ))
            ->order('p.name DESC');

        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();

        $hydrator = new AggregateHydrator();
        $hydrator->add(new TagHydrator());

        $resultSet = new HydratingResultSet($hydrator, new Tag());
        $resultSet->initialize($results);

        return ($resultSet->count() > 0 ? $resultSet->current() : null);
    }

    
    public function updateTag(Tag $tag)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $insert = $sql->update('tags')
            ->set(array(
                'name' => $tag->getTitle(),
                'slug' => $tag->getSlug(),
            ))
            ->where(array(
                'id' => $tag->getId(),
            ));

        $statement = $sql->prepareStatementForSqlObject($insert);
        $statement->execute();
    }

    public function deleteTag($tagId)
    {
        $sql = new \Zend\Db\Sql\Sql($this->adapter);
        $delete = $sql->delete()
            ->from('tags')
            ->where(array(
                'id' => $tagId,
            ));

        $statement = $sql->prepareStatementForSqlObject($delete);
        $statement->execute();
    }
}