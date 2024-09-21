<?php

namespace App\Services;

use App\Http\Validators\PostValidator;
use App\Interfaces\Post\PostRepositoryInterface;
use App\Interfaces\Post\PostServiceInterface;
use App\Models\Post;

class PostService implements PostServiceInterface
{

    private $postRepository, $validator;

    public function __construct(PostRepositoryInterface $postRepository, PostValidator $postValidator){
        $this->postRepository = $postRepository;
        $this->validator = $postValidator;
    }

    public function getAllPosts($pages = 10, string $user_id = null)
    {
        return $this->postRepository->getAll($pages, $user_id);
    }

    public function getPostById(string $id)
    {
        return $this->postRepository->getById($id);
    }

    public function getPostByTitle(string $title)
    {
        return $this->postRepository->getByTitle($title);
    }

    public function getPostsByUserId($pages = 10, string $user_id)
    {
        return $this->getAllPosts($pages, $user_id);
    }

    public function getPostByUserId(string $user_id)
    {
        return $this->postRepository->getByUserId($user_id);
    }

    public function createPost()
    {
        $validated = $this->validator->validated($this->validator->create());
        $validatedData = (array) $validated->getData()->data;
        $validatedData['user_id'] = auth()->user()->id;
        return $this->postRepository->create($validatedData);
    }

    public function updatePost(Post $post)
    {
        if($id = $post?->id){
            if($this->postRepository->getById($id)){
            if($post->user_id == auth()->user()->id){
            $validated = $this->validator->validated($this->validator->update($post));
              $validatedData = (array) $validated->getData()->data;
            //   print_r($validatedData);die;
              $validatedData['user_id'] = $post->id;
              $this->postRepository->update($post, $validatedData);
              return $post;
              } else{
                return null;
             }
            }
        }
        
        return null;
    }

    public function deletePost(Post $post)
    {
        if($post = $this->postRepository->getById($post->id)){
            if($post->user_id == auth()->user()->id){
                 return $this->postRepository->delete($post);
            } else{
                return null;
            }
        }
        return null;
    }
}
