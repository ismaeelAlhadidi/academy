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

    function setAvailabilityTimeOfBlob($availabilityTime, $subscriptionTime, $playlistTime) {
        if($playlistTime == null) return ($subscriptionTime == null) ? __('masseges.available-for-subscription') : __('masseges.available');
        if($availabilityTime == null && $subscriptionTime == null) {
            return __('masseges.available-for-subscription');
        }
        if($availabilityTime == null) {
            return __('masseges.available');
        }
        $availabilityTime = strtotime($availabilityTime);
        $subscriptionTime = strtotime($subscriptionTime);
        $playlistTime = strtotime($playlistTime);
        if($availabilityTime <= $playlistTime) return ($subscriptionTime == null) ? __('masseges.available-for-subscription') : __('masseges.available');

        if($subscriptionTime == null) {
            $target = $availabilityTime - $playlistTime;
        } else {
            $availability = $availabilityTime - $playlistTime;
            if($availability < 0) return __('masseges.available');
            $target = time() - $subscriptionTime;
            if($target >= $availability) return __('masseges.available');
            $target = $target - $availability;
        }

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

        if($subscriptionTime == null) {
            return __('time.after-subscription') . ' ' . $target;
        }
        return __('time.after') . ' ' . $target;
    }

    function blobIsAvailable($availabilityTime, $subscriptionTime, $playlistTime) {
        if($subscriptionTime == null) return false;
        if($availabilityTime == null) return true;
        if($playlistTime == null) return true;
        $availabilityTime = strtotime($availabilityTime);
        $subscriptionTime = strtotime($subscriptionTime);
        $playlistTime = strtotime($playlistTime);
        $availability = $availabilityTime - $playlistTime;
        if($availability < 0) return true;
        $target = time() - $subscriptionTime;
        if($target >= $availability) return true;
        return false;
    }

    function convertToReadableTime($time) {
        return Date('F j, Y, g:i a',strtotime($time));
    }
}