<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'phone', 'phone_verified_at','password'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
        'phone_verified_at',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('m/d/Y');
        // return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();

    }

    public function profile() {
        return $this->hasOne(Profile::class);
    }

    public function posts() {
        return $this->hasMany(Post::class);
    }

    public function favorites() {
        return $this->belongsToMany(Post::class,'favorites');
    }

    public function likes() {
        return $this->belongsToMany(Post::class,'likes');
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'followed_id', 'follower_id');
    }

    public function follows()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'followed_id');
    }

    public function follow(User $user)
    {
        if (!$this->isFollowing($user)) {
            $this->follows()->attach($user->id);
        }
    }

    public function unfollow(User $user)
    {
        if ($this->isFollowing($user)) {
            $this->follows()->detach($user->id);
        }
    }

    public function isFollowing(User $user)
    {
        $followCount = $this->follows()->where('followed_id', $user->id)->count();
        return $followCount > 0;
    }
    

}
