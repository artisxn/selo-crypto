<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminUserMessage extends Model
{
    protected $fillable = ['conversation_id','message','user_id', 'admin_id'];
	public function conversation()
	{
	    return $this->belongsTo('App\Models\AdminUserConversation','conversation_id');
	}

	public function admin()
	{
		return $this->belongsTo('App\Models\Admin', 'admin_id');
	}
}
