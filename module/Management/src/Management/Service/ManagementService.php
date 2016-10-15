<?php

namespace Management\Service;

use Management\Entity\Tag;
use Management\Entity\Category;

interface ManagementService
{
    /*
     * Categories' options
     */

    public function saveCategory(Category $category);

    public function fetchCategories($page);

    public function findCategory($categorySlug);

    public function updateCategory(Category $category);

    public function deleteCategory($categoryId);
    
    
    /*
     * Tags' options
     */

    public function saveTag(Tag $category);

    public function fetchTags($page);

    public function findTag($tagSlug);

    public function updateTag(Tag $category);

    public function deleteTag($tagId);
    

} 