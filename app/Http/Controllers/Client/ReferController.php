<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ReferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $referralBonus = array_key_exists('referral_bonus',SiteSetting()->toArray()) ? SiteSetting()['referral_bonus'] : 0;
       
        return view('frontend.referral.refer_a_friend',compact('referralBonus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function sendReferralLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'referral_email' => 'required|email',
            
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
   
        $link = url('').'/register-form?referby='.base64_encode($user =\Auth::user()->id);
       
        $data = array(
            'link'=>$link,
            'subject'=>"Referral link",
            'email'=>$request->referral_email
        );
      
        Mail::send('emails.referral_email', $data, function ($message) use ($data) {
            $message->to($data['email'])
                ->subject($data['subject']);
        });

        flash()->success('Email sent.');
        return redirect()->back();
    }
}
