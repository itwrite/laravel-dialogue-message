<?php

namespace Itwri\DialogueMessageService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User;
use Itwri\DialogueMessageService\Traits\ModelTimeFormatTrait;

class DialogMember extends Model
{
    use HasFactory,ModelTimeFormatTrait,SoftDeletes;

    protected $fillable = ['dialog_id','user_id'];

    protected $hidden = ['deleted_at'];

    /**
     * -------------------------------------------
     * -------------------------------------------
     * @return BelongsTo
     * itwri 2024/4/23 0:31
     */
    public function dialog()
    {
        return $this->belongsTo(Dialogue::class,'dialog_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
