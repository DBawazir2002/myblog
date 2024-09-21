<?php

namespace App\Repositories;

use App\Interfaces\Post\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class PostRepository implements PostRepositoryInterface
{

    public function getAll($pages = 10, string $user_id = null)
    {
        $post = Post::with(['user.profile'])->first();
        return isset($user_id)
            ? Post::where('user_id', $user_id)->with('user.profile')->paginate($pages)
            : Post::with('user.profile')->paginate($pages);

    }

    public function getById(string $id)
    {
        return Post::where('id', $id)->with('user.profile')->first();
    }

    public function getByTitle(string $title)
    {
        return Post::where('title', $title)->with('user.profile')->first();
    }

    public function getByUserId($pages = 10, string $user_id)
    {
        return Post::where('user_id', $user_id)->with('user.profile')->first();
    }

    public function create(array $data)
    {
        $imagePath = 'postsImages/default.png';


        if(request()->hasFile('image')){
            $image = request()->file('image');
            $imageName = Str::random(10).'.'.$image->getClientOriginalExtension();
            $imagePath = $image->storeAs('postsImages', $imageName,'public');
            //$publicPath = base_path().'/public';
            //$image->move($publicPath.'/files', $imageName);
            //$imagePath = $publicPath.'/files/'. $imageName;
        }


        $post = Post::create([
            'title' => $data['title'],
            'slug' => Str::slug($data['title']),
            'content' =>  $data['content'],
            'user_id' => $data['user_id'],
            'image' => $imagePath
        ]);
//        return Post::create($data);
        return $post;
    }

    public function update(Post $post, array $data)
    {
//        return $post->update($data);
// print_r($data);die;
        $imagePath = null;
        if(request()->hasFile('image')){
            $image = request()->file('image');
            $imageName = Str::random(10).'.'.$image->getClientOriginalExtension();
             $imagePath = $image->storeAs('postsImages', $imageName, 'public');
           // $publicPath = base_path().'/public';
            //$image->move($publicPath.'/files', $imageName);
            //$imagePath = $publicPath.'/files/'. $imageName;
        }
        
        if(isset($imagePath)){
            if(! str_contains($post->image, 'default.png')) {
            Storage::delete($post->image);
            }
        }else{
            $imagePath = $post->image;
        }
        

        $post->update([
            'title' => $data['title'] ?? $post->title   ,
            'slug' => (isset($data['title'])) ? Str::slug($data['title']) : $post->slug,
            'content' =>  $data['content'] ?? $post->content,
            'image' => $imagePath
            ]);
        return $post;
    }

    public function delete(Post $post)
    {
        if(! str_contains($post->image, 'default.png')) {
           // unlink($post->image);
            Storage::delete($post->image);
        }
        return $post->delete();
    }
}
