<?php

namespace App\Http\Validators;

use App\Http\Validators\BaseValidator;

class PostValidator extends BaseValidator
{
    public function create() {
        return [
            'title' => 'required|string|min:3|max:50|unique:posts,title',
            'content' => 'required|string|min:10|max:1000',
            'image.*' => 'sometimes|required|mimes:png,jpg,jpeg',
        ];
    }

    public function update($post){
        return [
            'title' => 'sometimes|required|string|min:3|max:50|unique:posts,title,'.$post->id,
            'content' => 'sometimes|required|string|min:10|max:1000',
            'image.*' => 'sometimes|required|mimes:png,jpg,jpeg',
        ];
    }
}
