<?php

namespace App\Http\Controllers\Authenticated;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SessionsOffer;
use App\Models\SessionsOnline;
use App\Traits\AjaxResponse;
use Validator;

class SessionController extends Controller
{
    use AjaxResponse;

    public function index () {
        $countOfSessionsInHomePage = 12;
        $sessions = SessionsOffer::orderBy('id','desc')->paginate($countOfSessionsInHomePage);
        $sessions->transform(function($session) {
            if($session->duration != null) {
                if($session->duration == 1) {
                    $session->duration =  __('masseges.hour');
                } elseif($session->duration == 2) {
                    $session->duration =  __('masseges.two-hours');
                } elseif($session->duration < 10 && $session->duration > 0) {
                    $session->duration = $session->duration . ' ' . __('masseges.hours');
                } elseif($session->duration > 0) {
                    $session->duration = $session->duration . ' ' . __('masseges.hour');
                } else {
                    $session->duration = __('masseges.duration-not-fixed');
                }
            } else {
                $session->duration = __('masseges.duration-not-fixed');
            }
            if($session->price == 0) $session->price = null;
            return $session;
        });
        return view('authenticated.session', ['sessions' => $sessions]);
    }
    public function getSessionOfferData($id) {
        $offer = SessionsOffer::find($id);
        if(! $offer) return abort(404);
        return $this->getResponse(true,'',$offer);
    }

    public function requestSession(Request $request) {
        $data = array();
        if($request->has('date')) $data['date'] = $request->input('date');
        if($request->has('time')) $data['time'] = $request->input('time');
        if($request->has('id')) {
            $offer = SessionsOffer::find($request->input('id'));
            if(! $offer) return abort(404);
            $data['sessions_offer_id'] = $request->input('id');
        }
        $rules = [
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
            'sessions_offer_id' => 'integer',
        ];
        $validator = Validator::make($data,$rules);
        if($validator->fails()) {
            return $this->getResponse(false, __('masseges.data-not-valid'), ['invalid']);
        }
        $data['time'] = $data['date'] . ' ' . $data['time'];
        $data['user_id'] = auth()->user()->id;
        if(strtotime($data['time']) <= time()) {
            return $this->getResponse(false, __('masseges.data-not-valid'), ['invalid']);
        }
        $session = SessionsOnline::create($data);
        if(! $session) {
            return $this->getResponse(false, __('masseges.session-deleted'), null);
        }
        return $this->getResponse(true, __('masseges.session-online-requested'), null);
    }
}
