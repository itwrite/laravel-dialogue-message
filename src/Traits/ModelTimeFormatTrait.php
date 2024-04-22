<?php

namespace Itwri\DialogueMessageService\Traits;

use Illuminate\Support\Carbon;

trait ModelTimeFormatTrait
{
    /**
     * @param $attr
     * @return string
     * @author zzp
     * @date 2022/6/9
     */
    public function getCreatedAtAttribute($attr) {
        $this->append('created_at_text');
        return Carbon::parse($attr)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'); //Change the format to whichever you desire
    }

    /**
     * -------------------------------------------
     * -------------------------------------------
     * @param $attr
     * @return string
     * itwri 2024/3/30 1:04
     */
    public function getCreatedAtTextAttribute($attr)
    {
        $d = Carbon::parse($this->created_at)->setTimezone(config('app.timezone'));
        if($d->diffInHours() > 24){
            return $d->format('Y-m-d H:i:s');
        }
        return $d->diffForHumans();
    }

    /**
     * @param $attr
     * @return string
     * @author zzp
     * @date 2022/6/9
     */
    public function getUpdatedAtAttribute($attr) {
        $this->append('updated_at_text');
        return Carbon::parse($attr)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s'); //Change the format to whichever you desire
    }

    /**
     * -------------------------------------------
     * -------------------------------------------
     * @param $attr
     * @return string
     * itwri 2024/3/30 1:04
     */
    public function getUpdatedAtTextAttribute($attr)
    {
        $d = Carbon::parse($this->updated_at)->setTimezone(config('app.timezone'));
        if($d->diffInHours() > 24*7){
            return $d->format('Y-m-d');
        }
        return $d->diffForHumans();
    }
}
