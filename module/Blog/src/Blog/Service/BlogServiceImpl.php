<?php

namespace Blog\Service;

use Blog\Entity\Post;
use Blog\Entity\Comment;
use Blog\Entity\Tag;

class BlogServiceImpl implements BlogService
{
    
    protected $postRepository;



    public function setBlogRepository($postRepository)
    {
        $this->postRepository = $postRepository;
    }


    public function getBlogRepository()
    {
        return $this->postRepository;
    }
    
    /**
     * Saves a blog post
     *
     * @param Post $post
     * @param int $authorId
     *
     * @return Post
     */
    public function save(Post $post,$slug, $authorId)
    {
        return $this->postRepository->save($post, $slug,$authorId);
    }

    public function fetch($page)
    {
        return $this->postRepository->fetch($page);
    }

    public function find($categorySlug, $postSlug,$posted)
    {
        return $this->postRepository->find($categorySlug, $postSlug,$posted);
    }


    public function findById($postId)
    {
        return $this->postRepository->findById($postId);
    }

    public function update(Post $post,$slug)
    {
        $this->postRepository->update($post,$slug);
    }

    public function delete($postId)
    {
        $this->postRepository->delete($postId);
    }
    
    
    /*
     * Comments service
     * 
    */
    
    public function saveComment(Comment $comment, $authorId,$postId)
    {
        $this->postRepository->saveComment($comment, $authorId,$postId);
    }
    
    public function deleteComment($commentId)
    {
        $this->postRepository->deleteComment($commentId);
    }
    
    public function findCommentById($commentId)
    {
        return $this->postRepository->findCommentById($commentId);
    }
    
    public function findCommentsByPost($postId,$page)
    {
        return $this->postRepository->findCommentsByPost($postId,$page);
    }
    
    /*
     * Tags service
     * 
    */
    
    public function saveTag(Tag $tag)
    {
        return $this->postRepository->saveTag($tag);
    }
    
    
    public function findTag($tagName)
    {
        return $this->postRepository->findTag($tagName);
    }
    
    public function addTagToPost(Tag $tag,$post)
    {
        $this->postRepository->addTagToPost($tag,$post);
    }
    
     public function findTagsByPost($postId,$page)
    {
        return $this->postRepository->findTagsByPost($postId,$page);
    }
    
  

   
}