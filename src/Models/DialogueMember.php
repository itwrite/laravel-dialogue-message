<?php

namespace Itwri\DialogueMessageService\Models;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Itwri\DialogueMessageService\Traits\ModelTimeFormatTrait;

class DialogueMember extends Model
{
    use HasFactory,ModelTimeFormatTrait,SoftDeletes;

    protected $fillable = ['dialogue_id','user_id'];

    protected $hidden = ['deleted_at'];

    /**
     * -------------------------------------------
     * -------------------------------------------
     * @return BelongsTo
     * itwri 2024/4/23 0:31
     */
    public function dialogue()
    {
        return $this->belongsTo(Dialogue::class,'dialogue_id');
    }

    public function user()
    {
        return $this->belongsTo(config('dialogue.user_model',User::class));
    }
}
