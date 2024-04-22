<?php

namespace Itwri\DialogueMessageService\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Itwri\DialogueMessageService\Traits\ModelTimeFormatTrait;

class Dialogue extends Model
{
    use HasFactory,ModelTimeFormatTrait,SoftDeletes;

    protected $fillable = ['name','type'];

    protected $hidden = ['deleted_at'];

}
