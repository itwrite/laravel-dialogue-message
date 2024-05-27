<?php

namespace Itwri\DialogueMessageService\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Itwri\DialogueMessageService\Traits\ModelTimeFormatTrait;

class Dialogue extends Model
{
    use HasFactory,ModelTimeFormatTrait,SoftDeletes;

    protected $fillable = ['name','create_user_id','channel','member_count','last_message_created_at'];

    protected $hidden = ['deleted_at'];

    public function members()
    {
        return $this->hasMany(DialogueMember::class,'dialogue_id');
    }
}
