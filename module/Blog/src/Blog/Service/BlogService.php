<?php

namespace Blog\Service;

use Blog\Entity\Post;
use Blog\Entity\Comment;
use Blog\Entity\Tag;


interface BlogService
{
    /*
     * Post service
     * 
    */
    
    public function save(Post $post,$slug, $authorId);

    public function fetch($page);

    public function find($categorySlug, $postSlug,$posted);

    public function findById($postId);
    
    public function findByUser($userId);

    public function update(Post $post,$slug);

    public function delete($postId);
    
    
    /*
     * Comments service
     * 
    */
    
    public function saveComment(Comment $comment, $authorId,$postId);
    
    public function deleteComment($commentId);
    
    public function findCommentById($commentId);
    
    public function findCommentsByPost($postId,$page);
    
    
    /*
     * Tags service
     * 
    */
    
    public function saveTag(Tag $tag);
    
    public function findTag($tagName);
    
    public function addTagToPost(Tag $tag,$post);
    
    public function findTagsByPost($postId,$page);
    
    
     

} 