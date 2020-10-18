<?php 

namespace App\Traits;

trait FormatTime {
    function convertToBeforeFormat($time) {
        $tempTime = strtotime($time);
        if(empty($tempTime)) return '';
        if($tempTime >= time()) return __('time.now');
        $target = time() - $tempTime;
        $periods = array("second", "minute", "hour", "day", "week", "month", "year");
        $lengths = array("60","60","24","7","4.35","12");
        for($i = 0; $target >= $lengths[$i] && $i < count($lengths)-1; $i++) $target /= $lengths[$i];
        $target = round($target);
        $target = ($target == 1) ? ( __('time.one-' . $periods[$i]) ) :
            ( ($target == 2) ? ( __('time.two-' . $periods[$i]) ) :
                ( ($target < 11) ? ( $target . ' ' . __('time.' . $periods[$i] . 's') ) : 
                ( $target . ' ' . __('time.one-' . $periods[$i]) )
            )
        );
        return __('time.before') . ' ' . $target;
    }
}