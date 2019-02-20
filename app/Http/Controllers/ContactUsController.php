<?php

namespace App\Http\Controllers;

use App\Mail\ContactUsEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;

class ContactUsController extends Controller
{
    /**
     * @param Request $request
     * Contact us form request by public user
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]);

        $input = Input::except('_token');
        \App\ContactUs::create($input);

        $ContactUs = config('mail.from.ContactUs');

        Mail::to($ContactUs)->send(new ContactUsEmail($request));
        \Session::flash('message', 'Your Message has been received successfully');
        $backUrl=url('/#contact');
        return redirect($backUrl);
    }
}
