<?php
//
namespace Blog\Repository;

use Application\Repository\RepositoryInterface;
use Blog\Entity\Post;
use Blog\Entity\Comment;
use Blog\Entity\Tag;

interface PostRepository extends RepositoryInterface
{
    /**
     * Saves a blog post
     *
     * @param Post $post
     * @param int $authorId
     *
     * @return void
     */
    public function save(Post $post, $slug,$authorId);

    public function fetch($page);

    public function find($categorySlug, $postSlug,$posted);

    public function findById($postId);

    public function update(Post $post,$slug);

    public function delete($postId);
    
    /*
     * Comments 
     */
    
    public function saveComment(Comment $comment, $authorId,$postId);
    
    public function deleteComment($commentId);
    
    public function findCommentById($commentId);
    
    public function findCommentsByPost($postId,$page);
    
    /*
     * Tags
     */
    
    public function saveTag(Tag $tag);
    
    public function findTag($tagName);
    
    public function addTagToPost(Tag $tag,$post);
    
    public function findTagsByPost($postId,$page);
    
    
}
