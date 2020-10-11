<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['comment_id', 'user_id','text'];
    
    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function comment()
    {
    	return $this->belongsTo('App\Models\Comment');
    }

	public function subreplies()
	{
	     return $this->hasMany('App\Models\SubReply');
	}
}
