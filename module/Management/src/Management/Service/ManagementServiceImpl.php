<?php

namespace Management\Service;

use Management\Entity\Tag;
use Management\Entity\Category;

use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\Aggregate\AggregateHydrator;

class ManagementServiceImpl implements ManagementService
{
    /**
     * @var \Management\Repository\ManagementRepository $managementRepository
     */
    protected $managementRepository;

    
    /*
     * Categories' options
     */

    public function saveCategory(Category $category){
         $this->managementRepository->saveCategory($category);
    }

    public function fetchCategories($page){
        $this->managementRepository->fetchCategories($page);
    }

    public function findCategory($categorySlug){
        $this->managementRepository->findCategory($categorySlug);
    }

    public function updateCategory(Category $category){
        $this->managementRepository->updateCategory($category);
    }

    public function deleteCategory($categoryId){
        $this->managementRepository->deleteCategory($categoryId);
    }
    
    
    /*
     * Tags' options
     */

    public function saveTag(Tag $tag){
       $this->managementRepository->saveTag($tag); 
    }

    public function fetchTags($page){
        $this->managementRepository->fetchTags($page);
    }

    public function findTag($tagSlug){
        $this->managementRepository->findTag($tagSlug);
    }

    public function updateTag(Tag $tag){
         $this->managementRepository->updateTag($tag);  
    }

    public function deleteTag($tagId){
        $this->managementRepository->deleteTag($tagId);
    }
    
    /**
     * @param \Management\Repository\ManagementRepository $managementRepository
     */
    public function setManagementRepository($managementRepository)
    {
        $this->managementRepository = $managementRepository;
    }

    /**
     * @return \Management\Repository\ManagementRepository
     */
    public function getManagementRepository()
    {
        return $this->managementRepository;
    }
            

    

}