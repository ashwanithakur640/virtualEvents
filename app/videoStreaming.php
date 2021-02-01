<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Overtrue\LaravelFollow\Traits\CanBeLiked;
use Auth;
use App\likeable;
class videoStreaming extends Model
{
    //

	protected $table  = 'video_streamings';
    protected $primarykey = 'id';
    protected $fillable = ['u_id','cname','s3_file','sid','views'];
    public function comments()
    {
        return $this->hasMany(Comment::class,'video_id')->whereNull('parent_id')->whereType('1');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likes()
    {
        return $this->hasMany('App\likeable','post_id')->whereLike('1');
    }
    public function disLikes()
    {
        return $this->hasMany('App\likeable','post_id')->whereLike('0');
    }

    public function hasLiked($id) {
        // do something
        $like = likeable::where('post_id',$id)->where('user_id',Auth::id())->where('like','1')->first();
        if(!empty($like))
        {
            return true;
        }
        else{
            return false;
        }
    }

    public function hasDisliked($id) {
        // do something
        $dislike = likeable::where('post_id',$id)->where('user_id',Auth::id())->where('like','0')->first();
        if(!empty($dislike))
        {
            return true;
        }
        else{
            return false;
        }
    }
}
