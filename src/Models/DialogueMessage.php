<?php

namespace Itwri\DialogueMessageService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Itwri\DialogueMessageService\Traits\ModelTimeFormatTrait;

class DialogueMessage extends Model
{
    use HasFactory,ModelTimeFormatTrait,SoftDeletes;

    protected $fillable = ['dialogue_id','dialogue_member_id','type','content'];

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

    public function DialogueMember()
    {
        return $this->belongsTo(DialogueMember::class,'dialogue_member_id');
    }
}
