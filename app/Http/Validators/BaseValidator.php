<?php

namespace App\Http\Validators;

use App\Traits\ResponseHelper;
use Laravel\Lumen\Routing\ProvidesConvenienceMethods;

 class BaseValidator
{
    use ProvidesConvenienceMethods, ResponseHelper;

     public function validated(array $rules)
     {
         return  $this->response($this->validate(request(), $rules));
     }
}
