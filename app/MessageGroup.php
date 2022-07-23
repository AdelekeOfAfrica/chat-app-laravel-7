<?php

namespace App;

use App\MessageGroupMember;
use Illuminate\Database\Eloquent\Model;

class MessageGroup extends Model
{
    //
    protected $table="message_groups";
    protected $fillable =['user_id','name'];

    public function message_group_members(){
        return $this->hasMany(MessageGroupMember::class);
    }

    public function user_messages(){
        return $this->hasMany(UserMessages::class);
    }
}
