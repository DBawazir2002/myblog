<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'user_id',
        'image',
        'created_at'
    ];

    protected $hidden = [
        'user_id',
        'updated_at'
    ];

    public function getCreatedAtAttribute($date){
        return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
    }

//     public function getImageAttribute($image){
// //         $publicPath = '/public';
// //        return $publicPath.'/files/'. $image;
//       //  return '/files/'. $image;
//         // return '/storage/'.$image;
//         // return '/storage/public/'.$image;
//         return Storage::path($image);


//     }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function favorites() {
        return $this->belongsToMany(User::class,'favorites');
    }

    public function likes() {
        return $this->belongsToMany(User::class,'likes');
    }
}
