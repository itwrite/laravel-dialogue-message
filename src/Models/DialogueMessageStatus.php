<?php

namespace Itwri\DialogueMessageService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Itwri\DialogueMessageService\Traits\ModelTimeFormatTrait;

class DialogueMessageStatus extends Model
{
    use HasFactory,ModelTimeFormatTrait,SoftDeletes;

    protected $fillable = ['dialogue_id','dialogue_member_id','is_read','is_removed'];

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

    public function dialogueMember()
    {
        return $this->belongsTo(DialogueMember::class,'dialogue_member_id');
    }
}
