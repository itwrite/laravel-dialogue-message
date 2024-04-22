<?php

namespace Itwri\DialogueMessageService\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Itwri\DialogueMessageService\Traits\ModelTimeFormatTrait;

class DialogMessageStatus extends Model
{
    use HasFactory,ModelTimeFormatTrait,SoftDeletes;

    protected $fillable = ['dialog_id','dialog_member_id','is_hidden'];

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

    public function dialogMember()
    {
        return $this->belongsTo(DialogMember::class,'dialog_member_id');
    }
}
