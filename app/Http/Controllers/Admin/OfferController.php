<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SessionsOffer;
use App\Models\SessionsOnline;
use App\Traits\AjaxResponse;
use App\Jobs\EmailUserToRejectionDeletedSessionsOffer;
use Validator;
use Storage;
class OfferController extends Controller
{
    use AjaxResponse;
    
    public function index() {
        $offers = SessionsOffer::orderBy('id','desc')->paginate(10);
        $offers->transform(function($offer) {
            $offer['hasSessions'] = ($offer->sessionsOnlines->count() > 0);
            return $offer;
        });
        return view('admin.sessionOffer')->with('offers',$offers);
    }
    public function store(Request $request) {
        $data = array();
        if($request->has('name')) $data['name'] = $request->input('name');
        if($request->has('price')) $data['price'] = $request->input('price');
        if($request->has('duration')) $data['duration'] = $request->input('duration');
        if($request->has('for_who')) $data['for_who'] = $request->input('for_who');
        if($request->has('for_who_not')) $data['for_who_not'] = $request->input('for_who_not');
        if($request->has('benefits')) $data['benefits'] = $request->input('benefits');
        if($request->has('notes')) $data['notes'] = $request->input('notes');
        if($request->hasFile('poster')) $data['poster'] = $request->file('poster');
        $rules = [
            'name' => 'required|string|max:255|min:1',
            'price' => 'required|numeric',
            'duration' => 'required|numeric',
            'for_who' => 'string',
            'for_who_not' => 'string',
            'benefits' => 'string',
            'notes' => 'string',
            'poster' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
        ];
        $validator = Validator::make($data,$rules);
        if($validator->fails()){
            return $this->getResponse(false, __('masseges.data-not-valid'),[]);
        }
        if($request->hasFile('poster')) {
            $ex = $request->file('poster')->getClientOriginalExtension();
            $name = str_replace('.', '-', time() . uniqid('offer',true)) . '.' . $ex;
            $path = '/public/images/upload/';
            $request->file('poster')->storeAs($path,$name);
            $data['poster'] = '/storage/images/upload/' . $name;
        }
        $offer = SessionsOffer::create($data);
        if( ! $offer) {
            return $this->getResponse(false, __('masseges.general-error'),[]);
        }
        return $this->getResponse(true, __('masseges.add-ok'),$offer);
    }
    public function delete($id) {
        $offer = SessionsOffer::find($id);
        if(! $offer) return abort(404);

        $sessions = $offer->sessionsOnlines->where('taken', '=', '0');
        foreach($sessions as $session) {
            dispatch(new EmailUserToRejectionDeletedSessionsOffer(
                $session->user->first_name,
                $session->sessionOffer->name,
                $session->user->email,
            ));
        }
        $offer->delete();
        return $this->getResponse(true, __('masseges.delete-ok'),[]);
    }
    public function update(Request $request) {
        if( ! $request->has('id')) return abort('404');
        $offer = SessionsOffer::find($request->input('id'));
        if(! $offer) {
            return abort('404');
        }
        $data = array();
        if($request->has('name')) $data['name'] = $request->input('name');
        if($request->has('price')) $data['price'] = $request->input('price');
        if($request->has('duration')) $data['duration'] = $request->input('duration');
        if($request->has('for_who')) $data['for_who'] = $request->input('for_who');
        if($request->has('for_who_not')) $data['for_who_not'] = $request->input('for_who_not');
        if($request->has('benefits')) $data['benefits'] = $request->input('benefits');
        if($request->has('notes')) $data['notes'] = $request->input('notes');
        if($request->hasFile('poster')) $data['poster'] = $request->file('poster');
        $rules = [
            'name' => 'string|max:255|min:1',
            'price' => 'numeric',
            'duration' => 'numeric',
            'for_who' => 'string',
            'for_who_not' => 'string',
            'benefits' => 'string',
            'notes' => 'string',
            'poster' => 'file|max:2000|mimetypes:image/jpeg,image/png,image/gif,image/bmp,image/svg',
        ];
        $validator = Validator::make($data,$rules);
        if($validator->fails()){
            return $this->getResponse(false, __('masseges.data-not-valid'),[]);
        }
        if($request->hasFile('poster')) {
            $ex = $request->file('poster')->getClientOriginalExtension();
            $name = str_replace('.', '-', time() . uniqid('offer',true)) . '.' . $ex;
            $path = '/public/images/upload/';
            $request->file('poster')->storeAs($path,$name);
            $data['poster'] = '/storage/images/upload/' . $name;
            $oldPosterSrc = str_replace('storage', 'public', $offer->poster);
        }
        if($offer->update($data)) {
            if($request->hasFile('poster')) Storage::disk('local')->delete($oldPosterSrc);
            return $this->getResponse(true, __('masseges.update-ok'),$data);
        }
        if($request->hasFile('poster')) Storage::disk('local')->delete($path);
        return $this->getResponse(false, __('masseges.general-error'),[]);
    }
    public function getSessions($id) {
        $offer = SessionsOffer::find($id);
        if(! $offer) return abort(404);
        $sessions = SessionsOnline::where('sessions_offer_id', '=', $id)->orderBy('id', 'desc')->paginate(10);
        if(! $sessions) return $this->getResponse(false, __('masseges.general-error'),[]);
        $sessions->transform(function($session) {
            $data = array (
                'id' => $session->id,
                'time' => Date('F j, Y, g:i a',strtotime($session->time)),
                'userName' => $session->user->first_name,
                'userImage' => $session->user->image,
                'taken' => $session->taken,
                'admission' => $session->admission,
            );
            return $data;
        });
        return $this->getResponse(true, '',$sessions);
    }
}
