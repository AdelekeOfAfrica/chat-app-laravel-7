<?php

namespace App;

use App\MessageGroup;
use App\User;
use Illuminate\Database\Eloquent\Model;

class MessageGroupMember extends Model
{
    //
    protected $table="message_groups_members";
    protected $fillable = ['user_id','message_group_id','status'];

    public function message_group(){
        return $this->belongsTo(MessageGroup::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
