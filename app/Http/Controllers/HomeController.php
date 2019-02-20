<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Feedback;
use App\Mail\FeedbackMail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
    /**
     * Save feedback and then send email to admin.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendFeedback(Request $request)
    {
        $user_id   = \Auth::user()->id;
        $question_response =  json_encode($request->data_field);
        
        //Save the feedback in database table
        $model = new Feedback();
        $model->user_id = $user_id;
        $model->question_response = $question_response;
        $model->save();

        //Send email to admin
        $email = ADMIN_EMAIL;
        $mailsend = \Mail::to($email)->send(new FeedbackMail($model));
        if (\Mail::failures()) {
            return "false";
        }
        return "true";
    }




}
