<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class Profile extends Model
{
    use HasFactory;
    protected $fillable = [
        'username',
        'bio',
        'name',
        'email',
        'user_id',
    ];

//    protected $hidden = [
//        'user_id',
//    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('m/d/Y');
        // return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();

    }

}
